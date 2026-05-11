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

<div class="col-sm-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Pengguna</h6>
      <h2><?= $userStats['total_users'] ?? 0 ?></h2>
    </div>
  </div>
</div>
<div class="col-sm-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Soal</h6>
      <h2><?= $userStats['total_soal'] ?? 0 ?></h2>
    </div>
  </div>
</div>
<div class="col-sm-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Level</h6>
      <h2><?= $userStats['total_level'] ?? 0 ?></h2>
    </div>
  </div>
</div>
<div class="col-sm-3">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Peserta</h6>
      <h2><?= $userStats['total_peserta'] ?? 0 ?></h2>
    </div>
  </div>
</div>
<div class="col-sm-12">
  <div class="card">
    <div class="card-body">
      <h6 class="mb-3">Total Kuis Selesai</h6>
      <h2><?= $userStats['total_kuis'] ?? 0 ?></h2>
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
