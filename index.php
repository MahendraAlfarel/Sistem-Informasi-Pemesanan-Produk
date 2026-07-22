<?php
session_start();
include 'koneksi.php';
include './functions/functions.php';

$kategori = getKategori();
$produk = getNewtProduk();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>PT Indo Kimia Abadi - Official Website</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="assets/img/favicon.png">
    <link rel="icon" type="image/x-icon" href="assets/img/icon.ico">

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

    <!-- Start Banner -->
    <div id="template-mo-zay-hero-carousel" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="0" class="active"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="1"></li>
            <li data-bs-target="#template-mo-zay-hero-carousel" data-bs-slide-to="2"></li>
        </ol>
        <div class="carousel-inner bg-light">
            <div class="carousel-item active">
                <img src="assets/img/banner-01.png" class="img-fluid" style="object-fit: cover; width: 100%; height: 700px;">
            </div>
            <div class="carousel-item" style="height: 700px;">
                <div class="container">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-start">
                            <img class="img-fluid" src="./assets/img/banner_img_02.png" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h1 class="h1"><b>Raigen Portable</b></h1>
                                <h3 class="h2">Butane Gas Cooking</h3>
                                <p>
                                    Gas kecil atau gas kompor portabel bisa juga untuk las torch. 
                                    Raigen gas mini yang <strong> isi lebih banyak dari kebanyakan gas</strong>  lainnya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="container" style="height: 700px;">
                    <div class="row p-5">
                        <div class="mx-auto col-md-8 col-lg-6 order-lg-last">
                            <img class="img-fluid" src="./assets/img/banner_img_03.png" alt="">
                        </div>
                        <div class="col-lg-6 mb-0 d-flex align-items-center">
                            <div class="text-align-left">
                                <h1 class="h1"><b>Proxima Penelube</b></h1>
                                <h3 class="h2">Stop Rust</h3>
                                <p>
                                    Melindungi logam dari karat dan korosi, Menembus bagian yang tersembunyi, 
                                    Melumasi hampir semua bagian, dan Menghilangkan lemak, kotoran dari permukaan metal
                                </p>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
        <a class="carousel-control-prev text-decoration-none w-auto ps-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
        </a>
        <a class="carousel-control-next text-decoration-none w-auto pe-3" href="#template-mo-zay-hero-carousel" role="button" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
    <!-- End Banner -->


    <!-- Start Categories -->
    <section class="container py-5">
        <div class="row text-center pt-3">
            <div class="col-lg-6 m-auto">
                <h1 class="h1">Kategori</h1>
            </div>
        </div>
        <div class="row btn-detail">
            <?php foreach($kategori as $kategori): ?>
            <div class="col-12 col-md-4 p-5 mt-3">
                <a><img src="images/foto_kategori/<?= htmlspecialchars($kategori['gambar_kategori']) ?>" class="rounded-circle img-fluid border"></a>
                <h5 class="text-center mt-3 mb-3"><?= htmlspecialchars($kategori['nama_kategori']) ?></h5>
                <p class="text-center"><a class="btn tombol-detail" href="shop.php?kategori=<?= $kategori['id_kategori'] ?>"><b>Lihat Detail </b><i class="fa fa-arrow-right"></i></a></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <!-- End Categories -->

    <!-- Start New Product -->
    <section class="bg-light">
        <div class="container py-5">
            <div class="row text-center py-3">
                <div class="col-lg-6 m-auto">
                    <h1 class="h1">Produk Terbaru</h1>
                </div>
            </div>
            <div class="row">
                <?php foreach($produk as $produk): ?>
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100">
                        <a href="detail_produk.php?id_produk=<?= $produk['id_produk'] ?>">
                            <img src="images/foto_produk/<?= htmlspecialchars($produk['foto_produk']) ?>" class="card-img-top" alt="<?= htmlspecialchars($produk['nama_produk'])?>">
                        </a>
                        <div class="card-body text-center">
                            <h3 class="text-decoration-none text-black mb-3 d-block"><?= htmlspecialchars($produk['nama_produk'])?></h3>
                        </div>
                        <p class="text-center"><a href="detail_produk.php?id_produk=<?= $produk['id_produk'] ?>" class="btn tombol-detail" type="button">Detail Produk </a></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- End New Product -->

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