<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
<?= isset($level) ? 'Edit Level' : 'Tambah Level Baru' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5><?= isset($level) ? 'Edit Level' : 'Tambah Level Baru' ?></h5>
    </div>
    <div class="card-body">
      <form action="<?= isset($level) ? site_url('level/update/' . $level['id_level']) : site_url('level') ?>"
            method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Nama Level *</label>
          <select name="nama_level" class="form-select" required>
            <option value="">Pilih Level</option>
            <option value="BEGINNER" <?= (isset($level['nama_level']) && $level['nama_level'] === 'BEGINNER') ? 'selected' : '' ?>>BEGINNER</option>
            <option value="INTERMEDIATE" <?= (isset($level['nama_level']) && $level['nama_level'] === 'INTERMEDIATE') ? 'selected' : '' ?>>INTERMEDIATE</option>
            <option value="ADVANCED" <?= (isset($level['nama_level']) && $level['nama_level'] === 'ADVANCED') ? 'selected' : '' ?>>ADVANCED</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control" rows="3" placeholder="Contoh: Level untuk pemula dengan dasar-dasar bahasa Inggris"><?= esc($level['deskripsi'] ?? old('deskripsi')) ?></textarea>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Nilai Minimum *</label>
              <input type="number" name="nilai_min" class="form-control" placeholder="0"
                     value="<?= esc($level['nilai_min'] ?? old('nilai_min')) ?>" required min="0">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Nilai Maksimum *</label>
              <input type="number" name="nilai_max" class="form-control" placeholder="100"
                     value="<?= esc($level['nilai_max'] ?? old('nilai_max')) ?>" required min="0">
            </div>
          </div>
        </div>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
          <a href="<?= site_url('level') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
