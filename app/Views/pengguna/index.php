<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Pengguna
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Daftar Instruktur</h5>
        <a href="<?= site_url('pengguna/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Instruktur
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="50">No</th>
              <th>Username</th>
              <th>Nama Lengkap</th>
              <th>Jenjang</th>
              <th>Status</th>
              <th width="350">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($pengguna ?? [])): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada pengguna instruktur</td>
              </tr>
            <?php else: ?>
              <?php $no = 1; foreach ($pengguna ?? [] as $p): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($p['nama_pengguna']) ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td>
                  <span class="badge <?= $p['jenjang'] === 'ELEMENTARY' ? 'bg-info' : 'bg-secondary' ?>">
                    <?= $p['jenjang'] === 'ELEMENTARY' ? 'ELEMENTARY' : 'HIGH SCHOOL' ?>
                  </span>
                </td>
                <td>
                  <span class="badge <?= $p['status'] === 'AKTIF' ? 'bg-success' : 'bg-danger' ?>">
                    <?= esc($p['status']) ?>
                  </span>
                </td>
                <td>
                  <a href="<?= site_url('pengguna/edit/' . $p['id_pengguna']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-pencil"></i> Edit
                  </a>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDeletePengguna(<?= $p['id_pengguna'] ?>)">
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
function confirmDeletePengguna(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus instruktur ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('pengguna/delete/') ?>' + id;

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
            title: 'Hapus Instruktur',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}
</script>
<?= $this->endSection() ?>
