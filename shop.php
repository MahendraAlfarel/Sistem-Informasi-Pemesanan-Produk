<?php
session_start();
include 'koneksi.php';
include './functions/functions.php';

if (!isset($_SESSION['rand_seed'])) {
    $_SESSION['rand_seed'] = rand(); // set hanya sekali saat awal sesi
}

$queryString = '';
if(isset($_GET['search'])) {
    $queryString .= '&search=' . urlencode($_GET['search']);
}
if(isset($_GET['sort'])) {
    $queryString .= '&sort=' . urlencode($_GET['sort']);
}
if(isset($_GET['kategori'])) {
    $queryString .= '&kategori=' . urlencode($_GET['kategori']);
}

$kategoriList = getKategori();
$kategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : null;
$page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$limit = 9;
$offset = ($page - 1) * $limit;
$produkList = getProduk($kategori, $limit, $offset);

$totalProduk = getTotalProduk($kategori); // fungsi menghitung total produk
$totalHalaman = ceil($totalProduk / $limit);
$prev = max(1, $page - 1);
$next = min($totalHalaman, $page + 1);

$queryString = '';
if ($kategori) $queryString .= "&kategori=$kategori";
if (isset($_GET['sort'])) $queryString .= "&sort=" . $_GET['sort'];

if (isset($_GET['aksi']) && $_GET['aksi'] === 'tambah' && isset($_GET['id'])) {
    $produk_id = $_GET['id'];

    if(!isset($_SESSION['username_pelanggan'])) {
        $_SESSION['notif'] = 'login-required';
        header("Location: shop.php");
        exit();
    }

    tambahKeranjang($produk_id);

    header("Location: shop.php?notif=1");
    exit();
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
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Start Header -->
    <?php include 'includes/header.php';?>
    <!-- End Header -->

    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">

            <div class="col-lg-3">
                <h1 class="h2 pb-4">Kategori</h1>
                <ul class="list-unstyled pl-3">
                    <li class="mb-2">
                        <a class="text-decoration-none text-black" href="shop.php">Semua</a>
                    </li>
                    <?php foreach($kategoriList as $listKategori): ?>
                    <li class="mb-2">
                        <a class="text-decoration-none text-black" href="shop.php?kategori=<?= $listKategori['id_kategori'] ?>">
                            <?= htmlspecialchars($listKategori['nama_kategori']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-inline shop-top-menu pb-3 pt-1">
                            <li class="list-inline-item">
                                <a class="h3 text-black text-decoration-none mr-3" href="#"></a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 pb-4">
                        <form method="GET">
                            <div class="d-flex">
                                <?php if (isset($_GET['kategori'])) : ?>
                                    <input type="hidden" name="kategori" value="<?= $_GET['kategori'] ?>">
                                <?php endif; ?>
                                <select class="form-control" name="sort" onchange="this.form.submit()">
                                    <option value="">Sortir dengan</option> <!-- Tidak usah diubah ini menjadi default-->
                                    <option value="terbaru" <?= isset($_GET['sort']) && $_GET['sort'] == 'terbaru' ? 'selected' : ''?>>Terbaru</option>
                                    <option value="nama" <?= isset($_GET['sort']) && $_GET['sort'] == 'nama' ? 'selected' : ''?>>A - Z</option>
                                    <option value="harga_terendah" <?= isset($_GET['sort']) && $_GET['sort'] == 'harga_terendah' ? 'selected' : ''?>>Harga Terendah</option>
                                    <option value="harga_tertinggi" <?= isset($_GET['sort']) && $_GET['sort'] == 'harga_tertinggi' ? 'selected' : ''?>>Harga Tertinggi</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <?php if ($produkList && $produkList->num_rows > 0): ?>
                    <?php while ($produk = $produkList->fetch_assoc()) :
                        $produk_id = $produk['id_produk'];
                        $produk_nama= htmlspecialchars($produk['nama_produk']);
                        $produk_harga = number_format($produk['harga_produk'], 0, ',', '.');
                        $produk_variasi = htmlspecialchars($produk['variasi_produk']);
                        $produk_kategori = htmlspecialchars($produk['nama_kategori']);
                        $produk_foto = htmlspecialchars($produk['foto_produk']);?>
                    <div class="col-md-4 p-2 pb-3 d-flex align-items-stretch">
                        <div class="card mb-4 product-wap rounded-0 w-100">
                            <div class="card rounded-0">
                                <img class="card-img rounded-0 img-fluid" src="./images/foto_produk/<?= $produk_foto ?>" alt="<?= $produk_nama ?>">
                                <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                    <ul class="list-unstyled">
                                        <li><a class="btn btn-primary text-white mt-2" name="btn-view" href="detail_produk.php?id_produk=<?= $produk_id ?>"><i class="far fa-eye"></i></a></li>
                                        <li><a class="btn btn-primary text-white mt-2" href="shop.php?aksi=tambah&id=<?= $produk_id ?>"><i class="fas fa-cart-plus"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <a href="detail_produk.php?id_produk=$produk_id" class="h3 text-decoration-none text-black"><b><?= $produk_nama ?></b></a>
                                </div>
                                    <div class="mt-auto">
                                    <p class="mb-0 text-primary mt-1"><?= $produk_kategori ?></p>
                                    <p class="mb-0 text-muted mt-1 d-flex justify-content-between">Variasi: <?= $produk_variasi ?></p>
                                    <p class="mb-1 text-black text-center mt-3">Rp <?= $produk_harga ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                        <?php else: ?>
                        <div class="col-12 text-center">
                            <p class="mt-5 h3 text-center text-black">Maaf, tidak ada produk untuk kategori ini.</p>
                        </div>
                        <?php endif; ?>
                </div>
                <?php if($produkList && $produkList->num_rows > 0 && $totalHalaman > 1): ?>
                <div div="row">
                    <ul class="pagination pagination-lg justify-content-end">
                        <!-- Tombol Previous -->
                        <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="?halaman=<?= $prev . $queryString ?>">&laquo;</a>
                        </li>

                        <!-- Tombol Angka Halaman -->
                        <?php for ($i = 1; $i <= $totalHalaman; $i++): 
                            $active = ($i == $page) ? 'active' : '';
                        ?>
                            <li class="page-item <?= $active ?>">
                                <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="?halaman=<?= $i . $queryString ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <!-- Tombol Next -->
                        <li class="page-item <?= ($page == $totalHalaman) ? 'disabled' : '' ?>">
                            <a class="page-link rounded-0 mr-3 shadow-sm border-top-0 border-left-0" href="?halaman=<?= $next . $queryString ?>">&raquo;</a>
                        </li>
                    </ul>
                </div>
                <?php endif ?>
            </div>

        </div>
    </div>
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

    <?php if(isset($_SESSION['notif'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        <?php if($_SESSION['notif'] === 'tambah-keranjang-success'): ?>
            Swal.fire({
            title: 'Berhasil',
            text: 'Produk berhasil dimasukkan ke keranjang',
            icon: 'success',
            confirmButtonText: 'Oke'
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = '#.php';
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
    <!-- End Script -->
</body>

</html>