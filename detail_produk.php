<?php
session_start();
include 'koneksi.php';
include './functions/functions.php';

$produk_id = $_GET['id_produk'] ?? '';
$produk = getProdukById($con, $produk_id);
$produk_related = getRelatedProduct($produk_id);

if (!$produk) {
    echo "<script>
        alert('Akses Ditolak.');
        window.location.href = 'index.php';
    </script>";
    exit();
}

$produk_nama = htmlspecialchars($produk['nama_produk']);
$produk_harga = number_format($produk['harga_produk'], 0, ',', '.');
$produk_variasi = htmlspecialchars($produk['variasi_produk']);
$produk_berat = htmlspecialchars($produk['berat_produk']);
$produk_satuan = htmlspecialchars($produk['satuan_produk']);
$produk_kategori = htmlspecialchars($produk['nama_kategori']);
$produk_deskripsi = htmlspecialchars($produk['deskripsi_produk']);
$produk_status = htmlspecialchars($produk['status_produk']);
$produk_foto = htmlspecialchars($produk['foto_produk']);
$produk_foto_dua = htmlspecialchars($produk['foto_produk_2']);
$produk_foto_tiga = htmlspecialchars($produk['foto_produk_3']);

if(isset($_POST['addcart']) && isset($_POST['id_produk']) && isset($_POST['produk_qty'])){
    $jumlah = $_POST['produk_qty'];
    $produk_id = isset($_POST['id_produk']) ? $_POST['id_produk'] : null;

    if(!isset($_SESSION['username_pelanggan'])) {
        $_SESSION['notif'] = 'login-required';
        header("Location: detail_produk.php?id_produk=$produk_id");
        exit();
    }

    if ($jumlah < 1){
        $_SESSION['notif'] = 'tambah-keranjang-gagal';
        header("Location: detail_produk.php?id_produk=$produk_id");
        exit();
    }

    if(isset($_SESSION['keranjang'][$produk_id])){
        $_SESSION['keranjang'][$produk_id] += $jumlah;
    }else{
        $_SESSION['keranjang'][$produk_id] = $jumlah;
    }
    $_SESSION['notif'] = 'tambah-keranjang-berhasil';
    header("Location: detail_produk.php?id_produk=$produk_id");
    exit();
}

if(isset($_POST['buynow'])){
    $jumlah = $_POST['produk_qty'];
    $produk_id = $_POST['id_produk'];

    if(!isset($_SESSION['username_pelanggan'])) {
        $_SESSION['notif'] = 'login-required';
        header("Location: detail_produk.php?id_produk=$produk_id");
        exit();
    }

    if ($jumlah < 1){
        $_SESSION['notif'] = 'tambah-keranjang-gagal';
        header("Location: detail_produk.php?id_produk=$produk_id");
        exit();
    }

    $_SESSION['keranjang'] = [];
    $_SESSION['keranjang'][$produk_id] = $jumlah;
    echo "<script>location = 'checkout.php';</script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shop - PT Indo Kimia Abadi</title>
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
    
    <!-- Slick -->
    <link rel="stylesheet" type="text/css" href="assets/css/slick.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/slick-theme.css">
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Start Header -->
    <?php include 'includes/header.php';?>
    <!-- End Header -->

    <!-- Open Content -->
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img class="card-img img-fluid" src="./images/foto_produk/<?= $produk_foto ?>" alt="Card image cap" id="product-detail">
                    </div>
                    <div class="row">
                        <!--Start Controls-->
                        <div class="col-1 align-self-center">
                            <a href="#multi-item-example" role="button" data-bs-slide="prev">
                                <i class="text-dark fas fa-chevron-left"></i>        
                                <span class="sr-only">Previous</span>
                            </a>
                        </div>
                        <!--End Controls-->

                        <!--Start Carousel Wrapper-->
                        <div id="multi-item-example" class="col-10 carousel slide carousel-multi-item" data-bs-ride="carousel">
                            <!--Start Slides-->
                            <div class="carousel-inner product-links-wap" role="listbox">

                                <!--First slide-->
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="#">
                                                <img class="card-img img-fluid" src="./images/foto_produk/<?= $produk_foto ?>" alt="Product Image 1">
                                            </a>
                                        </div>
                                        
                                        <?php if (!empty($produk_foto_dua)):?>
                                        <div class="col-4">
                                            <a href="#">
                                                <img class="card-img img-fluid" src="./images/foto_produk/<?= $produk_foto_dua ?>" alt="Product Image 2">
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($produk_foto_tiga)): ?>
                                        <div class="col-4">
                                            <a href="#">
                                                <img class="card-img img-fluid" src="./images/foto_produk/<?= $produk_foto_tiga ?>" alt="Product Image 2">
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!--/.First slide-->
                            </div>
                            <!--End Slides-->
                        </div>
                        <!--End Carousel Wrapper-->
                        
                        <!--Start Controls-->
                        <div class="col-1 align-self-center">
                            <a href="#multi-item-example" role="button" data-bs-slide="next">
                                <i class="text-dark fas fa-chevron-right"></i>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                        <!--End Controls-->
                    </div>
                </div>
                <!-- col end -->

                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="h2 text-black"><b><?= $produk_nama ?></b></h1>
                            <p class="h3 py-2">Rp. <?= $produk_harga ?></p>
                            
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Kategori:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-primary"><strong><?= $produk_kategori ?></strong></p>
                                </li>
                            </ul>
                            
                            <h6>Description:</h6>
                            <p class="h5"><?= nl2br(htmlspecialchars($produk_deskripsi)) ?></p>
                            
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Berat Produk:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-black"><?= $produk_berat ?> Gram</p>
                                </li>
                            </ul>
                                            
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Satuan Produk:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-black"><?= $produk_satuan ?></p>
                                </li>
                            </ul>

                            <div class="row">
                                <div class="col-auto">
                                    <ul class="list-inline pb-3">
                                        <li class="list-inline-item">
                                            <strong>Variasi :</strong>
                                        </li>
                                        <li class="list-inline-item">
                                            <span class="btn btn-primary btn-size"><?= $produk_variasi ?></span>
                                        </li>
                                    </ul>
                                </div>
                                                    
                            <form id="checkout" action="" method="POST">
                                <input type="hidden" name="id_produk" value="<?= $produk_id ?>">
                                <div class="col-auto">
                                <ul class="list-inline pb-3">
                                    <li class="list-inline-item text-right">
                                        <strong>Quantity</strong>
                                    </li>
                                    <li class="list-inline-item">
                                        <button type="button" class="btn btn-primary" id="btn-minus">-</button>
                                    </li>
                                    <li class="list-inline-item">
                                        <input type="number" name="produk_qty" id="product-quantity" value="1" min="1" class="form-control" style="width: 70px;">
                                    </li>
                                    <li class="list-inline-item">
                                        <button type="button" class="btn btn-primary" id="btn-plus">+</button>
                                    </li>
                                </ul>
                                </div>
                            </div>
                                        
                            <div class="row pb-3">
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg" name="buynow">Beli Sekarang</button>
                                </div>
                                <div class="col d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg" name="addcart">Tambah Ke Keranjang</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close Content -->
    
    <!-- Start Article -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center p-2 pb-3">
                <h4>Related Products</h4>
            </div>

            <!--Start Carousel Wrapper-->
            <div class="row" id="carousel-related-product">
                <?php
                foreach ($produk_related as $produkList):
                $produk_id_related = $produkList['id_produk'];
                $produk_nama_related = htmlspecialchars($produkList['nama_produk']);
                $produk_harga_related = number_format($produkList['harga_produk'], 0, ',', '.');
                $produk_variasi_related = htmlspecialchars($produkList['variasi_produk']);
                $produk_kategori_related = htmlspecialchars($produkList['nama_kategori']);
                $produk_foto_related = htmlspecialchars($produkList['foto_produk']);
                    ?>
                <div class="p-2 pb-3">
                    <div class="product-wap card rounded-0 h-100">
                        <div class="card rounded-0">
                            <img class="card-img rounded-0 img-fluid" src="./images/foto_produk/<?= $produk_foto_related ?>" alt="$produk_nama">
                            <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                <ul class="list-unstyled">
                                    <li><a class="btn btn-primary text-white mt-2" href="detail_produk.php?id_produk=<?= $produk_id_related ?>"><i class="far fa-eye"></i></a></li>
                                    <li><a class="btn btn-primary text-white mt-2" href="shop.php?aksi=tambah&id=<?= $produk_id_related ?>"><i class="fas fa-cart-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <a href="detail_produk.php?id_produk=<?= $produk_id_related ?>" class="h3 text-decoration-none text-black"><b><?= $produk_nama_related ?></b></a>
                            </div>
                            <div class="mt-auto">
                                <p class="mb-0 text-primary mt-1"><?= $produk_kategori_related ?></p>
                                <p class="mb-0 text-muted mt-1 d-flex justify-content-between">Variasi: <?= $produk_variasi_related ?></p>
                                <p class="mb-1 text-black text-center mt-2 mb-0">Rp <?= $produk_harga_related ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- End Article -->


    <!-- Start Footer -->
    <?php include 'includes/footer.php';?>
    <!-- End Footer -->

    <!-- Start Loaded Spinner -->
    <div id="spinner-overlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!-- End Loaded Spinner --> 

    <!-- Start Slider Script -->
    <script src="assets/js/slick.min.js"></script>
    <script>
        $('#carousel-related-product').slick({
            infinite: true,
            arrows: false,
            slidesToShow: 4,
            slidesToScroll: 3,
            dots: true,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 3
                    }
                }
            ]
        });
    </script>
    <!-- End Slider Script -->

    <!-- Start Spinner Load Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("checkout");
            const buyNowBtn = document.querySelector("button[name='buynow']");
            const spinner = document.getElementById("spinner-overlay");

            if (buyNowBtn && form) {
                buyNowBtn.addEventListener("click", function() {
                    spinner.classList.add('show');

                    setTimeout(() => {
                        //form.submit(); // bisa dipanggil
                        spinner.classList.add('hide');
                    }, 300);
                });
            }
        });
    </script>
    <!-- End Spinner Load Script -->
     
    <!-- Start Sweetalert Script -->
    <?php if(isset($_SESSION['notif'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        <?php if($_SESSION['notif'] === 'tambah-keranjang-berhasil'): ?>
            Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Produk berhasil dimasukkan ke keranjang',
            confirmButtonText: 'Oke',
            allowOutsideClick: true
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = '#';
            }
        });
        <?php elseif ($_SESSION['notif'] === 'tambah-keranjang-gagal'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Jumlah produk tidak valid',
                confirmButtonText: 'Oke',
                allowOutsideClick: true
            }).then((result) => {
                if(result.isConfirmed){
                    window.location.href = '#';
                }
            });
        <?php elseif ($_SESSION['notif'] === 'login-required'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Mohon untuk login terlebih dahulu',
                confirmButtonText: 'Oke',
                allowOutsideClick: true
            }).then((result) => {
                if(result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
            <?php endif; ?>
        <?php unset($_SESSION['notif']); ?>
        });
    </script>
    <?php endif; ?>
    <!-- End Sweetalert Script -->

</body>

</html>

