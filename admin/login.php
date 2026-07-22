<?php
require '../koneksi.php';
session_start();

$alert = ''; // Inisialisasi pesan alert kosong

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validasi: username dan password tidak boleh kosong
    if (empty($username) || empty($password)) {
        $alert = "<script>
            swal({
                title: 'Input Tidak Boleh Kosong',
                text: 'Username dan Password tidak boleh kosong!',
                icon: 'error',
                button: 'Oke'
            });
        </script>";
    } else {
        $admin = false;
        $manajer = false;
        $userDitemukan = false;
        $passwordDitemuakan = false;

        // Cek ke tabel admin
        $stmtAdmin = $con->prepare("SELECT * FROM tb_admin WHERE username_admin = ? AND status_admin = 'Aktif'");
        $stmtAdmin->bind_param("s", $username);
        $stmtAdmin->execute();
        $resultAdmin = $stmtAdmin->get_result();

        if ($rowAdmin = $resultAdmin->fetch_assoc()) {
            $userDitemukan = true;
            if (password_verify($password, $rowAdmin['password_admin'])) {
                $passwordDitemuakan = true;
                $admin = true;
                $_SESSION['username_admin'] = $rowAdmin['username_admin'];
                $_SESSION['id_admin'] = $rowAdmin['id_admin'];
                $_SESSION['level'] = 'admin';
            }
        }
        $stmtAdmin->close();

        // Jika bukan admin, cek manajer
        if (!$userDitemukan || !$passwordDitemuakan) {
            $stmtManajer = $con->prepare("SELECT * FROM tb_manajer WHERE username_manajer = ? AND status_manajer = 'Aktif'");
            $stmtManajer->bind_param("s", $username);
            $stmtManajer->execute();
            $resultManajer = $stmtManajer->get_result();

            if ($rowManajer = $resultManajer->fetch_assoc()) {
                $userDitemukan = true;
                if (password_verify($password, $rowManajer['password_manajer'])) {
                    $passwordDitemuakan = true;
                    $manajer = true;
                    $_SESSION['username_manajer'] = $rowManajer['username_manajer'];
                    $_SESSION['id_manajer'] = $rowManajer['id_manajer'];
                    $_SESSION['level'] = 'manajer';
                }
            }
            $stmtManajer->close();
        }

        // Handle hasil login
        if ($userDitemukan && $passwordDitemuakan) {
            $alert = "<script>
                swal({
                    title: 'Berhasil Login',
                    text: 'Selamat datang di PT Indo Kimia Abadi!',
                    icon: 'success',
                    button: false,
                    timer: 1000
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
        } elseif ($userDitemukan && !$passwordDitemuakan) {
            $alert = "<script>
                swal({
                    title: 'Password Salah',
                    text: 'Silakan periksa kembali password Anda!',
                    icon: 'error',
                    button: 'Coba Lagi'
                });
            </script>";
        } else {
            $alert = "<script>
                swal({
                    title: 'Username Tidak Ditemukan',
                    text: 'Silakan periksa kembali username Anda!',
                    icon: 'error',
                    button: 'Coba Lagi'
                });
            </script>";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <title>Login - PT INDO KIMIA ABADI</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon"/>
        <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>
    <style>
        body {
            background-color: #f3f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 385px;
            height: 420px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-container img {
            margin-bottom: 20px;
        }

        .form-group { /* Membuat div untuk label dan input beserta ikon */
            margin-bottom: 5px;
            position: relative; /* Untuk memposisikan ikon di dalamnya */
        }

        .form-control {
            border-radius: 5px;
            height: 45px;
        }

        .form-group label[for="username"],
        .form-group label[for="password"]{
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group .input-icon {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            color: #000000;
            font-size: 16px;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <img src="assets/img/logo.svg" alt="Logo" width="100">
        <form method="POST">
            <div class="form-group">
                <label for="password">Username</b></label>
                <div class="input-group">
                    <span class="input-group-text" id="login-icon"><i class="fas fa-user"></i></img></span>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username">
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</b></label>
                <div class="input-group">
                    <span class="input-group-text" id="login-icon"><i class="fas fa-lock"></i></img></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3" name="submit">Masuk</button>
        </form>
    </div>

    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <?php if (!empty($alert)) echo $alert; ?>

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

        document.querySelector("form").addEventListener("submit", function (e) {;
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

</body>
</html>