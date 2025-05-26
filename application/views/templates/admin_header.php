<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/img/apple-icon.png') ?>">
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
  <title>
    <?= $title ?> - Gallery Kembang Ilung Admin
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="<?= base_url('assets/css/argon-dashboard.css?v=2.1.0') ?>" rel="stylesheet" />
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
  <!-- Custom CSS -->
  <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-gradient-success position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="<?= base_url('beranda/index') ?>">
        <img src="<?= base_url('assets/img/gallery-logo.png') ?>" class="navbar-brand-img h-100" alt="gallery_logo">
        <span class="ms-1 font-weight-bold text-warning">Gallery Kembang Ilung</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'dasbor' ? 'active' : '' ?>" href="<?= base_url('admin/dasbor') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dasbor</span>
          </a>
        </li>

        <!-- <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'kategori' ? 'active' : '' ?>" href="<?= base_url('admin/kategori') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tag text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Kategori Lowongan</span>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'lamaran' || $this->uri->segment(2) == 'detail_lamaran' ? 'active' : '' ?>" href="<?= base_url('admin/lamaran') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Lamaran</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'penilaian' ? 'active' : '' ?>" href="<?= base_url('admin/penilaian') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-ruler-pencil text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penilaian</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'lowongan' || $this->uri->segment(2) == 'tambahLowongan' || $this->uri->segment(2) == 'edit_lowongan' ? 'active' : '' ?>" href="<?= base_url('admin/lowongan') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-briefcase-24 text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Lowongan</span>
          </a>
        </li>

        <!-- Next project -->
        <!-- <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'koleksi' || $this->uri->segment(2) == 'detail_koleksi' ? 'active' : '' ?>" href="<?= base_url('admin/koleksi') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-palette text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Koleksi Kerajinan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'pesanan' ? 'active' : '' ?>" href="<?= base_url('admin/pesanan') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-shopping-cart text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Pesanan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'workshop' || $this->uri->segment(2) == 'tambah_workshop' || $this->uri->segment(2) == 'edit_workshop' ? 'active' : '' ?>" href="<?= base_url('admin/workshop') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-graduation-cap text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Workshop & Pelatihan</span>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'blog' ? 'active' : '' ?>" href="<?= base_url('admin/blog') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-newspaper text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Artikel & Blog</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'pengguna' ? 'active' : '' ?>" href="<?= base_url('admin/pengguna') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-users text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Pengguna</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'laporan' ? 'active' : '' ?>" href="<?= base_url('admin/laporan') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-bar-32 text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Laporan</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Halaman Akun</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $this->uri->segment(2) == 'profil' ? 'active' : '' ?>" href="<?= base_url('admin/profil') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profil</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('auth/logout') ?>">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-button-power text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Keluar</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="<?= base_url('admin/dasbor') ?>">Admin</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page"><?= $title ?></li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0"><?= $title ?></h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <!-- <div class="input-group">
              <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
              <input type="text" class="form-control" placeholder="Cari di sini...">
            </div> -->
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="<?= base_url('admin/profil') ?>" class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none"><?= $this->session->userdata('full_name') ?></span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="<?= base_url('assets/img/customer-1.jpg') ?>" class="avatar avatar-sm  me-3 ">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">Pesanan baru</span> dari Siti Rahayu
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          13 menit yang lalu
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <div class="avatar avatar-sm bg-gradient-success me-3 d-flex align-items-center justify-content-center">
                          <i class="fas fa-graduation-cap text-white"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                          <span class="font-weight-bold">Workshop baru</span> terdaftar
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          1 hari
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <?php if ($this->session->flashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show d-none" role="alert">
          <?= $this->session->flashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <?php if ($this->session->flashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show d-none" role="alert">
          <?= $this->session->flashdata('error') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
