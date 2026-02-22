<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
<?= isset($peserta) ? 'Edit Peserta' : 'Tambah Peserta Baru' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5><?= isset($peserta) ? 'Edit Peserta' : 'Tambah Peserta Baru' ?></h5>
    </div>
    <div class="card-body">
      <form action="<?= isset($peserta) ? site_url('peserta/update/' . $peserta['id_peserta']) : site_url('peserta') ?>"
            method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Username *</label>
          <input type="text" name="username" class="form-control" placeholder="Contoh: peserta123"
                 value="<?= esc($peserta['username'] ?? old('username')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label"><?= isset($peserta) ? 'Password (kosongkan jika tidak diubah)' : 'Password *' ?></label>
          <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter"
                 <?= !isset($peserta) ? 'required' : '' ?>>
          <?php if (!isset($peserta)): ?>
          <small class="text-muted">Minimal 6 karakter</small>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: John Doe"
                 value="<?= esc($peserta['nama_lengkap'] ?? old('nama_lengkap')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-control" placeholder="Contoh: email@example.com"
                 value="<?= esc($peserta['email'] ?? old('email')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Nomor HP</label>
          <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789"
                 value="<?= esc($peserta['no_hp'] ?? old('no_hp')) ?>">
        </div>

        <?php if (isset($peserta)): ?>
        <div class="mb-3">
          <label class="form-label">Token API</label>
          <div class="input-group">
            <input type="text" class="form-control" value="<?= esc($peserta['token']) ?>" readonly>
            <button type="button" class="btn btn-outline-secondary" onclick="copyToken('<?= esc($peserta['token']) ?>')">
              <i class="ti ti-copy"></i> Copy
            </button>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Status *</label>
          <select name="status" class="form-select" required>
            <option value="AKTIF" <?= (isset($peserta['status']) && $peserta['status'] === 'AKTIF') ? 'selected' : '' ?>>AKTIF</option>
            <option value="NONAKTIF" <?= (isset($peserta['status']) && $peserta['status'] === 'NONAKTIF') ? 'selected' : '' ?>>NONAKTIF</option>
          </select>
        </div>
        <?php endif; ?>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
          <a href="<?= site_url('peserta') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function copyToken(token) {
  navigator.clipboard.writeText(token).then(function() {
    notyf.success('Token berhasil disalin!');
  }, function() {
    notyf.error('Gagal menyalin token');
  });
}
</script>
<?= $this->endSection() ?>
