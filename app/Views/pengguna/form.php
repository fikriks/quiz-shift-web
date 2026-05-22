<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
<?= isset($pengguna) ? 'Edit Instruktur' : 'Tambah Instruktur Baru' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5><?= isset($pengguna) ? 'Edit Instruktur' : 'Tambah Instruktur Baru' ?></h5>
    </div>
    <div class="card-body">
      <form action="<?= isset($pengguna) ? site_url('pengguna/update/' . $pengguna['id_pengguna']) : site_url('pengguna') ?>"
            method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Username *</label>
          <input type="text" name="nama_pengguna" class="form-control" placeholder="Contoh: instruktur123"
                 value="<?= esc($pengguna['nama_pengguna'] ?? old('nama_pengguna')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label"><?= isset($pengguna) ? 'Password (kosongkan jika tidak diubah)' : 'Password *' ?></label>
          <input type="password" name="kata_sandi" class="form-control" placeholder="Minimal 6 karakter"
                 <?= !isset($pengguna) ? 'required' : '' ?>>
          <?php if (!isset($pengguna)): ?>
          <small class="text-muted">Minimal 6 karakter</small>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Nama Lengkap *</label>
          <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Guru Bahasa Inggris"
                 value="<?= esc($pengguna['nama_lengkap'] ?? old('nama_lengkap')) ?>" required>
        </div>

        <?php if (isset($pengguna)): ?>
        <div class="mb-3">
          <label class="form-label">Status *</label>
          <select name="status" class="form-select" required>
            <option value="AKTIF" <?= (isset($pengguna['status']) && $pengguna['status'] === 'AKTIF') ? 'selected' : '' ?>>AKTIF</option>
            <option value="NONAKTIF" <?= (isset($pengguna['status']) && $pengguna['status'] === 'NONAKTIF') ? 'selected' : '' ?>>NONAKTIF</option>
          </select>
        </div>
        <?php endif; ?>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
          <a href="<?= site_url('pengguna') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
