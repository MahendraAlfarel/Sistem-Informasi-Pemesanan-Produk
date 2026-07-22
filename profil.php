<?php
session_start();
ob_start();
include 'koneksi.php';
include 'functions/functions.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

$row = getProfilUser($_SESSION['id_pelanggan']);
if ($row) {
    $pelanggan_id = htmlspecialchars($row['id_pelanggan']);
    $pelanggan_nama = htmlspecialchars($row['nama_pelanggan']);
    $pelanggan_headphone = htmlspecialchars($row['headphone_pelanggan']);
    $pelanggan_email = htmlspecialchars($row['email_pelanggan']);
    $pelanggan_foto = htmlspecialchars(basename($row['foto_pelanggan']));
    $pelanggan_jalan = htmlspecialchars($row['alamat_pelanggan']);
    $pelanggan_provinsi = htmlspecialchars($row['nama_provinsi']);
    $pelanggan_kota = htmlspecialchars($row['nama_kota']);
    $pelanggan_kecamatan = htmlspecialchars($row['nama_kecamatan']);
    $pelanggan_kelurahan = htmlspecialchars($row['nama_kelurahan']);
    $pelanggan_kdps = htmlspecialchars($row['kode_pos']);
    $pelanggan_username = htmlspecialchars($row['username_pelanggan']);
}
$pelanggan_alamat = ("$pelanggan_jalan, $pelanggan_kelurahan, $pelanggan_kecamatan, $pelanggan_kota, $pelanggan_provinsi, $pelanggan_kdps");


function getHalamanProfil()
{
    return basename($_SERVER['PHP_SELF']);
}

$halamanProfil = getHalamanProfil();

$isHalamanProfil = ($halamanProfil == 'profil.php');

// Tambahkan variabel untuk mendeteksi apakah detail_pesanan sedang aktif
$isDetailPesanan = false;
if (isset($_GET['menunggu_pembayaran']) && $_GET['menunggu_pembayaran'] == 'detail_pesanan') {
    $isDetailPesanan = true;
} elseif (isset($_GET['daftar_pesanan']) && $_GET['daftar_pesanan'] == 'detail_pesanan') {
    $isDetailPesanan = true;
}

// $isActiveUP = $isHalamanProfil && isset($_GET['ubah_password']) && $_GET['ubah_password'] == 'true';
$isActiveMP = $isHalamanProfil && isset($_GET['menunggu_pembayaran']) && $_GET['menunggu_pembayaran'] == 'true';
$isActiveDP = $isHalamanProfil && isset($_GET['daftar_pesanan']) && $_GET['daftar_pesanan'] == 'true';

$isActiveDefault = $isHalamanProfil && !$isActiveMP && !$isActiveDP && !$isDetailPesanan;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PT Indo Kimia Abadi - Official Website</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="assets/img/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/icon.ico">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/templatemo.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Start Header -->
    <?php include 'includes/header.php'; ?>
    <!-- End Header -->

    <!-- Start Content Page
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">My profil</h1>
        </div>
    </div> -->
    <!-- Start profil Page -->
    <div class="container mt-5 mb-5 shadow-lg">
        <section class="h-100 py-5">
            <div class="row d-flex justify-content-center h-100">
                <div class="col-md-3 bg-wgite p-4 text-left profil-sidebar">
                    <h3 class="pb-3 border-bottom mb-2">Hi <?= ucfirst($pelanggan_username) ?></h3>
                    <div class="profil-menu-wrapper">
                        <a href="profil.php" class="profil-menu-item <?= ($isActiveDefault ? 'profil-menu-item-active' : ''); ?>">
                            <i class="fas fa-user profil-menu-icon"></i>
                            <span class="profil-menu-text">Profil Saya</span>
                        </a>
                        <a href="profil.php?menunggu_pembayaran=true" class="profil-menu-item <?= ($isActiveMP ? 'profil-menu-item-active' : ''); ?>">
                            <i class="fas fa-money-bill-wave-alt profil-menu-icon"></i>
                            <span class="profil-menu-text">Menunggu Pembayaran</span>
                        </a>
                        <a href="profil.php?daftar_pesanan=true" class="profil-menu-item <?= ($isActiveDP ? 'profil-menu-item-active' : ''); ?>">
                            <i class="fas fa-clipboard profil-menu-icon"></i>
                            <span class="profil-menu-text">Daftar Pesanan Saya</span>
                        </a>
                        <a href="#" class="btn-logout profil-menu-item">
                            <i class="fas fa-sign-out-alt profil-menu-icon"></i>
                            <span class="profil-menu-text">Keluar</span>
                        </a>
                    </div>
                </div>
                <div class='col-md-9'>
                    <?php if (!isset($_GET['edit_profil']) && !isset($_GET['ubah_password']) && !isset($_GET['menunggu_pembayaran']) && !isset($_GET['daftar_pesanan'])): ?>
                        <div class="mb-4">
                            <h3 class="fw-normal mb-1 text-black">Profil Saya</h3>
                            <p>Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        <img src="./images/foto_pelanggan/<?= $pelanggan_foto ?>" alt="avatar" class="img-fluid" style="width: 150px;">
                                        <h5 class="my-4"><?= $pelanggan_nama ?></h5>
                                        <div class="d-flex justify-content-center mb-2">
                                            <a href="profil.php?edit_profil" type="button" class="btn btn-outline-primary">Ubah profil</a> &nbsp;
                                            <a href="profil.php?ubah_password" type="button" class="btn btn-outline-primary">Ubah Password</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0"><strong>Username</strong></p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-black mb-0"><?= $pelanggan_username ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0"><strong>Nama</strong></p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-black mb-0"><?= $pelanggan_nama ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0"><strong>Nomer HP</strong></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="text-black mb-0"><?= $pelanggan_headphone ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0"><strong>Email</strong></p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-black mb-0"><?= $pelanggan_email ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <p class="mb-0"><strong>Alamat</strong></p>
                                            </div>
                                            <div class="col-sm-9">
                                                <p class="text-black mb-0"><?= $pelanggan_alamat ?></p>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php
                        if (isset($_GET['edit_profil'])) {
                            include('edit_profil.php');
                        }

                        if (isset($_GET['ubah_password'])) {
                            include('ubah_password.php');
                        }

                        if (isset($_GET['menunggu_pembayaran'])) {
                            if ($_GET['menunggu_pembayaran'] == 'detail_pesanan') {
                                include('detail_pesanan.php');
                            } else {
                                include('menunggu_pembayaran.php');
                            }
                        } elseif (isset($_GET['daftar_pesanan'])) {
                            if ($_GET['daftar_pesanan'] == 'detail_pesanan') {
                                include('detail_pesanan.php');
                            } else {
                                include('daftar_pesanan.php');
                            }
                        }
                        ?>
                        </div>
                </div>
        </section>
    </div>
    <!-- End profil Page -->
    <!-- End Content -->

    <!-- Start Footer -->
    <?php include 'includes/footer.php'; ?>
    <script src="admin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="admin/assets/js/kaiadmin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- End Footer -->

    <!-- SweetAlert Data ID Tidak Benar-->
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'id_invalid'): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // SweetAlert Update Data
                swal("Peringatan!", "ID tidak ditemukan!", {
                    icon: "warning",
                    buttons: {
                        confirm: {
                            className: "btn btn-primary",
                            text: "Oke"
                        },
                    },
                }).then(() => {
                    window.location.href = "profil.php"; // Refresh halaman
                });
            });
        </script>
    <?php unset($_SESSION['status']);
    endif; ?> <!-- Hapus session setelah digunakan -->
</body>

</html>