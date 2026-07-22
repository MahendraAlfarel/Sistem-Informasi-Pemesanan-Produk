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

if (isset($_POST['ubah'])) {
    $nama_manajer = trim($_POST['nama']);
    $phone_manajer = trim($_POST['phone']);
    $email_manajer =  trim($_POST['email']);
    $alamat_manajer = trim($_POST['alamat']);
    $username_manajer = trim($_POST['username']);
    $password_manajer = $_POST['password'];
    $status = trim($_POST['status']);
    $foto_manajer = "";

    if (!empty($_FILES['foto_manajer']['name'])) {
        if (validasiFoto('foto_manajer')) {
            $ext = strtolower(pathinfo($_FILES['foto_manajer']['name'], PATHINFO_EXTENSION));
            $foto_manajer = uniqid('manajer_', true) . '.' . $ext;
            $lokasi = $_FILES['foto_manajer']['tmp_name'];
            move_uploaded_file($lokasi, "../images/foto_admin/" . $foto_manajer);
        } else {
            $_SESSION['status'] = 'update_failed';
            header("Location: index.php?halaman=daftarmanajer");
            exit;
        }   
    }

    if (empty($nama_manajer) || empty($phone_manajer) || empty($email_manajer) || empty($alamat_manajer) || empty($username_manajer)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    }
    
    $cek = $con->prepare("SELECT * FROM tb_manajer WHERE (nama_manajer = ? OR headphone_manajer = ? OR email_manajer = ? OR username_manajer = ?) AND id_manajer != ?");
    $cek->bind_param("ssssi", $nama_manajer, $phone_manajer, $email_manajer, $username_manajer, $id_manajer);
    $cek->execute();
    $cek->store_result();
    
    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarmanajer");
        exit();
    }

    if (!empty($foto_manajer)) {
        // Jika ada foto
        if (!empty($password_manajer)) {
            // Foto & Password diubah
            $final_password = password_hash($password_manajer, PASSWORD_DEFAULT);
            $query = $con->prepare("UPDATE tb_manajer 
                SET nama_manajer=?, headphone_manajer=?, email_manajer=?, alamat_manajer=?, 
                    foto_manajer=?, username_manajer=?, password_manajer=?
                WHERE id_manajer=?");
            $query->bind_param("sssssssi", $nama_manajer, $phone_manajer, $email_manajer, $alamat_manajer, $foto_manajer, $username_manajer, $final_password, $id_manajer);
        } else {
            // Foto saja yang diubah
            $query = $con->prepare("UPDATE tb_manajer 
                SET nama_manajer=?, headphone_manajer=?, email_manajer=?, alamat_manajer=?, 
                    foto_manajer=?, username_manajer=?
                WHERE id_manajer=?");
            $query->bind_param("ssssssi", $nama_manajer, $phone_manajer, $email_manajer, $alamat_manajer, $foto_manajer, $username_manajer, $id_manajer);
        }
    } else {
        if (!empty($password_manajer)) {
            // Password saja yang diubah
            $final_password = password_hash($password_manajer, PASSWORD_DEFAULT);
            $query = $con->prepare("UPDATE tb_manajer 
                SET nama_manajer=?, headphone_manajer=?, email_manajer=?, alamat_manajer=?, 
                    username_manajer=?, password_manajer=?
                WHERE id_manajer=?");
            $query->bind_param("ssssssi", $nama_manajer, $phone_manajer, $email_manajer, $alamat_manajer, $username_manajer, $final_password, $id_manajer);
        } else {
            // Tidak ada foto & tidak ada password yang diubah
            $query = $con->prepare("UPDATE tb_manajer 
                SET nama_manajer=?, headphone_manajer=?, email_manajer=?, alamat_manajer=?, 
                    username_manajer=?
                WHERE id_manajer=?");
            $query->bind_param("sssssi", $nama_manajer, $phone_manajer, $email_manajer, $alamat_manajer, $username_manajer, $id_manajer);
        }
    }

    // Eksekusi
    if ($query->execute()) {
        $_SESSION['status'] = "update_success";
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    } else {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    }
    $query->close();
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Form Edit Manajer</h3>
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
                    <a href="#">Form Edit Manajer</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Manajer</div>
                    </div>
                    <div class="card-body">
                     <div class="row">
                <div class="col-md-4">
                    <img src="../images/<?= $manajer_foto ?>" width="350">      
                </div>
                <div class="col-md-8">
                    <div style="border-bottom: 1px solid #000; margin-bottom: 15px;">
                        <h3><b>Data <?= $manajer_nama?></b></h3>
                    </div>
                    <form action="" id="formedit" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?= $manajer_nama?>">
                        </div>
                        <div class="form-group">
                            <label>No Headphone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?= $manajer_phone ?>">
                        </div>
                            <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= $manajer_email ?>">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $manajer_alamat ?>">
                        </div>
                        <div class="form-group mb-3">
                            <span class="label">Foto</span>
                            <input class="form-control" type="file" name="foto_manajer"/>
                        </div>
                        <div class="form-group">
                            <label>Status Manajer</label>
                            <select name="status" class="form-control">
                                    <option value="Aktif" <?= ($manajer_status == 'Aktif') ? 'selected' : ''; ?>>Aktif
                                    </option>
                                    <option value="Tidak aktif" <?= ($manajer_status == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?= $manajer_username?>">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" name="password" id="password" class="form-control" placeholder="Password Manajer">
                        </div>
                    </form>
                </div>
                <div class="card-action">
                    <button type="submit" name="ubah" class="btn btn-success" form="formedit">Simpan</button>
                    <a class="btn btn-danger" href="index.php?halaman=daftarmanajer">Kembali</a>
                </div>
            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showNotif(message, type = "danger", icon = "fa fa-times") {
        $.notify(
            {
                icon: icon,
                title: "Terjadi Kesalahan",
                message: message
            },
            {
                type: type,
                placement: {
                    from: "top",
                    align: "right",
                },
                time: 1000,
                delay: 3000,
            }
        );
    }

    document.querySelector("form").addEventListener("submit", function (e) {
        let nama = document.getElementById("nama").value.trim();
        let phone = document.getElementById("phone").value.trim();
        let email = document.getElementById("email").value.trim();
        let alamat = document.getElementById("alamat").value.trim();
        let username = document.getElementById("username");
        let password = document.getElementById("password");

        if (nama === ""){
            e.preventDefault();
            showNotif("Nama tidak boleh kosong");
            return false;
        }

        if (phone === ""){
            e.preventDefault();
            showNotif("Nomer Headphone tidak boleh kosong");
            return false;
        }
        
        if (!/^\d+$/.test(phone)){
            e.preventDefault();
            showNotif("Nomer Headphone hanya boleh angka");
            return false;
        }

        if (email === ""){
            e.preventDefault();
            showNotif("Email tidak boleh kosong");
            return false;
        }

        if (!email.includes("@")){
            e.preventDefault();
            showNotif("Email harus mengandung karakter '@'");
            return false;
        }

        if (alamat === ""){
            e.preventDefault();
            showNotif("Alamat tidak boleh kosong");
            return false;
        }

        if (username && username.value.trim() === ""){
            e.preventDefault();
            showNotif("Username tidak boleh kosong");
            return false;
        }
    });
</script>
