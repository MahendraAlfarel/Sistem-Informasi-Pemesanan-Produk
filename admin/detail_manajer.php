<?php
include '../functions/functions.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'manajer') {
    // Jika bukan level manajer
    header("Location: index.php");
    exit;
}

$id_manajer = $_GET['id'] ?? '';
$manajer = getManajerById($con, $id_manajer);

if (!$manajer) {
    $_SESSION['status'] = 'id_invalid';
    header("Location: index.php?halaman=daftarmanajer");
    exit;
}

$manajer_nama = htmlspecialchars($manajer['nama_manajer']);
$manajer_phone = htmlspecialchars($manajer['headphone_manajer']);
$manajer_email = htmlspecialchars($manajer['email_manajer']);
$manajer_alamat = htmlspecialchars($manajer['alamat_manajer']);
$manajer_username = htmlspecialchars($manajer['username_manajer']);
$manajer_status = htmlspecialchars($manajer['status_manajer']);
$manajer_foto = htmlspecialchars($manajer['foto_manajer']);
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Manajer</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="index.php">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <?php if(isset($_SESSION['level']) && $_SESSION['level'] === 'manajer') : ?>
                <li class="nav-item">
                    <a href="index.php?halaman=daftarmanajer">Daftar Manajer</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <?php endif?>
                <li class="nav-item">
                    <a href="#">Detail Manajer</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Detail Manajer</div>
                    </div>
                    <div class="card-body">
                     <div class="row">
                    <div class="col-md-4">
                    <img src="../images/foto_admin/<?= $manajer_foto ?>" width="350">      
                </div>
                <div class="col-md-8">
                    <div style="border-bottom: 1px solid #000; margin-bottom: 15px;">
                        <h3><b><?= $manajer_nama ?></h3>
                    </div>
                        <div class="form-group">
                            <label class="control-label">ID manajer</label>
                            <p class="form-control-static"><?= $id_manajer ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nama Lengkap</label>
                            <p class="form-control-static"><?= $manajer_nama ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nomer Headphone</label>
                            <p class="form-control-static"><?= $manajer_headphone ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <p class="form-control-static"><?= $manajer_email ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Alamat</label>
                            <p class="form-control-static"><?= $manajer_alamat?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Status</label>
                            <p class="form-control-static"><?= $manajer_status?></p>
                        </div>
                    </div>
                        <div class="card-action">
                            <a class="btn btn-danger ms-auto" href="index.php?halaman=daftarmanajer"><i class="fas fa-arrow-left"></i>
                            Kembali</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
