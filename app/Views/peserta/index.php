<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Daftar Peserta
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Daftar Peserta</h5>
        <a href="<?= site_url('peserta/create') ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i> Tambah Peserta
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
              <th>Email</th>
              <th>No. HP</th>
              <th>Status</th>
              <th width="350">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($peserta ?? [])): ?>
              <tr>
                <td colspan="7" class="text-center text-muted">Belum ada peserta</td>
              </tr>
            <?php else: ?>
              <?php $no = 1; foreach ($peserta ?? [] as $p): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($p['username']) ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['email']) ?></td>
                <td><?= esc($p['no_hp'] ?? '-') ?></td>
                <td>
                  <span class="badge <?= $p['status'] === 'AKTIF' ? 'bg-success' : 'bg-danger' ?>">
                    <?= esc($p['status']) ?>
                  </span>
                </td>
                <td>
                  <a href="<?= site_url('peserta/edit/' . $p['id_peserta']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-pencil"></i> Edit
                  </a>
                  <button type="button" class="btn btn-sm btn-outline-danger"
                          onclick="confirmDeletePeserta(<?= $p['id_peserta'] ?>)">
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
function confirmDeletePeserta(id) {
    toast.confirm(
        'Apakah Anda yakin ingin menghapus peserta ini? Data yang dihapus tidak dapat dikembalikan.',
        function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= site_url('peserta/delete/') ?>' + id;

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
            title: 'Hapus Peserta',
            confirmText: 'Hapus',
            confirmClass: 'btn-danger'
        }
    );
}
</script>
<?= $this->endSection() ?>
