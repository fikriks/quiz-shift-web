<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Soal
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Daftar Soal</h5>
        <?php if (in_array($currentUser['hak_akses'] ?? '', ['ADMIN', 'INSTRUKTUR'])): ?>
        <a href="<?= site_url('soal/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Soal
        </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="card-body">
      <!-- Filter by Level -->
      <div class="mb-3">
        <form method="GET" action="<?= site_url('soal') ?>">
          <div class="row">
            <div class="col-md-3">
              <select name="level" class="form-select">
                <option value="">Semua Level</option>
                <?php foreach ($levels ?? [] as $level): ?>
                  <option value="<?= $level['id_level'] ?>" <?= (isset($_GET['level']) && $_GET['level'] == $level['id_level']) ? 'selected' : '' ?>>
                    <?= esc($level['nama_level']) ?> (<?= $level['nilai_min'] ?> - <?= $level['nilai_max'] ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-secondary">Filter</button>
              <a href="<?= site_url('soal') ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
          </div>
        </form>
      </div>

      <!-- Questions Table -->
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Pertanyaan</th>
              <th width="100">Level</th>
              <th width="100">Jawaban</th>
              <th width="100">Status</th>
              <th width="250">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($soal ?? [])): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada soal</td>
              </tr>
            <?php else: ?>
              <?php
                $no = 1;
                $badgeColors = [
                    'BEGINNER'     => 'bg-primary',
                    'INTERMEDIATE' => 'bg-warning',
                    'ADVANCED'     => 'bg-danger'
                ];
                foreach ($soal ?? [] as $s):
                  $badgeClass = $badgeColors[$s['nama_level']] ?? 'bg-secondary';
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td>
                  <div class="question-text"><?= character_limiter(strip_tags($s['pertanyaan']), 100) ?></div>
                </td>
                <td><span class="badge <?= $badgeClass ?>"><?= esc($s['nama_level']) ?></span></td>
                <td><span class="badge bg-primary"><?= esc($s['jawaban_benar']) ?></span></td>
                <td>
                  <span class="badge <?= $s['status'] === 'AKTIF' ? 'bg-success' : 'bg-danger' ?>">
                    <?= esc($s['status']) ?>
                  </span>
                </td>
                <td>
                  <?php if (in_array($currentUser['hak_akses'] ?? '', ['ADMIN', 'INSTRUKTUR'])): ?>
                  <a href="<?= site_url('soal/edit/' . $s['id_soal']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-pencil"></i> Edit
                  </a>
                  <?php if ($currentUser['hak_akses'] === 'ADMIN'): ?>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDeleteSoal(<?= $s['id_soal'] ?>)">
                    <i class="ti ti-trash"></i> Hapus
                  </button>
                  <?php endif; ?>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDeleteSoal(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus soal ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('soal/delete/') ?>' + id;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        },
        null,
        {
            title: 'Hapus Soal',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}
</script>
<?= $this->endSection() ?>
