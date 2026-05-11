
<!DOCTYPE html>
<html lang="id">
<head>
  <title><?= $this->renderSection('title') ?> | QuizShift</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Mantis is made using Bootstrap 5 design framework. Download the free admin template & use it for your project.">
  <meta name="keywords" content="Mantis, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template">
  <meta name="author" content="CodedThemes">

  <!-- [Favicon] icon -->
  <link rel="icon" href="<?= base_url('assets/images/favicon.svg') ?>" type="image/x-icon"> <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="<?= base_url('assets/fonts/tabler-icons.min.css') ?>" >
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="<?= base_url('assets/fonts/feather.css') ?>" >
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="<?= base_url('assets/fonts/fontawesome.css') ?>" >
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="<?= base_url('assets/fonts/material.css') ?>" >
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" id="main-style-link" >
<link rel="stylesheet" href="<?= base_url('assets/css/style-preset.css') ?>" >

<!-- Notyf CSS -->
<link rel="stylesheet" href="<?= base_url('assets/css/plugins/notyf.min.css') ?>" />

<!-- Additional Page-specific CSS -->
<?= $this->renderSection('styles') ?>

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
<div class="loader-bg">
  <div class="loader-track">
    <div class="loader-fill"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
 <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <a href="<?= site_url('dashboard') ?>" class="b-brand text-primary">
        <!-- ========   Change your logo from here   ============ -->
        <img src="<?= base_url('assets/images/logo.png') ?>" class="img-fluid logo-lg" alt="QuizShift Logo" style="width: 60px;">
      </a>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item">
          <a href="<?= site_url('dashboard') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>Manajemen Kuis</label>
          <i class="ti ti-news"></i>
        </li>

        <li class="pc-item">
          <a href="<?= site_url('soal') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-help"></i></span>
            <span class="pc-mtext">Soal</span>
          </a>
        </li>

        <li class="pc-item">
          <a href="<?= site_url('level') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-flag"></i></span>
            <span class="pc-mtext">Level</span>
          </a>
        </li>

        <?php if (($currentUser['hak_akses'] ?? '') === 'ADMIN'): ?>
        <li class="pc-item">
          <a href="<?= site_url('peserta') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-users"></i></span>
            <span class="pc-mtext">Peserta</span>
          </a>
        </li>
        <?php endif; ?>

        <li class="pc-item">
          <a href="<?= site_url('hasil') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-chart-bar"></i></span>
            <span class="pc-mtext">Hasil Kuis</span>
          </a>
        </li>

        <li class="pc-item pc-caption">
          <label>Akun</label>
          <i class="ti ti-user"></i>
        </li>

        <li class="pc-item">
          <a href="<?= site_url('logout') ?>" class="pc-link">
            <span class="pc-micon"><i class="ti ti-logout"></i></span>
            <span class="pc-mtext">Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end --> <!-- [ Header Topbar ] start -->
<header class="pc-header">
  <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
<div class="me-auto pc-mob-drp">
  <ul class="list-unstyled">
    <!-- ======= Menu collapse Icon ===== -->
    <li class="pc-h-item pc-sidebar-collapse">
      <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="pc-h-item pc-sidebar-popup">
      <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
        <i class="ti ti-menu-2"></i>
      </a>
    </li>
    <li class="dropdown pc-h-item d-inline-flex d-md-none">
      <a
        class="pc-head-link dropdown-toggle arrow-none m-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        aria-expanded="false"
      >
        <i class="ti ti-search"></i>
      </a>
    </li>
  </ul>
</div>
<!-- [Mobile Media Block end] -->
<div class="ms-auto">
  <ul class="list-unstyled">
    <li class="dropdown pc-h-item header-user-profile">
      <a
        class="pc-head-link dropdown-toggle arrow-none me-0"
        data-bs-toggle="dropdown"
        href="#"
        role="button"
        aria-haspopup="false"
        data-bs-auto-close="outside"
        aria-expanded="false"
      >
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($currentUser['nama_lengkap'] ?? 'Guest') ?>&background=random&color=fff" alt="user-image" class="user-avtar">
        <span><?= esc($currentUser['nama_lengkap'] ?? 'Guest') ?></span>
      </a>
      <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
        <div class="dropdown-header">
          <div class="d-flex mb-1">
            <div class="flex-shrink-0">
              <img src="https://ui-avatars.com/api/?name=<?= urlencode($currentUser['nama_lengkap'] ?? 'Guest') ?>&background=random&color=fff" alt="user-image" class="user-avtar wid-35">
            </div>
            <div class="flex-grow-1 ms-3">
              <h6 class="mb-1"><?= esc($currentUser['nama_lengkap'] ?? 'Guest') ?></h6>
              <span><?= esc($currentUser['hak_akses'] ?? '-') ?></span>
            </div>
          </div>
        </div>
        <a href="<?= site_url('logout') ?>" class="dropdown-item">
          <i class="ti ti-power"></i>
          <span>Logout</span>
        </a>
      </div>
    </li>
  </ul>
</div>
 </div>
</header>
<!-- [ Header ] end -->

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <!-- <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Home</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard/index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Dashboard</a></li>
                <li class="breadcrumb-item" aria-current="page">Home</li>
              </ul>
            </div>
          </div>
        </div>
      </div> -->
      <!-- [ breadcrumb ] end -->
      <!-- [ Main Content ] start -->
      <div class="row">
        <?= $this->renderSection('content') ?>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col-sm my-1">
          <p class="m-0">Copyright &copy; QuizShift.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- [Page Specific JS] end -->
  <!-- Required Js -->
  <script src="<?= base_url('assets/js/plugins/popper.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/simplebar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/fonts/custom-font.js') ?>"></script>
  <script src="<?= base_url('assets/js/pcoded.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/feather.min.js') ?>"></script>    
  <!-- Notyf JavaScript -->
  <script src="<?= base_url('assets/js/plugins/notyf.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/toast-helper.js') ?>"></script>
  <!-- Flash Messages (loaded after Notyf) -->
  <?= $this->include('partials/flash_messages') ?>

  <!-- Additional Page-specific JavaScript -->
  <?= $this->renderSection('scripts') ?>
</body>
<!-- [Body] end -->

</html>