<?php
session_start();
include 'koneksi.php';
include 'functions/functions.php';

if (isset($_POST['tambah'])) {
    $pelanggan_nama = trim($_POST['nama']);
    $pelanggan_headphone = trim($_POST['headphone']);
    $pelanggan_email = trim($_POST['email']);
    $pelanggan_alamat = trim($_POST['alamat']);
    $id_provinsi = $_POST['provinsi'];
    $provinsi_nama = trim($_POST['nama_provinsi']);
    $kota_nama = trim($_POST['nama_kota']);
    $kecamatan_nama = trim($_POST['nama_kecamatan']);
    $kelurahan_nama = trim($_POST['nama_kelurahan']);
    $pelanggan_kdps = trim($_POST['kdps']);
    $pelanggan_username = trim($_POST['username']);
    $pelanggan_password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];
    $now = date('y-m-d H:i:s');

    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $pelanggan_foto = uniqid('pelanggan_') . '.' . $ext;
        $lokasifoto = $_FILES['foto']['tmp_name'];
        move_uploaded_file($lokasifoto, "images/foto_pelanggan/" . $pelanggan_foto);
    } else {
        $pelanggan_foto = 'people.png';
    }

    if (
        empty($pelanggan_nama) || empty($pelanggan_headphone) || empty($pelanggan_email) ||
        empty($pelanggan_alamat) || empty($id_provinsi) || empty($provinsi_nama) ||
        empty($kota_nama) || empty($kecamatan_nama) || empty($kelurahan_nama) || empty($pelanggan_kdps) ||
        empty($pelanggan_username) || empty($pelanggan_password) || empty($konfirmasi_password)
    ) {
        $_SESSION['registrasi_gagal'] = "Semua field wajib diisi.";
        header("Location: register.php");
        exit;
    } elseif (strlen($pelanggan_password) < 8) {
        $_SESSION['error'] = "Password minimal 8 karakter.";
        header("Location: register.php");
        exit;
    } elseif ($pelanggan_password !== $konfirmasi_password) {
        $_SESSION['error'] = "Password dan Konfirmasi Password tidak sesuai.";
        header("Location: register.php");
        exit;
    } elseif (!filter_var($pelanggan_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid.";
        header("Location: register.php");
        exit;
    } elseif (preg_match('/\s/', $pelanggan_username)) {
        $_SESSION['error'] = "Username tidak boleh mengandung spasi.";
        header("Location: register.php");
        exit;
    }

    $cek_headphone = mysqli_query($con, "SELECT * FROM tb_pelanggan WHERE headphone_pelanggan = '$pelanggan_headphone'");
    $cek_email = mysqli_query($con, "SELECT * FROM tb_pelanggan WHERE email_pelanggan = '$pelanggan_email'");
    $cek_user = mysqli_query($con, "SELECT * FROM tb_pelanggan WHERE username_pelanggan = '$pelanggan_username'");

    if (mysqli_num_rows($cek_headphone) > 0) {
        $_SESSION['error'] = "No Headphone sudah digunakan.";
    } elseif (mysqli_num_rows($cek_email) > 0) {
        $_SESSION['error'] = "Email sudah digunakan.";
    } elseif (mysqli_num_rows($cek_user) > 0) {
        $_SESSION['error'] = "Username sudah digunakan.";
    } else {
        $password_hash = password_hash($pelanggan_password, PASSWORD_DEFAULT);

        $tambah_pelanggan = $con->prepare("INSERT INTO tb_pelanggan 
        (nama_pelanggan, headphone_pelanggan, email_pelanggan, alamat_pelanggan, 
        id_provinsi, nama_provinsi, nama_kota, nama_kecamatan, nama_kelurahan, kode_pos, 
        foto_pelanggan, username_pelanggan, password_pelanggan, tgl_register)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? , ?, ?, ?)");
        $tambah_pelanggan->bind_param(
            "ssssissssissss",
            $pelanggan_nama,
            $pelanggan_headphone,
            $pelanggan_email,
            $pelanggan_alamat,
            $id_provinsi,
            $provinsi_nama,
            $kota_nama,
            $kecamatan_nama,
            $kelurahan_nama,
            $pelanggan_kdps,
            $pelanggan_foto,
            $pelanggan_username,
            $password_hash,
            $now
        );

        if ($tambah_pelanggan->execute()) {
            $_SESSION['registrasi_berhasil'] = "Selamat, Akun anda berhasil didaftarkan.";
            header("Location: register.php");
            $tambah_pelanggan->close();
            exit();
        } else {
            $_SESSION['registrasi_gagal'] = "Gagal membuat akun. Silahkan coba lagi.";
            header("Location: register.php");
            $tambah_pelanggan->close();
            exit();
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

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- Start Header -->
    <?php include 'includes/header.php'; ?>
    <!-- End Header -->

    <!-- Start Content -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Registrasi Akun</h1>
        </div>
    </div>

    <!-- Start Form Register -->
    <div class="container">
        <section class="h-100 d-flex align-items-center justify-content-center py-5" style="min-height: 100;">
            <div class="col-md-8">
                <div class="bg-light p-4 shadow rounded">
                    <h3>Pendaftar Pengguna Baru</h3>
                    <p>Sudah punya akun? Klik ke <a class="text-primary" href="login.php">Login</a></p>

                    <div class="separator-dashed mb-4"></div>

                    <form method="POST" action="" enctype="multipart/form-data" id="formRegister">
                        <!-- Bagian Data Diri -->
                        <h5 class="mb-3">
                            <i class="fas fa-address-card me-2"></i>DATA DIRI
                        </h5>

                        <div class="mb-3">
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Lengkap">
                        </div>

                        <div class="mb-3">
                            <input type="text" name="headphone" id="headphone" class="form-control" placeholder="Nomer Headphone" title="Input Hanya Boleh Angka">
                        </div>

                        <div class="mb-3">
                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                        </div>

                        <div class="mb-3">
                            <input type="file" name="foto" class="form-control">
                        </div>

                        <div class="separator-dashed my-4"></div>

                        <!-- Bagian Alamat -->
                        <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>ALAMAT</h5>
                        <div class="mb-3">
                            <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <select name="provinsi" id="provinsi" class="form-control">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="kota" id="kota" class="form-control" disabled>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="kecamatan" id="kecamatan" class="form-control" disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="kelurahan" id="kelurahan" class="form-control" disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <!-- <select name="kdps" id="kdps" class="form-control" disabled>
                                    <option value="">Pilih Kode Pos</option>
                                </select> -->
                                <input type="text" name="kdps" id="kdps_manual" class="form-control" placeholder="Masukkan Kode Pos" disabled>
                            </div>
                        </div>
                        <input type="hidden" name="nama_provinsi" id="nama_provinsi">
                        <input type="hidden" name="nama_kota" id="nama_kota">
                        <input type="hidden" name="nama_kecamatan" id="nama_kecamatan">
                        <input type="hidden" name="nama_kelurahan" id="nama_kelurahan">

                        <div class="separator-dashed my-4"></div>

                        <!-- Bagian Akun -->
                        <h5 class="mb-3"><i class="fas fa-user-alt me-2"></i>AKUN</h5>

                        <div class="mb-3">
                            <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                <span class="input-group-text icon-eye" onclick="togglePassword('password', this)" style="cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Konfirmasi Password">
                                <span class="input-group-text icon-eye" onclick="togglePassword('konfirmasi_password', this)" style="cursor: pointer;">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="separator-dashed my-4"></div>

                        <div class="d-grid gap-2">
                            <button type="submit" name="tambah" class="btn btn-primary">Daftar</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <a href="index.php" class="btn btn-danger">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <!-- Close Form Register -->
    <!-- End Content -->

    <!-- Start Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Start Script -->
    <script src="admin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <script src="admin/assets/js/kaiadmin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- End Script -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinsiSelect = document.getElementById('provinsi');
            const kotaSelect = document.getElementById('kota');
            const kecamatanSelect = document.getElementById('kecamatan');
            const kelurahanSelect = document.getElementById('kelurahan');
            const kodePosInput = document.getElementById('kdps_manual');

            const inputProvinsiNama = document.getElementById('nama_provinsi');
            const inputKotaNama = document.getElementById('nama_kota');
            const inputKecamatanNama = document.getElementById('nama_kecamatan');
            const inputKelurahanNama = document.getElementById('nama_kelurahan');

            // Fetch provinsi saat halaman dimuat
            fetch('wilayah.php?endpoint=provinces')
                .then(response => response.json())
                .then(data => {
                    data.data.forEach(prov => {
                        const option = new Option(prov.name, prov.code);
                        provinsiSelect.add(option);
                    });
                });

            // Saat Provinsi dipilih
            provinsiSelect.addEventListener('change', function() {
                kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kodePosInput.value = '';

                kotaSelect.disabled = true;
                kecamatanSelect.disabled = true;
                kelurahanSelect.disabled = true;
                kodePosInput.disabled = true;

                inputProvinsiNama.value = this.options[this.selectedIndex].text;

                if (this.value !== '') {
                    fetch(`wilayah.php?endpoint=regencies&id=${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            data.data.forEach(kota => {
                                const option = new Option(kota.name, kota.code);
                                kotaSelect.add(option);
                            });
                            kotaSelect.disabled = false;
                        });
                }
            });

            // Saat Kota dipilih
            kotaSelect.addEventListener('change', function() {
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kodePosInput.value = '';

                kecamatanSelect.disabled = true;
                kelurahanSelect.disabled = true;
                kodePosInput.disabled = true;

                inputKotaNama.value = this.options[this.selectedIndex].text;

                if (this.value !== '') {
                    fetch(`wilayah.php?endpoint=districts&id=${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            data.data.forEach(kec => {
                                const option = new Option(kec.name, kec.code);
                                kecamatanSelect.add(option);
                            });
                            kecamatanSelect.disabled = false;
                        });
                }
            });

            // Saat Kecamatan dipilih
            kecamatanSelect.addEventListener('change', function() {
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kodePosInput.value = '';

                kelurahanSelect.disabled = true;
                kodePosInput.disabled = true;

                inputKecamatanNama.value = this.options[this.selectedIndex].text;

                if (this.value !== '') {
                    fetch(`wilayah.php?endpoint=villages&id=${this.value}`)
                        .then(response => response.json())
                        .then(data => {
                            data.data.forEach(vill => {
                                const option = new Option(vill.name, vill.name); // gunakan name sebagai value juga
                                kelurahanSelect.add(option);
                            });
                            kelurahanSelect.disabled = false;
                        });
                }
            });

            // Saat Kelurahan dipilih
            kelurahanSelect.addEventListener('change', function() {
                inputKelurahanNama.value = this.options[this.selectedIndex].text;

                if (this.value !== '') {
                    kodePosInput.disabled = false;
                } else {
                    kodePosInput.disabled = true;
                }
            });
        });
    </script>

    <script>
        function togglePassword(inputId, iconSpan) {
            const input = document.getElementById(inputId);
            const icon = iconSpan.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <script>
        function showNotif(message, type = "danger", icon = "fa fa-times") {
            const notify = $.notify({
                icon: icon,
                title: "Terjadi Kesalahan",
                message: message
            }, {
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
                onClose: function() {
                    // bisa dikosongkan jika tidak perlu
                },
                onClosed: function(element) {
                    setTimeout(() => {
                        if (element && element.get(0)) {
                            element.get(0).classList.add('notify-exit');
                        }
                    }, 200);
                }
            });
        }
        document.getElementById("formRegister").addEventListener("submit", function(e) {
            let nama = document.getElementById("nama").value.trim();
            let phone = document.getElementById("headphone").value.trim();
            let email = document.getElementById("email").value.trim();
            let alamat = document.getElementById("alamat").value.trim();
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();
            let konfirm = document.getElementById("konfirmasi_password").value.trim();
            let provinsi = document.getElementById("provinsi");
            let kota = document.getElementById("kota");
            let kecamatan = document.getElementById("kecamatan");
            let kelurahan = document.getElementById("kelurahan");
            let kdps = document.getElementById("kdps_manual").value.trim();;

            if (nama === "") {
                e.preventDefault();
                showNotif("Nama tidak boleh kosong");
                return false;
            }

            if (phone === "") {
                e.preventDefault();
                showNotif("Nomer Headphone tidak boleh kosong");
                return false;
            }

            if (!/^\+?\d+$/.test(phone)) {
                e.preventDefault();
                showNotif("Nomer Headphone hanya boleh angka");
                return false;
            }

            if (email === "") {
                e.preventDefault();
                showNotif("Email tidak boleh kosong");
                return false;
            }

            if (!email.includes("@")) {
                e.preventDefault();
                showNotif("Email harus mengandung karakter '@'");
                return false;
            }

            if (alamat === "") {
                e.preventDefault();
                showNotif("Alamat tidak boleh kosong");
                return false;
            }

            if (provinsi && provinsi.value === "") {
                showNotif("Provinsi tidak boleh kosong");
                return;
            }

            if (kota && kota.value === "") {
                showNotif("Kota tidak boleh kosong");
                return;
            }

            if (kecamatan && kecamatan.value === "") {
                showNotif("Kecamatan tidak boleh kosong");
                return;
            }

            if (kelurahan && kelurahan.value === "") {
                showNotif("Kelurahan tidak boleh kosong");
                return;
            }

            if (kdps === "") {
                showNotif("Kode Pos tidak boleh kosong");
                return;
            }

            if (username === "") {
                e.preventDefault();
                showNotif("Username tidak boleh kosong");
                return false;
            }

            if (username.includes(" ")) {
                e.preventDefault();
                showNotif("Username tidak boleh mengandung spasi");
                return false;
            }

            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                e.preventDefault();
                showNotif("Username hanya boleh huruf, angka, dan underscore (_), tanpa spasi");
                return false;
            }

            if (password === "") {
                e.preventDefault();
                showNotif("Password tidak boleh kosong");
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                showNotif("Password minimal 8 karakter");
                return;
            }

            if (konfirm === "") {
                e.preventDefault();
                showNotif("Konfirmasi Password tidak boleh kosong");
                return false;
            }

            if (password !== konfirmasi) {
                e.preventDefault();
                showNotif("Password dan konfirmasi tidak sama");
                return;
            }
        });
    </script>
    <?php if (isset($_SESSION['error'])) : ?>
        <script>
            showNotif("<?= $_SESSION['error']; ?>");
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif ?>

    <?php if (isset($_SESSION['registrasi_berhasil'])) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?php echo $_SESSION['registrasi_berhasil']; ?>',
                confirmButtonText: 'Ok'
            });
        </script>
    <?php unset($_SESSION['registrasi_berhasil']);
    endif ?>

    <?php if (isset($_SESSION['registrasi_gagal'])) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?php echo $_SESSION['registrasi_gagal']; ?>',
                confirmButtonText: 'Ok'
            });
        </script>
    <?php unset($_SESSION['registrasi_gagal']);
    endif ?>

</body>

</html>