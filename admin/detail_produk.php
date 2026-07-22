<?php
include '../functions/functions.php';
$produk_id = $_GET['id'] ?? '';
$produk = getProdukById($con, $produk_id);

if (!$produk) {
    $_SESSION['status'] = 'id_invalid';
    header("Location: index.php?halaman=daftarproduk");
    exit;
}

$produk_nama = htmlspecialchars($produk['nama_produk']);
$produk_harga = number_format($produk['harga_produk'], 0, ',', '.');
$produk_variasi = htmlspecialchars($produk['variasi_produk']);
$produk_berat = htmlspecialchars($produk['berat_produk']);
$produk_satuan = htmlspecialchars($produk['satuan_produk']);
$produk_kategori = htmlspecialchars($produk['nama_kategori']);
$produk_deskripsi = htmlspecialchars($produk['deskripsi_produk']);
$produk_status = htmlspecialchars($produk['status_produk']);
$produk_foto1 = htmlspecialchars($produk['foto_produk']);
$produk_foto2 = htmlspecialchars($produk['foto_produk_2']);
$produk_foto3 = htmlspecialchars($produk['foto_produk_3']);
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Produk</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="index.php">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="index.php?halaman=daftarproduk">Daftar Produk</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail Produk</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Detail Produk</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div id="carouselProduk" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="../images/foto_produk/<?= $produk_foto1 ?>" class="d-block w-100" alt="Foto Produk Utama">
                                        </div>
                                        <?php if (!empty($produk_foto2)): ?>
                                        <div class="carousel-item">
                                            <img src="../images/foto_produk/<?= $produk_foto2 ?>" class="d-block w-100" alt="Foto Produk 2">
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($produk_foto2)): ?>
                                        <div class="carousel-item">
                                            <img src="../images/foto_produk/<?= $produk_foto3 ?>" class="d-block w-100" alt="Foto Produk 3">
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php
                                    // Hitung jumlah foto yang tersedia
                                    $fotoCount = 1;
                                    if (!empty($produk_foto2)) $fotoCount++;
                                    if (!empty($produk_foto3)) $fotoCount++;
                                    ?>

                                    <?php if ($fotoCount > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduk" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Sebelumnya</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselProduk" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Berikutnya</span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div style="border-bottom: 1px solid #000; margin-bottom: 15px;">
                                    <h3><b><?= $produk_nama ?></h3>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">ID Produk</label>
                                    <p class="form-control-static"><?= $produk_id ?></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Nama Produk</label>
                                    <p class="form-control-static"><?= $produk_nama ?></p>
                                </div>
                                <div class="form-group">
                                    <label>Kategori</label>
                                    <p name="kategori" class="form-control-static"><?= $produk_nama ?></p>                        
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Harga Produk</label>
                                    <p class="form-control-static">Rp <?= $produk_harga ?></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Variasi Produk</label>
                                    <p class="form-control-static"><?= $produk_variasi ?></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Berat Produk</label>
                                    <p class="form-control-static"><?= $produk_berat ?> Gram</p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Satuan Produk</label>
                                    <p class="form-control-static"><?= $produk_satuan ?></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Deskripsi Produk</label>
                                        <p><?= nl2br(htmlspecialchars($produk_deskripsi)) ?></p>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Status Produk</label>
                                    <p class="form-control-static"><?= $produk_status ?></p>
                                </div>
                            </div>
                            <div class="card-action">
                                <a class="btn btn-danger ms-auto" href="index.php?halaman=daftarproduk"><i class="fas fa-arrow-left"></i>
                                Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>