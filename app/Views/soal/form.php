<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
<?= isset($soal) ? 'Edit Soal' : 'Tambah Soal Baru' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5><?= isset($soal) ? 'Edit Soal' : 'Tambah Soal Baru' ?></h5>
    </div>
    <div class="card-body">
      <form action="<?= isset($soal) ? site_url('soal/update/' . $soal['id_soal']) : site_url('soal') ?>"
            method="POST">
        <?= csrf_field() ?>

        <div class="mb-3">
          <label class="form-label">Level *</label>
          <select name="id_level" class="form-select" required>
            <option value="">Pilih Level</option>
            <?php foreach ($levels ?? [] as $level): ?>
              <option value="<?= $level['id_level'] ?>"
                      <?= (isset($soal['id_level']) && $soal['id_level'] == $level['id_level']) ? 'selected' : '' ?>>
                <?= esc($level['nama_level']) ?> (<?= $level['nilai_min'] ?> - <?= $level['nilai_max'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Jenjang *</label>
          <?php if ($currentUser['hak_akses'] === 'INSTRUKTUR'): ?>
            <input type="text" class="form-control" value="<?= esc($currentUser['jenjang'] === 'ELEMENTARY' ? 'ELEMENTARY LEVEL' : 'HIGH SCHOOL LEVEL') ?>" readonly>
            <input type="hidden" name="jenjang" value="<?= esc($currentUser['jenjang']) ?>">
          <?php else: ?>
            <select name="jenjang" class="form-select" required>
              <option value="ELEMENTARY" <?= (isset($soal['jenjang']) && $soal['jenjang'] === 'ELEMENTARY') || old('jenjang') === 'ELEMENTARY' ? 'selected' : '' ?>>ELEMENTARY LEVEL</option>
              <option value="HIGH_SCHOOL" <?= (isset($soal['jenjang']) && $soal['jenjang'] === 'HIGH_SCHOOL') || old('jenjang') === 'HIGH_SCHOOL' ? 'selected' : '' ?>>HIGH SCHOOL LEVEL</option>
            </select>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Pertanyaan *</label>
          <textarea name="pertanyaan" class="form-control" rows="4" placeholder="Tulis pertanyaan di sini..." required><?= esc($soal['pertanyaan'] ?? old('pertanyaan')) ?></textarea>
          <?php if (isset($validation) && $validation->hasError('pertanyaan')): ?>
            <div class="text-danger small"><?= $validation->getError('pertanyaan') ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="form-label">Opsi A *</label>
          <input type="text" name="opsi_a" class="form-control" placeholder="Pilihan jawaban A"
                 value="<?= esc($soal['opsi_a'] ?? old('opsi_a')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Opsi B *</label>
          <input type="text" name="opsi_b" class="form-control" placeholder="Pilihan jawaban B"
                 value="<?= esc($soal['opsi_b'] ?? old('opsi_b')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Opsi C *</label>
          <input type="text" name="opsi_c" class="form-control" placeholder="Pilihan jawaban C"
                 value="<?= esc($soal['opsi_c'] ?? old('opsi_c')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Opsi D *</label>
          <input type="text" name="opsi_d" class="form-control" placeholder="Pilihan jawaban D"
                 value="<?= esc($soal['opsi_d'] ?? old('opsi_d')) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Jawaban Benar *</label>
          <select name="jawaban_benar" class="form-select" required>
            <option value="">Pilih Jawaban</option>
            <option value="A" <?= (isset($soal['jawaban_benar']) && $soal['jawaban_benar'] === 'A') ? 'selected' : '' ?>>A</option>
            <option value="B" <?= (isset($soal['jawaban_benar']) && $soal['jawaban_benar'] === 'B') ? 'selected' : '' ?>>B</option>
            <option value="C" <?= (isset($soal['jawaban_benar']) && $soal['jawaban_benar'] === 'C') ? 'selected' : '' ?>>C</option>
            <option value="D" <?= (isset($soal['jawaban_benar']) && $soal['jawaban_benar'] === 'D') ? 'selected' : '' ?>>D</option>
          </select>
        </div>

        <div class="mb-3">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy"></i> Simpan
          </button>
          <a href="<?= site_url('soal') ?>" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
