<?php
session_start();
include 'koneksi.php';
include 'functions/functions.php';

//mengecek apakah form disubmit atau tidak
if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['notif'] = 'error-kosong'; // bisa ditangani dengan alert di halaman login
        header("Location: login.php");
        exit();
    }

    // Gunakan prepared statement
    $stmt = $con->prepare("SELECT * FROM tb_pelanggan WHERE username_pelanggan = ?");
    $stmt->bind_param("s", $username); // "s" berarti string
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        if (password_verify($password, $row['password_pelanggan'])) {
            $_SESSION['id_pelanggan'] = $row['id_pelanggan'];
            $_SESSION['username_pelanggan'] = $row['username_pelanggan'];
            $_SESSION['nama_pelanggan'] = $row['nama_pelanggan'];

            $_SESSION['notif'] = 'login-success';
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['notif'] = 'password-error';
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['notif'] = 'username-error';
        header("Location: login.php");
        exit();
    }

    $stmt->close();
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

    <!-- Load map styles -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
<!-- Start Login Page -->
<div class="wrapper" id="login">
<div class="container py-5 bg-white shadow rounded" id="login-container">
    <div class="row justify-content-center align-items-center">
      <!-- Kolom Logo -->
      <div class="col-lg text-center mb-4 mb-lg-0">
        <img src="assets/img/logo.svg" alt="Logo Indo Kimia Abadi" class="img-fluid" style="max-width: 300px; height: auto;">
      </div>
  
      <!-- Kolom Form -->
      <div class="col-lg">
        <div class="p-4">
          <h2 class="h2 mb-4"><b>Login</b></h2>
          <form method="POST" action="">
            <div class="form-group" id="form-login">
                <label for="password"><b>Username</b></label>
                <div class="input-group">
                    <span class="input-group-text" id="login-icon"><i class="fas fa-user"></i></img></span>
                    <input type="text" id="username" name="username" class="form-control" id="form-control-login" placeholder="Username">
                </div>
            </div>
            <div class="form-group" id="form-login">
                <label for="password"><b>Password</b></label>
                <div class="input-group">
                    <span class="input-group-text" id="login-icon"><i class="fas fa-lock"></i></img></span>
                    <input type="password" id="password" name="password" class="form-control" id="form-control-login" placeholder="Password">
                    <span class="input-group-text icon-eye" id="icon-password" onclick="togglePassword()" style="cursor: pointer;">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </span>
                </div>
            </div>
            
            <div class="d-grid mt-4">
                <button type="submit" name="submit" class="btn btn-primary" id="btn-login">Masuk</button>
            </div>
  
            <div class="text-center mt-3">
              <small> Belum punya akun? <a href="register.php" class="text-primary">Daftar di sini</a></small>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- End Login Page -->
  

    <!-- Start Script -->
    <script src="admin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="admin/assets/js/kaiadmin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- End Script -->

    <script>
        function togglePassword() {
          const passwordInput = document.getElementById("password");
          const icon = document.getElementById("togglePasswordIcon");
          if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
          } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
          }
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
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();
            if (username === "" && password === ""){
                e.preventDefault();
                showNotif("Username dan Password tidak boleh kosong");
                return false;
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
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['notif'])): ?>
                <?php if ($_SESSION['notif'] === 'login-success'): ?>
                    Swal.fire ({
                        title: 'Berhasil',
                        text: 'Selamat Datang Di Website Official PT Indo Kimia Abadi',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                <?php elseif ($_SESSION['notif'] === 'password-error') :?>
                    Swal.fire({
                        title: 'Password Salah',
                        text: 'Password yang dimasukan salah. Silahkan periksa kembali password',
                        icon: 'error',
                        confirmButtonText: 'Oke'
                    });
                <?php elseif ($_SESSION['notif'] === 'username-error'): ?>
                    Swal.fire({
                        title: 'Username Salah',
                        text: 'Silahkan periksa kembali username anda',
                        icon: 'error',
                        confirmButtonText: 'Oke'
                    });
                <?php elseif ($_SESSION['notif'] === 'error-kosong'): ?>
                    Swal.fire({
                        title: 'Input Tidak Boleh Kosong',
                        text: 'Username dan Password tidak boleh kosong!',
                        icon: 'error',
                        confirmButtonText: 'Oke'
                    });
                <?php endif; ?>
                <?php unset($_SESSION['notif']); ?>
            <?php endif; ?>
        }); 
    </script>
</body>

</html>