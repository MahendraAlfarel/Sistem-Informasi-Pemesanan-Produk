<?php
include '../functions/functions.php';
$id_pelanggan = $_GET['id'] ?? '';
$pelanggan = getPelangganById($con, $id_pelanggan);

if (!$pelanggan) {
    $_SESSION['status'] = 'id_invalid';
    header("Location: index.php?halaman=daftarpelanggan");
    exit;
}

$pelanggan_nama = htmlspecialchars($pelanggan['nama_pelanggan']);
$pelanggan_headphone = htmlspecialchars($pelanggan['headphone_pelanggan']);
$pelanggan_email = htmlspecialchars($pelanggan['email_pelanggan']);
$pelanggan_username = htmlspecialchars($pelanggan['username_pelanggan']);
$pelanggan_foto = htmlspecialchars($pelanggan['foto_pelanggan']);
$pelanggan_alamat = htmlspecialchars($pelanggan['alamat_pelanggan']);
$pelanggan_provinsi =  htmlspecialchars($pelanggan['nama_provinsi']);
$pelanggan_kota =  htmlspecialchars($pelanggan['nama_kota']);
$pelanggan_kecamatan =  htmlspecialchars($pelanggan['nama_kecamatan']);
$pelanggan_kelurahan =  htmlspecialchars($pelanggan['nama_kelurahan']);
$pelanggan_kdps =  htmlspecialchars($pelanggan['kode_pos']);
$alamat_pelanggan = "$pelanggan_alamat, $pelanggan_kelurahan, $pelanggan_kecamatan,  $pelanggan_kota, $pelanggan_provinsi, $pelanggan_kdps";
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail pelanggan</h3>
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
                    <a href="index.php?halaman=daftarpelanggan">Daftar pelanggan</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail pelanggan</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Detail pelanggan</div>
                    </div>
                    <div class="card-body">
                     <div class="row">
                    <div class="col-md-4">
                    <img src="../images/foto_pelanggan/<?= $pelanggan_foto ?>" width="350">      
                </div>
                <div class="col-md-8">
                    <div style="border-bottom: 1px solid #000; margin-bottom: 15px;">
                        <h3><b><?= $pelanggan_nama ?></h3>
                    </div>
                    <form action="" id="formedit" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                        <label class="control-label">ID pelanggan</label>
                        <p class="form-control-static"><?= $id_pelanggan?></p>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Username Pelanggan</label>
                        <p class="form-control-static"><?= $pelanggan_username ?></p>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Nomer Headphone pelanggan</label>
                        <p class="form-control-static"><?= $pelanggan_headphone?></p>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Email pelanggan</label>
                        <p class="form-control-static"><?= $pelanggan_email?></p>
                        </div>
                        <div class="form-group">
                        <label class="control-label">Alamat pelanggan</label>
                        <p class="form-control-static"><?= $alamat_pelanggan ?></p>
                        </div>
                    </form>
                    </div>
                        <div class="card-action">
                            <a class="btn btn-danger ms-auto" href="index.php?halaman=daftarpelanggan"><i class="fas fa-arrow-left"></i>
                            Kembali</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
