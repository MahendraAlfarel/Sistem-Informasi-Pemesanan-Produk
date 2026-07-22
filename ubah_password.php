<?php
// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

if(isset($_GET['ubah_password'])){
    $row = getProfilUser($_SESSION['id_pelanggan']);
    if($row){
        $pelanggan_id = htmlspecialchars($row['id_pelanggan']);
        $pelanggan_nama = htmlspecialchars($row['nama_pelanggan']);
        $pelanggan_headphone = htmlspecialchars($row['headphone_pelanggan']);
        $pelanggan_email = htmlspecialchars($row['email_pelanggan']);
        $pelanggan_foto = htmlspecialchars(basename($row['foto_pelanggan']));
        $pelanggan_alamat = htmlspecialchars($row['alamat_pelanggan']);
        $pelanggan_provinsi = htmlspecialchars($row['nama_provinsi']);
        $pelanggan_kota = htmlspecialchars($row['nama_kota']);
        $pelanggan_kecamatan = htmlspecialchars($row['nama_kecamatan']);
        $pelanggan_kdps = htmlspecialchars($row['kode_pos']); 
        $pelanggan_username = htmlspecialchars($row['username_pelanggan']);
    }
}

$error_email = false;
$enable_form = false;
$notif_update = null;
$error_password = null;
$error_konfirmasi = null;

if(isset($_POST['cek_email'])){
    $pelanggan_email = trim($_POST['email']);
    if (
        empty($pelanggan_email)
    ) {
        $error_email = true;
    }
    $cek_email = $con->prepare("SELECT id_pelanggan FROM tb_pelanggan WHERE id_pelanggan = ? AND email_pelanggan = ?");
    $cek_email->bind_param("is", $pelanggan_id, $pelanggan_email);
    $cek_email->execute();
    $cek_email->store_result();
    
    if($cek_email->num_rows > 0){
        $_SESSION['email_valid'] = $pelanggan_email;
        $enable_form = true;
    } else {
        $error_email = true;
    }
}

if(isset($_POST['update'])){
    $email = $_SESSION['email_valid'] ?? null;
    $pelanggan_password = $_POST['password_lama'];
    $pelanggan_password_baru = $_POST['password_baru'];
    $konfirmasi_password = $_POST['konfirm_password_baru'];

    $cek_pass = $con->prepare("SELECT password_pelanggan FROM tb_pelanggan WHERE email_pelanggan = ?");
    $cek_pass->bind_param("s", $email);
    $cek_pass->execute();
    $cek_pass->bind_result($password_hash_lama);
    $cek_pass->fetch();
    $cek_pass->close();

    if (empty($pelanggan_password) || empty($pelanggan_password_baru) || empty($konfirmasi_password)) {
        $notif_update = false;
    } else {
        // Ambil data user dari email
        $cek = $con->prepare("SELECT password_pelanggan FROM tb_pelanggan WHERE email_pelanggan = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $result = $cek->get_result();
        $data = $result->fetch_assoc();
        if($password_hash_lama && password_verify($pelanggan_password, $password_hash_lama)) {
            if($pelanggan_password_baru === $konfirmasi_password) {
                $password_hash = password_hash($pelanggan_password_baru, PASSWORD_DEFAULT);
                $update_query = $con->prepare("UPDATE tb_pelanggan SET password_pelanggan = ? WHERE id_pelanggan = ?");
                $update_query->bind_param("si", $password_hash, $pelanggan_id);

                if($update_query->execute()) {
                    $notif_update = true;
                    unset($_SESSION['email_valid']);
                } else {
                    $notif_update = false; // Saat Update Gagal
                }
                $update_query->close();
            } else {
                $error_konfirmasi = true;
            }
        } else {
            $error_password = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>PT Indo Kimia Abadi - Official Website</title>
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
    <!-- Start Edit Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-normal mb-0 text-black">Ubah Password Akun Anda</h3>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <form action="" method="post" enctype="multipart/form-data" class="d-flex flex-column">

                    <div class="mb-3 row">
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" aria-describedby="emailHelp" placeholder="Masukkan Email">
                        </div>
                    </div>

                    <div class="mb-3 d-grid gap-2 d-md-flex justify-content-md-start">
                        <button type="submit" name="cek_email" class="btn btn-secondary">Masuk</button>
                        <a href="profil.php" class="btn btn-danger">Kembali</a>
                    </div>
                </form>
                <?php if ($enable_form): ?>
                <form id="formPassword" action="" method="post" enctype="multipart/form-data" class="d-flex flex-column" style="<?= $enable_form ? '':'display:none;' ?>">
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="inputPasswordLama" name="password_lama" placeholder="Masukkan Password Lama">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="inputPasswordBaru" name="password_baru" placeholder="Masukkan Password Baru">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col">
                            <label for="password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="inputKonfirmPassword" name="konfirm_password_baru" placeholder="Masukkan Password">
                        </div>
                    </div>

                    <div class="mb-3 d-grid gap-2 d-md-flex justify-content-md-start">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <a href="profil.php" class="btn btn-danger">Batal</a>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    <!-- Close Edit Password -->
    <script src="admin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="admin/assets/js/kaiadmin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if ($error_email): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Email Tidak Ditemukan',
                text: 'Silahkan masukkan email yang sesuai dengan akun anda',
                confirmButtonText: 'Oke'
            });
        </script>
    <?php endif; ?>

    <script>
        function showNotif(message, type = "danger", icon = "fa fa-times") {
            const notify = $.notify(
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
                    delay: 4000, // durasi total sebelum dihapus
                    allow_dismiss: true,
                    animate: {
                        enter: '',
                        exit: ''
                    },
                    onClose: function () {
                        // bisa dikosongkan jika tidak perlu
                    },
                    onClosed: function (element) {
                        // Tambahkan class exit dengan jeda 200ms agar terlihat lebih halus
                        setTimeout(() => {
                            element.classList.add('notify-exit');
                        }, 200); // jeda waktu sebelum animasi keluar dimulai
                    }
                }
            );
        }
        document.getElementById("formPassword").addEventListener("submit", function (e) {
            let password_lama = document.getElementById("inputPasswordLama").value.trim();
            let password_baru = document.getElementById("inputPasswordBaru").value.trim();
            let konfirm = document.getElementById("inputKonfirmPassword").value.trim();

            if (password_lama === "") {
                e.preventDefault();
                showNotif("Password lama tidak boleh kosong");
                return false;
            }

            if (password_baru === "") {
                e.preventDefault();
                showNotif("Password baru tidak boleh kosong");
                return false;
            }

            if (password_baru.length < 8) {
                e.preventDefault();
                showNotif("Password baru minimal 8 karakter");
                return false;
            }

            if (konfirm === "") {
                e.preventDefault();
                showNotif("Konfirmasi password tidak boleh kosong");
                return false;
            }

            if (password_baru !== konfirm) {
                e.preventDefault();
                showNotif("Konfirmasi password tidak cocok");
                return false;
            }
        });
    </script>

    <?php if ($error_password === true): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Password Lama Salah',
                text: 'Silahkan masukkan password lama yang benar',
                confirmButtonText: 'Oke'
            });
        </script>
    <?php endif; ?>

    <?php if($error_konfirmasi === true): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Konfirmasi Password dan Password Baru tidak sesuai',
                confirmButtonText: 'Oke'
            });
        </script>
    <?php endif; ?>

    <?php
    if ($notif_update === true) {
        session_unset();
        session_destroy();
        ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Password Anda berhasil diperbarui, silakan login kembali.',
                confirmButtonText: 'Oke'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
        </script>
    <?php
    } elseif ($notif_update === false) {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Mohon maaf, terjadi kesalahan saat menyimpan password baru.',
                confirmButtonText: 'Coba lagi'
            });
        </script>
    <?php
    }
    ?>
</body>

</html>