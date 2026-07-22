<?php
include '../functions/functions.php';
if ($_SESSION['level'] === 'admin') {
    if (!isset($_GET['id']) || $_GET['id'] != $_SESSION['id_admin']) {
        header("Location: index.php");
        exit;
    }
}

$id_admin = $_GET['id'] ?? '';
$admin = getAdminById($con, $id_admin);

if (!$admin) {
    $_SESSION['status'] = 'id_invalid';
    header("Location: index.php?halaman=daftaradmin");
    exit;
}

$admin_nama = htmlspecialchars($admin['nama_admin']);
$admin_headphone = htmlspecialchars($admin['headphone_admin']);
$admin_email = htmlspecialchars($admin['email_admin']);
$admin_alamat = htmlspecialchars($admin['alamat_admin']);
$admin_username = htmlspecialchars($admin['username_admin']);
$admin_foto = htmlspecialchars($admin['foto_admin']);
$admin_status = htmlspecialchars($admin['status_admin']);
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Admin</h3>
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
                    <a href="index.php?halaman=daftaradmin">Daftar Admin</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <?php endif?>
                <li class="nav-item">
                    <a href="#">Detail Admin</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Detail Admin</div>
                    </div>
                    <div class="card-body">
                     <div class="row">
                    <div class="col-md-4">
                    <img src="../images/foto_admin/<?= $admin_foto ?>" width="350">      
                </div>
                <div class="col-md-8">
                    <div style="border-bottom: 1px solid #000; margin-bottom: 15px;">
                        <h3><b><?= $admin_nama?></h3>
                    </div>
                        <div class="form-group">
                            <label class="control-label">ID Admin</label>
                            <p class="form-control-static"><?= $id_admin?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nama Lengkap</label>
                            <p class="form-control-static"><?= $admin_nama ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Nomer Headphone Admin</label>
                            <p class="form-control-static"><?= $admin_headphone ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email Admin</label>
                            <p class="form-control-static"><?= $admin_email ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Alamat Admin</label>
                            <p class="form-control-static"><?= $admin_alamat ?></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Status Admin</label>
                            <p class="form-control-static"><?= $admin_status ?></p>
                        </div>
                    </div>
                        <div class="card-action">
                            <a class="btn btn-danger" href="<?php echo (isset($_SESSION['level']) && $_SESSION['level'] === 'manajer') ? 'index.php?halaman=daftaradmin' : 'index.php'; ?>">Kembali</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
