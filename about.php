<?php
session_start();
include 'koneksi.php';
include './functions/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>About - PT Indo Kimia Abadi</title>
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
    <?php include 'includes/header.php';?>
    <!-- End Header -->
     
    <!-- Start Content-->
        <!-- Start Banner-->
        <section class="bg-light py-5">
            <div class="container">
                <div class="row align-items-center py-5">
                    <div class="col-md-4">
                        <img src="assets/img/logo.svg" alt="Logo Indo Kimia Abadi">
                    </div>
                    <div class="col-md-8 text-dark">
                        <h1>Profil Perusahaan</h1>
                        <p>
                            PT Indo Kimia Abadi merupakan Perusahaan manafaktur yang bergerak dibidang industri kimia, 
                            yang memproduksi dan menjual barang-barang grosir kimia umum. 
                            PT Indo Kimia Abadi di dirikan oleh Bapak Aditya Tan pada tahun 2013, 
                            beralamat di Jalan Millennium 11B Blok F6 no 5, Peusar, Kec. Panongan, Kabupaten Tangerang, Banten (15710) Indonesia. 
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Close Banner -->

        <!-- Start Section -->
        <section class="container py-5">
            <div class="row text-center pt-5 pb-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Layanan Kami</h1>
                    <p>
                        Selamat Datang Di PT Indo Kimia Abadi
                    </p>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6 col-lg-3 pb-5">
                    <div class="h-100 py-5 services-icon-wap shadow">
                        <div class="h1 text-primary text-center"><i class="fa fa-truck fa-lg"></i></div>
                        <h2 class="h5 mt-4 text-center">Pengiriman Cepat & Bebas Biaya Pengiriman</h2>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 pb-5">
                    <div class="h-100 py-5 services-icon-wap shadow">
                        <div class="h1 text-primary text-center"><i class="fas fa-exchange-alt"></i></div>
                        <h2 class="h5 mt-4 text-center">Gratis Pengembalian Produk</h2>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 pb-5">
                    <div class="h-100 py-5 services-icon-wap shadow">
                        <div class="h1 text-primary text-center"><i class="fa fa-shopping-bag"></i></div>
                        <h2 class="h5 mt-4 text-center">Berbelanja Dengan Mudah & Praktis</h2>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 pb-5">
                    <div class="h-100 py-5 services-icon-wap shadow">
                        <div class="h1 text-primary text-center"><i class="fa fa-money-bill-wave-alt"></i></div>
                        <h2 class="h5 mt-4 text-center">Harga Dijamin Murah</h2>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Section -->
    <!-- End Content -->

    <!-- Start Footer -->
    <?php include 'includes/footer.php';?>
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->
</body>

</html>