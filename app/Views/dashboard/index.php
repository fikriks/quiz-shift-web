<?= $this->extend('layouts/app') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5>Selamat Datang, <?= esc($currentUser['nama_lengkap'] ?? 'User') ?>!</h5>
    </div>
    <div class="card-body">
      <p class="text-muted">Anda login sebagai <span class="badge bg-primary"><?= esc($currentUser['hak_akses'] ?? '-') ?></span></p>
    </div>
  </div>
</div>

<div class="col-sm-4">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Soal</h6>
      <div class="d-flex align-items-center justify-content-between">
        <h2 class="mb-0"><?= $userStats['total_soal'] ?? 0 ?></h2>
        <div class="text-end text-muted small">
          <?php if (($currentUser['hak_akses'] ?? '') === 'ADMIN'): ?>
            Elementary: <strong><?= $userStats['soal_elementary'] ?? 0 ?></strong><br>
            High School: <strong><?= $userStats['soal_high_school'] ?? 0 ?></strong>
          <?php else: ?>
            Jenjang: <strong><?= esc($currentUser['jenjang'] ?? '-') ?></strong>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-4">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Level</h6>
      <div class="d-flex align-items-center justify-content-between">
        <h2 class="mb-0"><?= $userStats['total_level'] ?? 0 ?></h2>
        <div class="text-end text-muted small">
          <?php if (!empty($levels)): ?>
            <?php foreach ($levels as $lvl): ?>
              <?php 
                $badgeClass = 'bg-secondary';
                if ($lvl['nama_level'] === 'BEGINNER') $badgeClass = 'bg-primary';
                elseif ($lvl['nama_level'] === 'INTERMEDIATE') $badgeClass = 'bg-warning text-dark';
                elseif ($lvl['nama_level'] === 'ADVANCED') $badgeClass = 'bg-danger';
              ?>
              <span class="badge <?= $badgeClass ?>"><?= esc($lvl['nama_level']) ?></span><br>
            <?php endforeach; ?>
          <?php else: ?>
            -
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-sm-4">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Peserta</h6>
      <h2 class="mb-0"><?= $userStats['total_peserta'] ?? 0 ?></h2>
    </div>
  </div>
</div>
<div class="col-sm-12">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Kuis Selesai</h6>
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <h2 class="mb-0"><?= $userStats['total_kuis'] ?? 0 ?></h2>
        <div class="d-flex flex-wrap gap-4 text-muted small mt-2 mt-md-0">
          <?php if (($currentUser['hak_akses'] ?? '') === 'ADMIN'): ?>
            <div>
              <span class="d-block text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Berdasarkan Jenjang</span>
              Elementary: <strong><?= $userStats['kuis_elementary'] ?? 0 ?></strong><br>
              High School: <strong><?= $userStats['kuis_high_school'] ?? 0 ?></strong>
            </div>
          <?php endif; ?>
          <div>
            <span class="d-block text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Berdasarkan Level</span>
            Beginner: <strong><?= $userStats['kuis_beginner'] ?? 0 ?></strong><br>
            Intermediate: <strong><?= $userStats['kuis_intermediate'] ?? 0 ?></strong><br>
            Advanced: <strong><?= $userStats['kuis_advanced'] ?? 0 ?></strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-sm-12">
  <div class="card">
    <div class="card-header">
      <h5>Informasi Akun</h5>
    </div>
    <div class="card-body">
      <table class="table">
        <tr>
          <td width="200">Username</td>
          <td>: <?= esc($currentUser['nama_pengguna'] ?? '-') ?></td>
        </tr>
        <tr>
          <td>Nama Lengkap</td>
          <td>: <?= esc($currentUser['nama_lengkap'] ?? '-') ?></td>
        </tr>
        <tr>
          <td>Hak Akses</td>
          <td>: <span class="badge bg-primary"><?= esc($currentUser['hak_akses'] ?? '-') ?></span></td>
        </tr>
        <tr>
          <td>Status</td>
          <td>: <span class="badge bg-success">AKTIF</span></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
