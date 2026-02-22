<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Detail Hasil Kuis
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <!-- Quiz Info -->
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5>Informasi Kuis</h5>
        <a href="<?= site_url('hasil') ?>" class="btn btn-secondary">
          <i class="ti ti-arrow-left"></i> Kembali
        </a>
      </div>
    </div>
    <div class="card-body">
      <table class="table">
        <tr>
          <td width="200">Nama Kuis</td>
          <td>: <?= esc($kuis['nama_kuis']) ?></td>
        </tr>
        <tr>
          <td>Peserta</td>
          <td>: <?= esc($kuis['nama_lengkap']) ?> (<?= esc($kuis['email']) ?>)</td>
        </tr>
        <tr>
          <td>Waktu Mulai</td>
          <td>: <?= esc($kuis['waktu_mulai']) ?></td>
        </tr>
        <tr>
          <td>Waktu Selesai</td>
          <td>: <?= esc($kuis['waktu_selesai'] ?? '-') ?></td>
        </tr>
        <tr>
          <td>Status</td>
          <td>: <span class="badge bg-success"><?= esc($kuis['status']) ?></span></td>
        </tr>
      </table>
    </div>
  </div>
</div>

<div class="col-sm-12">
  <!-- Statistics -->
  <div class="row">
    <div class="col-sm-3">
      <div class="card">
        <div class="card-body text-center">
          <h6 class="mb-2">Total Soal</h6>
          <h2 class="text-primary"><?= $statistik['total_soal'] ?></h2>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card">
        <div class="card-body text-center">
          <h6 class="mb-2">Jawaban Benar</h6>
          <h2 class="text-success"><?= $statistik['total_benar'] ?></h2>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card">
        <div class="card-body text-center">
          <h6 class="mb-2">Jawaban Salah</h6>
          <h2 class="text-danger"><?= $statistik['total_salah'] ?></h2>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="card">
        <div class="card-body text-center">
          <h6 class="mb-2">Persentase</h6>
          <h2 class="text-info"><?= $statistik['persentase'] ?>%</h2>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-sm-12">
  <!-- Final Result -->
  <div class="card">
    <div class="card-header">
      <h5>Hasil Akhir</h5>
    </div>
    <div class="card-body text-center">
      <?php if ($kuis['level_ditetapkan']): ?>
        <?php
        $levelClass = match($kuis['level_ditetapkan']) {
          'BEGINNER' => 'bg-success',
          'INTERMEDIATE' => 'bg-info',
          'ADVANCED' => 'bg-warning',
          default => 'bg-secondary'
        };
        ?>
        <h3>Level Anda: <span class="badge <?= $levelClass ?> fs-4"><?= esc($kuis['level_ditetapkan']) ?></span></h3>
        <p class="text-muted mt-2">Nilai: <?= $kuis['total_nilai'] ?>/100</p>
      <?php else: ?>
        <h4>Nilai: <span class="badge bg-primary"><?= $kuis['total_nilai'] ?></span></h4>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="col-sm-12">
  <!-- Answer Details -->
  <div class="card">
    <div class="card-header">
      <h5>Detail Jawaban</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th width="50">No</th>
              <th width="150">Level</th>
              <th>Pertanyaan</th>
              <th width="100">Jawaban Siswa</th>
              <th width="100">Jawaban Benar</th>
              <th width="100">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($detail ?? [])): ?>
              <tr>
                <td colspan="6" class="text-center text-muted">Belum ada jawaban</td>
              </tr>
            <?php else: ?>
              <?php $no = 1; foreach ($detail ?? [] as $d): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><span class="badge bg-info"><?= esc($d['nama_level']) ?></span></td>
                <td><?= esc($d['pertanyaan']) ?></td>
                <td><span class="badge bg-primary"><?= esc($d['jawaban_siswa']) ?></span></td>
                <td><span class="badge bg-success"><?= esc($d['jawaban_benar']) ?></span></td>
                <td>
                  <?php if ($d['is_benar']): ?>
                    <span class="badge bg-success"><i class="ti ti-check"></i> Benar</span>
                  <?php else: ?>
                    <span class="badge bg-danger"><i class="ti ti-x"></i> Salah</span>
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
