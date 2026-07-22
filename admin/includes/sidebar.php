<?php
// Pastikan ada parameter halaman di URL
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 'dashboard';
?>

<!-- Sidebar -->
<div class="sidebar" data-background-color="white">
    <div class="sidebar-logo">
        <!-- Logo Header -->
<div class="logo-header" data-background-color="white">
    <a href="index.php" class="logo">
        <img src="assets/img/logo.svg" alt="navbar brand" class="navbar-brand" height="65" width="100">
    </a>

    <div class="header-right-buttons"> <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
            <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
        </div>
        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
    </div>
</div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <!-- Dashboard -->
                <li class="nav-item <?= ($halaman == 'dashboard') ? 'active' : '' ?>">
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Menu</h4>
                </li>

                <!-- Daftar Produk -->
                <li class="nav-item <?= ($halaman == 'daftarproduk') ? 'active' : '' ?>">
                    <a href="index.php?halaman=daftarproduk">
                        <i class="fas fa-box"></i>
                        <p>Daftar Produk</p>
                    </a>
                </li>

                <!-- Daftar Katalog -->
                <li class="nav-item <?= ($halaman == 'daftarkategori') ? 'active' : '' ?>">
                    <a href="index.php?halaman=daftarkategori">
                        <i class="fas fa-th-list"></i>
                        <p>Daftar Kategori</p>
                    </a>
                </li>

                <!-- Pesanan -->
                <li class="nav-item <?= in_array($halaman, ['daftarpesanan', 'menunggupembayaran', 'pesananbaru', 'pesanandiproses', 'pesanandikirim', 'pesananselesai']) ? 'active' : '' ?>">
                    <a data-bs-toggle="collapse" href="#pesanan">
                        <i class="fas fa-truck"></i>
                        <p>Daftar Pesanan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse <?= in_array($halaman, ['daftarpesanan', 'menunggupembayaran', 'pesananbaru', 'pesananselesai', 'pesanandiproses', 'pesanandikirim','pesananselesai']) ? 'show' : '' ?>" id="pesanan">
                        <ul class="nav nav-collapse">
                            <li><a href="index.php?halaman=daftarpesanan"><span class="sub-item">Semua Pesanan</span></a></li>
                            <li><a href="index.php?halaman=menunggupembayaran"><span class="sub-item">Menunggu Pembayaran</span></a></li>
                            <li><a href="index.php?halaman=pesananbaru"><span class="sub-item">Pesanan Baru</span></a></li>
                            <li><a href="index.php?halaman=pesanandiproses"><span class="sub-item">Pesanan Diproses</span></a></li>
                            <li><a href="index.php?halaman=pesanandikirim"><span class="sub-item">Pesanan Dikirim</span></a></li>
                            <li><a href="index.php?halaman=pesananselesai"><span class="sub-item">Pesanan Selesai</span></a></li>
                        </ul>
                    </div>
                </li>

                <!-- Laporan -->
                <li class="nav-item <?= ($halaman == 'daftarlaporan') ? 'active' : '' ?>">
                    <a href="index.php?halaman=daftarlaporan">
                        <i class="fas fa-file"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                <!-- Manajer -->
                <!-- Manajer -->
                <?php if ($_SESSION['level'] === 'manajer'): ?>
                    <li class="nav-item <?= ($halaman == 'daftarmanajer') ? 'active' : '' ?>">
                        <a href="index.php?halaman=daftarmanajer" class="manajer-link">
                            <i class="fas fa-user-tie"></i>
                            <p>Manajer</p>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="#" class="manajer-link blocked">
                            <i class="fas fa-user-tie"></i>
                            <p>Manajer</p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Admin -->
                <?php if ($_SESSION['level'] === 'manajer'): ?>
                <li class="nav-item <?= ($halaman == 'daftaradmin') ? 'active' : '' ?>">
                    <a href="index.php?halaman=daftaradmin">
                        <i class="fas fa-user"></i>
                        <p>Admin</p>
                    </a>
                </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="#" class="manajer-link blocked">
                            <i class="fas fa-user"></i>
                            <p>Admin</p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Pelanggan -->
                <li class="nav-item <?= ($halaman == 'daftarpelanggan') ? 'active' : '' ?>">
                    <a href="index.php?halaman=daftarpelanggan">
                        <i class="fas fa-users"></i>
                        <p>Pelanggan</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="white">
              <a href="index.html" class="logo">
                <img
                  src="assets/img/kaiadmin/logo_light.svg"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>