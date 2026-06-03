
<!DOCTYPE html>
<html lang="id">
<head>
  <title>Login | QuizShift</title>
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

<!-- Additional Page-specific CSS -->
  <?= $this->renderSection('styles') ?>
  <style>
    @media (min-width: 992px) {
      .desktop-left-padding {
        padding-left: 90px !important;
      }
      .desktop-right-padding {
        padding-right: 50px !important;
      }
    }
  </style>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="auth-header">
        </div>
        
        <div class="row align-items-center w-100 mx-0 my-auto py-4">
          <!-- Left side: logo and info (Desktop only) -->
          <div class="col-lg-6 pe-lg-5 d-none d-lg-block desktop-left-padding">
            <div class="mb-4">
              <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo UP Speaking Course" class="img-fluid" style="max-width: 200px;">
            </div>
            <h2 class="mb-4 text-dark fw-bold">UP Speaking Course</h2>
            <div class="contact-details mt-4 fs-5" style="line-height: 2;">
              <div class="mb-3 d-flex align-items-center">
                <i class="ti ti-brand-instagram fs-2 text-danger me-3"></i>
                <a href="https://instagram.com/up.speakingcourse" target="_blank" class="text-secondary text-decoration-none">@up.speakingcourse</a>
              </div>
              <div class="mb-3 d-flex align-items-center">
                <i class="ti ti-brand-whatsapp fs-2 text-success me-3"></i>
                <a href="https://wa.me/6281316701747" target="_blank" class="text-secondary text-decoration-none">+62 813-1670-1747</a>
              </div>
              <div class="mb-3 d-flex align-items-start">
                <i class="ti ti-map-pin fs-2 text-primary me-3 mt-1"></i>
                <span class="text-secondary text-start" style="max-width: 420px;">Jl. Aruji Kartawinata No.Dalam, Kuningan, Kec. Kuningan, Kabupaten Kuningan</span>
              </div>
            </div>
          </div>

          <!-- Right side: login card -->
          <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end desktop-right-padding">
            <div class="w-100" style="max-width: 480px;">
              <!-- Mobile logo and title -->
              <div class="text-center d-lg-none mb-4">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="img-fluid mb-2" style="max-width: 100px;">
                <h4 class="fw-bold text-dark">UP Speaking Course</h4>
              </div>

              <div class="card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.08); border-radius: 12px; border: none; background: #ffffff;">
                <div class="card-body p-4">
                  <div class="d-flex justify-content-between align-items-end mb-4">
                    <h3 class="mb-0"><b>Login</b></h3>
                  </div>
                  <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                  <?php endif; ?>
                  <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                  <?php endif; ?>
                  <?= form_open(site_url('login')) ?>
                  <div class="form-group mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="nama_pengguna" value="<?= esc(old('nama_pengguna')) ?>" class="form-control" placeholder="Username" required>
                  </div>
                  <div class="form-group mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="kata_sandi" class="form-control" placeholder="Password" required>
                  </div>
                  <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                  </div>
                  <?= form_close() ?>
                </div>
              </div>

              <!-- Mobile contact details -->
              <div class="contact-details mt-4 fs-6 d-lg-none text-center">
                <div class="mb-2">
                  <a href="https://instagram.com/up.speakingcourse" target="_blank" class="text-secondary text-decoration-none">
                    <i class="ti ti-brand-instagram text-danger me-1"></i> @up.speakingcourse
                  </a>
                  <span class="mx-2 text-muted">|</span>
                  <a href="https://wa.me/6281316701747" target="_blank" class="text-secondary text-decoration-none">
                    <i class="ti ti-brand-whatsapp text-success me-1"></i> +62 813-1670-1747
                  </a>
                </div>
                <div class="text-secondary small">
                  <i class="ti ti-map-pin text-primary me-1"></i> Jl. Aruji Kartawinata No.Dalam, Kuningan
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="auth-footer row">
          <div class="col my-1">
            <p class="m-0">Copyright © <a href="#">Quiz Shift</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  <!-- Required Js -->
  <script src="<?= base_url('assets/js/plugins/popper.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/simplebar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/fonts/custom-font.js') ?>"></script>
  <script src="<?= base_url('assets/js/pcoded.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/feather.min.js') ?>"></script>

  <!-- Additional Page-specific JavaScript -->
  <?= $this->renderSection('scripts') ?>
</body>
<!-- [Body] end -->

</html>