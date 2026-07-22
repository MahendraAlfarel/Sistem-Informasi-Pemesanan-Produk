<?php
include '../functions/functions.php';
if ($_SESSION['level'] === 'admin') {
    if (!isset($_GET['id']) || $_GET['id'] != $_SESSION['id_admin']) {
        header("Location: index.php");
        exit;
    }
}

if ($_SESSION['level'] === 'manajer'){
    $id_manajer = $_SESSION['id_manajer'];
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

if (isset($_POST['ubah'])) {
    $nama_admin = trim($_POST['nama']);
    $phone_admin = trim($_POST['phone']);
    $email_admin = trim($_POST['email']);
    $alamat_admin = trim($_POST['alamat']);
    $username_admin = trim($_POST['username']);
    $status_admin = trim($_POST['status']);
    $password_admin = $_POST['password'];
    $foto_admin = "";

    if (!empty($_FILES['foto_admin']['name'])) {
        if (validasiFoto('foto_admin')) {
            $ext = strtolower(pathinfo($_FILES['foto_admin']['name'], PATHINFO_EXTENSION));
            $foto_admin = uniqid('admin_', true) . '.' . $ext;
            $lokasi = $_FILES['foto_admin']['tmp_name'];
            move_uploaded_file($lokasi, "../images/foto_admin/" . $foto_admin);
        } else {
            $_SESSION['status'] = 'update_failed';
            header("Location: index.php?halaman=daftaradmin");
            exit;
        }   
    }

    if (empty($nama_admin) || empty($phone_admin) || empty($email_admin) || empty($alamat_admin) || empty($username_admin)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftaradmin");
        exit;
    }
    
    $cek = $con->prepare("SELECT * FROM tb_admin WHERE (nama_admin = ? OR headphone_admin = ? OR email_admin = ? OR username_admin = ?) AND id_admin != ?");
    $cek->bind_param("ssssi", $nama_admin, $phone_admin, $email_admin, $username_admin, $id_admin);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftaradmin");
        exit();
    }

    $cek->close();

    if (!empty($foto_admin)) {
        // Jika ada foto
        if (!empty($password_admin)) {
            // Foto & Password diubah
            $final_password = password_hash($password_admin, PASSWORD_DEFAULT);
            if ($_SESSION['level'] === 'manajer') {
                $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, foto_admin=?, username_admin=?, password_admin=?, id_manajer = ? WHERE id_admin=?");
                $query->bind_param("sssssssii", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $foto_admin, $username_admin, $final_password, $id_manajer, $id_admin);
            } else {
                $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, foto_admin=?, username_admin=?, password_admin=?, id_manajer = ? WHERE id_admin=?");
                $query->bind_param("sssssssi", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $foto_admin, $username_admin, $final_password, $id_admin);
            }
        } else {
            if ($_SESSION['level'] === 'manajer') {
            // Foto saja yang diubah
                $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, foto_admin=?, username_admin=?, id_manajer=? WHERE id_admin=?");
                $query->bind_param("ssssssii", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $foto_admin, $username_admin, $id_manajer, $id_admin);
            } else {
                $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, foto_admin=?, username_admin=? WHERE id_admin=?");
                $query->bind_param("ssssssi", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $foto_admin, $username_admin, $id_admin);
            }
        }
    } else {
        if (!empty($password_admin)) {
            // Password saja yang diubah
            $final_password = password_hash($password_admin, PASSWORD_DEFAULT);
            if ($_SESSION['level'] === 'manajer') {
                $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, username_admin=?, password_admin=?, id_manajer=? WHERE id_admin=?");
                $query->bind_param("ssssssii", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $username_admin, $final_password, $id_manajer, $id_admin);
            } else {
                $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, username_admin=?, password_admin=? WHERE id_admin=?");
                $query->bind_param("ssssssi", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $username_admin, $final_password, $id_admin);    
            }
        } else {
            if ($_SESSION['level'] === 'manajer') {
            // Tidak ada foto & tidak ada password yang diubah
            $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, username_admin=?, id_manajer=? WHERE id_admin=?");
            $query->bind_param("sssssii", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $username_admin, $id_manajer, $id_admin);
        } else {
            $query = $con->prepare("UPDATE tb_admin SET nama_admin=?, headphone_admin=?, email_admin=?, alamat_admin=?, username_admin=? WHERE id_admin=?");
            $query->bind_param("sssssi", $nama_admin, $phone_admin, $email_admin, $alamat_admin, $username_admin, $id_admin);
        }
    }
}

    // Eksekusi
    if ($query->execute()) {
        $_SESSION['status'] = "update_success";
        if ($_SESSION['level'] === 'manajer') {
            header("Location: index.php?halaman=daftaradmin");
        } else {
            $_SESSION['update_admin'] = true;
            $admin_id = $_SESSION['id_admin'];
            header("Location: index.php?halaman=editadmin&id=" . $admin_id ."#formedit");
        }
        exit;
    } else {
        $_SESSION['status'] = "update_failed";
        if ($_SESSION['level'] === 'manajer') {
            header("Location: index.php?halaman=daftaradmin");
        } else {
            $_SESSION['update_admin'] = true;
            $admin_id = $_SESSION['id_admin'];
            header("Location: index.php?halaman=editadmin&id=". $admin_id . "#formedit");
        }
        exit;
    }
    $query->close();
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Form Edit Admin</h3>
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
                    <a href="index.php?halaman=daftaradmin">Daftar admin</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <?php endif?>
                <li class="nav-item">
                    <a href="#">Data Admin</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Admin</div>
                    </div>
                    <div class="card-body">
                     <div class="row">
                <div class="col-md-4">
                    <img src="../images/foto_admin/<?= $admin_foto ?>" width="350">      
                </div>
                <div class="col-md-8">
                    <div style="border-bottom: 1px solid #000; margin-bottom: 15px;">
                        <h3><b><?= $admin_nama ?></b></h3>
                    </div>
                    <form action="" id="formedit" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Nama Admin</label>
                            <input type="text" name="nama" id="nama" class="form-control" value="<?= $admin_nama ?>">
                        </div>
                        <div class="form-group">
                            <label>No Headphone</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?= $admin_headphone ?>">
                        </div>
                            <div class="form-group">
                            <label>Email Admin</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= $admin_email?>">
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $admin_alamat ?>">
                        </div>
                        <div class="form-group mb-3">
                            <span class="label">Foto Admin</span>
                            <input class="form-control" type="file" name="foto_admin" />
                        </div>
                        <?php if(isset($_SESSION['level']) && $_SESSION['level'] === 'admin') : ?>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="status" id="status" class="form-control" value="<?= $admin_status ?>"readonly>
                        </div>
                        <?php endif?>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" id="username" class="form-control" value="<?= $admin_username?>" <?php echo (isset($_SESSION['level']) && $_SESSION['level'] === 'manajer') ? '' : 'readonly' ?>>
                        </div>
                        <?php if(isset($_SESSION['level']) && $_SESSION['level'] === 'manajer') : ?>
                        <div class="form-group">
                            <label>Status Admin</label>
                            <select name="status" class="form-control">
                                    <option value="Aktif" <?= ($admin_status == 'Aktif') ? 'selected' : ''; ?>>Aktif
                                    </option>
                                    <option value="Tidak Aktif" <?= ($admin_status == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif
                                    </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control" placeholder="Password Admin">
                        </div>
                        <?php endif?>
                    </form>
                </div>
                <div class="card-action">
                    <button type="submit" name="ubah" class="btn btn-success" form="formedit">Simpan</button>
                    <a class="btn btn-danger" href="<?php echo (isset($_SESSION['level']) && $_SESSION['level'] === 'manajer') ? 'index.php?halaman=daftaradmin' : 'index.php'; ?>">Kembali</a>
                </div>
            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan gambar sebelum upload
    function openFile(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var imgElement = document.getElementById("displayImage");
            imgElement.src = reader.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

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

<?php if(isset($_SESSION['status']) && isset($_SESSION['update_admmin']) && $_SESSION['update_admin'] === true && $_SESSION['level'] === 'admin'):?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($_SESSION['status'] === "update_success"): ?>
            swal("Berhasil", "Data berhasil diperbarui!", {
                icon = "success",
                buttons: {
                    confirm: {
                        className: "btn btn-success",
                        text: "Oke"
                    },
                },
            });
        <?php else: ?>
            swal("Gagal!", "Data gagal diperbarui!", {
                icon: "error",
                buttons: {
                    confirm: {
                        className: "btn btn-danger",
                        text: "Oke"
                    },
                },                
            });
        <?php endif; ?>
    });    
</script>

<?php unset($_SESSION['status']); unset($_SESSION['update_admin']); endif; ?>