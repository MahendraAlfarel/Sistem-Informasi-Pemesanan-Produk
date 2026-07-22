<?php
include '../functions/functions.php';

if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'manajer') {
    // Jika bukan level manajer
    header("Location: index.php");
    exit;
}

if (isset($_POST['tambah'])) {
    $nama_manajer = trim($_POST['nama']);
    $phone_manajer = trim($_POST['phone']);
    $email_manajer = trim($_POST['email']);
    $alamat_manajer = trim($_POST['alamat']);
    $username_manajer = trim($_POST['username']);
    $password_manajer = $_POST['password'];
    $final_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (validasiFoto('foto_manajer')) {
        $ext = strtolower(pathinfo($_FILES['foto_manajer']['name'], PATHINFO_EXTENSION));
        $foto_manajer = uniqid('manajer_', true) . '.' . $ext;
        $lokasi = $_FILES['foto_manajer']['tmp_name'];

        move_uploaded_file($lokasi, "../images/foto_admin/" . $foto_manajer);
    } else {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    }

    if (empty($nama_manajer) || empty($phone_manajer) || empty($email_manajer) || empty($alamat_manajer) || empty($username_manajer) || empty($password_manajer)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    } elseif (strlen($password_manajer) < 8) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    } elseif (!filter_var($email_manajer, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    } elseif (preg_match('/\s/', $username_manajer)) {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftarmanajer");
        exit;
    }

    $cek = $con->prepare("SELECT * FROM tb_manajer WHERE nama_manajer = ? OR headphone_manajer = ? OR email_manajer = ? OR username_manajer = ?");
    $cek->bind_param("ssss", $nama_manajer, $phone_manajer, $email_manajer, $username_manajer);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarmanajer");
        exit();
    }

    $query = $con->prepare("INSERT INTO tb_manajer 
             (nama_manajer, headphone_manajer, email_manajer, alamat_manajer, foto_manajer, username_manajer, password_manajer) 
             VALUES (?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("sssssss", $nama_manajer, $phone_manajer, $email_manajer, $alamat_manajer, $foto_manajer, $username_manajer, $final_password);
    if($query->execute()){
        $_SESSION['status'] = "add_success";
        header("Location: index.php?halaman=daftarmanajer");
        $query->close();
        exit();
    } else {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarmanajer");
        $query->close();
        exit();
    }
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Form Tambah manajer</h3>
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
                    <a href="index.php?halaman=daftarmanajer">Daftar manajer</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Tambah manajer</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Manajer</div>
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
                                <span class="label">Foto manajer</span>
                                <input class="form-control" type="file" id="foto" name="foto_manajer" onchange="openFile(event)">
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
                                <a class="btn btn-danger" href="index.php?halaman=daftarmanajer">Batal</a>
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