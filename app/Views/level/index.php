<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Level
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Daftar Level</h5>
        <a href="<?= site_url('level/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Level
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Nama Level</th>
              <th>Deskripsi</th>
              <th width="100">Nilai Min</th>
              <th width="100">Nilai Max</th>
              <th width="200">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($levels ?? [])): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada level</td>
              </tr>
            <?php else: ?>
              <?php
                $no = 1;
                $badgeColors = [
                    'BEGINNER'     => 'bg-primary',
                    'INTERMEDIATE' => 'bg-warning',
                    'ADVANCED'     => 'bg-danger'
                ];
                foreach ($levels ?? [] as $level):
                  $badgeClass = $badgeColors[$level['nama_level']] ?? 'bg-secondary';
              ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><span class="badge <?= $badgeClass ?>"><?= esc($level['nama_level']) ?></span></td>
                <td><?= esc($level['deskripsi'] ?? '-') ?></td>
                <td><?= esc($level['nilai_min']) ?></td>
                <td><?= esc($level['nilai_max']) ?></td>
                <td>
                  <a href="<?= site_url('level/edit/' . $level['id_level']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-pencil"></i> Edit
                  </a>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDeleteLevel(<?= $level['id_level'] ?>)">
                    <i class="ti ti-trash"></i> Hapus
                  </button>
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
function confirmDeleteLevel(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus level ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('level/delete/') ?>' + id;

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
            title: 'Hapus Level',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}
</script>
<?= $this->endSection() ?>
