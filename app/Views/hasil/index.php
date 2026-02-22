<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Hasil Kuis
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5>Daftar Hasil Kuis</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Nama Kuis</th>
              <th>Peserta</th>
              <th>Waktu Mulai</th>
              <th>Waktu Selesai</th>
              <th>Nilai</th>
              <th>Level</th>
              <th width="200">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($hasil ?? [])): ?>
              <tr>
                <td colspan="8" class="text-center text-muted">Belum ada hasil kuis</td>
              </tr>
            <?php else: ?>
              <?php $no = 1; foreach ($hasil ?? [] as $h): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($h['nama_kuis']) ?></td>
                <td><?= esc($h['nama_lengkap']) ?></td>
                <td><?= esc($h['waktu_mulai']) ?></td>
                <td><?= esc($h['waktu_selesai'] ?? '-') ?></td>
                <td>
                  <span class="badge bg-primary"><?= esc($h['total_nilai']) ?></span>
                </td>
                <td>
                  <?php if ($h['level_ditetapkan']): ?>
                    <?php
                    $levelClass = match($h['level_ditetapkan']) {
                      'BEGINNER' => 'bg-success',
                      'INTERMEDIATE' => 'bg-info',
                      'ADVANCED' => 'bg-warning',
                      default => 'bg-secondary'
                    };
                    ?>
                    <span class="badge <?= $levelClass ?>"><?= esc($h['level_ditetapkan']) ?></span>
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?= site_url('hasil/' . $h['id_kuis']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-eye"></i> Detail
                  </a>
                  <?php if ($currentUser['hak_akses'] === 'ADMIN'): ?>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDeleteHasil(<?= $h['id_kuis'] ?>)">
                    <i class="ti ti-trash"></i> Hapus
                  </button>
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
function confirmDeleteHasil(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus hasil kuis ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('hasil/delete/') ?>' + id;

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
            title: 'Hapus Hasil',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}
</script>
<?= $this->endSection() ?>
