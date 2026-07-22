<?php
include '../functions/functions.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'manajer') {
    // Jika bukan level manajer
    header("Location: index.php");
    exit;
}
$id_manajer = $_SESSION['id_manajer'];

if (isset($_POST['tambah'])) {
    $nama_admin = trim($_POST['nama']);
    $phone_admin = trim($_POST['phone']);
    $email_admin = trim($_POST['email']);
    $alamat_admin = trim($_POST['alamat']);
    $username_admin = trim($_POST['username']);
    $password_admin = $_POST['password'];
    $final_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status_admin = 'Aktif';


    if (validasiFoto('foto_admin')) {
        $ext = strtolower(pathinfo($_FILES['foto_admin']['name'], PATHINFO_EXTENSION));
        $foto_admin = uniqid('admin_', true) . '.' . $ext;
        $lokasi = $_FILES['foto_admin']['tmp_name'];

        move_uploaded_file($lokasi, "../images/foto_admin/" . $foto_admin);
    } else {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftaradmin");
        exit;
    }

    if (empty($nama_admin) || empty($phone_admin) || empty($email_admin) || empty($alamat_admin) || empty($username_admin) || empty($password_admin)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftaradmin");
        exit;
    } elseif (strlen($password_admin) < 8) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftaradmin");
        exit;
    } elseif (!filter_var($email_admin, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftaradmin");
        exit;
    } elseif (preg_match('/\s/', $username_admin)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftaradmin");
        exit;
    }

    $cek = $con->prepare("SELECT * FROM tb_admin WHERE nama_admin = ? OR headphone_admin = ? OR email_admin = ? OR username_admin = ?");
    $cek->bind_param("ssss", $nama_admin, $phone_admin, $email_admin, $username_admin);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftaradmin");
        exit();
    }

    $query = $con->prepare("INSERT INTO tb_admin 
             (nama_admin, headphone_admin, email_admin, alamat_admin, foto_admin, username_admin, password_admin, status_admin, id_manajer) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("ssssssssi",$nama_admin, $phone_admin, $email_admin, $alamat_admin, $foto_admin, $username_admin, $final_password, $status_admin, $id_manajer);
    if($query->execute()){
        $_SESSION['status'] = "add_success";
        header("Location: index.php?halaman=daftaradmin");
        $query->close();
        exit();
    } else {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftaradmin");
        $query->close();
        exit();
    }
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Form Tambah admin</h3>
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
                    <a href="index.php?halaman=daftaradmin">Daftar admin</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Tambah admin</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Admin</div>
                    </div>
                    <div class="card-body">
                        <!-- Form mulai -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama">
                            </div>
                            <div class="form-group">
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="Masukkan Nomor Headphone" title="Inputan hanya boleh angka">
                            </div>
                            <div class="form-group">
                                <input type="text" id="email" name="email" class="form-control" placeholder="Masukkan Email">
                            </div>
                            <div class="form-group">
                                <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat"></textarea>
                            </div>
                            <div class="form-group">
                                <img id="displayImage" src="" width="250" height="250" />
                            </div>
                            <div class="form-group">
                                <span class="label">Foto Admin</span>
                                <input class="form-control" type="file"id="foto" name="foto_admin" onchange="openFile(event)">
                            </div>
                            <div class="form-group">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="fa fa-user"></i>
                                    </span>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan Username">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-icon">
                                    <span class="input-icon-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password">
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                                <button type="reset" class="btn btn-primary">Reset</button>
                                <a class="btn btn-danger" href="index.php?halaman=daftaradmin">Batal</a>
                            </div>
                        </form>
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
        let username = document.getElementById("username").value.trim();
        let password = document.getElementById("password").value.trim();
        let foto = document.getElementById("foto");
        const maxFileSize = 2*1024*1024;
        const formatFoto = ["jpg","jpeg",'png','webp'];

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
        
        if (!/^\+?\d+$/.test(phone)){
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

        if (!foto.files.length){
            e.preventDefault();
            showNotif("Foto tidak boleh kosong. Silahkan masukkan foto produk");
            return false;
        } else {
            const file = foto.files[0];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!formatFoto.includes(ext)) {
                e.preventDefault();
                showNotif("Foto tidak sesuai format (hanya boleh JPG, PNG, JPEG)");
                return false;
            }
            if (file.size > maxFileSize) {
                e.preventDefault();
                showNotif("Ukuran Foto tidak boleh lebih dari 2 MB");
                return false;
            }
        }

        if (username === ""){
            e.preventDefault();
            showNotif("Username tidak boleh kosong");
            return false;
        }

        if (password === ""){
            e.preventDefault();
            showNotif("Password tidak boleh kosong");
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            showNotif("Password minimal 8 karakter");
            return;
        }
    });
</script>
