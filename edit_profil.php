<?php
// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

if(isset($_GET['edit_profil'])){
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

if(isset($_POST['update'])){
    $nama_p = trim($_POST['nama']);
    $headphone_p = trim($_POST['headphone']);
    $email_p = trim($_POST['email']);
    $username_p = trim($_POST['username']);
    $alamat_p = trim($_POST['alamat']);
    $provinsi_p = trim($_POST['provinsi']);
    $kdps_p = trim($_POST['kdps']);
    $provinsi_nama = trim($_POST['nama_provinsi']);
    $kota_nama = trim($_POST['nama_kota']);
    $kecamatan_nama = trim($_POST['nama_kecamatan']);
    $kelurahan_nama = trim($_POST['nama_kelurahan']);
    $now = date('Y-m-d H:i:s');

    if (
        empty($nama_p) || empty($headphone_p) || empty($email_p) || empty($alamat_p) || empty($provinsi_p) || 
        empty($provinsi_nama) || empty($kota_nama) || empty($kecamatan_nama) || empty($kelurahan_nama) || empty($kdps_p)
    ) {
        $_SESSION['update_gagal'] = "Semua field wajib diisi.";
        header("Location: profil.php?edit_profil");
        exit;
    }

    // Cek data duplikat
    $cek_headphone = $con->prepare("SELECT 1 FROM tb_pelanggan WHERE headphone_pelanggan = ? AND id_pelanggan != ?");
    $cek_headphone->bind_param("si", $headphone_p, $pelanggan_id);
    $cek_headphone->execute();
    $cek_headphone->store_result();

    $cek_email = $con->prepare("SELECT 1 FROM tb_pelanggan WHERE email_pelanggan = ? AND id_pelanggan != ?");
    $cek_email->bind_param("si", $email_p, $pelanggan_id);
    $cek_email->execute();
    $cek_email->store_result();

    $cek_user = $con->prepare("SELECT 1 FROM tb_pelanggan WHERE username_pelanggan = ? AND id_pelanggan != ?");
    $cek_user->bind_param("si", $username_p, $pelanggan_id);
    $cek_user->execute();
    $cek_user->store_result();

    if($cek_headphone->num_rows > 0){
        $_SESSION['error'] = "No Headphone sudah digunakan.";
    } elseif($cek_email->num_rows > 0){
        $_SESSION['error'] = "Email sudah digunakan.";
    } elseif($cek_user->num_rows > 0){
        $_SESSION['error'] = "Username sudah digunakan.";
    } else {
        $lokasifoto = $_FILES['foto']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $foto = uniqid('pelanggan_') . '.' . $ext;

        if(!empty($lokasifoto)) {
            if(move_uploaded_file($lokasifoto, "images/foto_pelanggan/$foto")) {
                $query = "UPDATE tb_pelanggan SET 
                    nama_pelanggan=?, alamat_pelanggan=?, headphone_pelanggan=?, email_pelanggan=?,
                    nama_provinsi=?, nama_kota=?, nama_kecamatan=?, nama_kelurahan=?,
                    kode_pos=?, username_pelanggan=?, foto_pelanggan=?, tgl_perbarui=? 
                    WHERE id_pelanggan=?";
                
                $stmt = $con->prepare($query);
                $stmt->bind_param("ssssssssisssi", $nama_p, $alamat_p, $headphone_p, $email_p,
                    $provinsi_nama, $kota_nama, $kecamatan_nama, $kelurahan_nama, $kdps_p,
                    $username_p, $foto, $now, $pelanggan_id);
            } else {
                $_SESSION['update_gagal'] = "Gagal mengupload Foto.";
                header("Location: profil.php?edit_profil");
                exit();
            }
        } else {
            $query = "UPDATE tb_pelanggan SET 
                nama_pelanggan=?, alamat_pelanggan=?, headphone_pelanggan=?, email_pelanggan=?,
                nama_provinsi=?, nama_kota=?, nama_kecamatan=?, nama_kelurahan=?,
                kode_pos=?, username_pelanggan=?, tgl_perbarui=? 
                WHERE id_pelanggan=?";
            
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssssssssissi", $nama_p, $alamat_p, $headphone_p, $email_p,
                $provinsi_nama, $kota_nama, $kecamatan_nama, $kelurahan_nama, $kdps_p,
                $username_p, $now, $pelanggan_id);
        }

        if($stmt->execute()){
            $_SESSION['update_berhasil'] = "Profil anda berhasil diperbarui!";
        } else {
            $_SESSION['update_gagal'] = "Gagal memperbarui profil. Silahkan coba lagi.";
        }
        $stmt->close();
    }

    header("Location: profil.php?edit_profil");
    exit();
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
    <!-- Start Edit profil -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-normal mb-0 text-black">Update Profil Anda</h3>
        </div>
        <hr>
        <form action="" id="formEditProfil" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <img src="images/foto_pelanggan/<?php echo $pelanggan_foto?>" alt="foto" class="img-fluid" style="width: 200px;" id="foto">
                            <div class="d-flex justify-content-center mb-2 my-4">
                                <button type="button" id="uploadFotoBtn" class="btn btn-outline-primary" style="width: 200px;">Ubah Foto</button>
                                <input type="file" class="form-control" name="foto" id="uploadFotoInput" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mb-3 row">
                        <label for="nama" class="form-label">Nama Lenkap</label>
                        <div class="col">
                            <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $pelanggan_nama; ?>">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col">
                            <label for="headphone" class="form-label">Nomer Headphone</label>
                            <input type="tel" class="form-control" id="headphone" name="headphone" value="<?php echo $pelanggan_headphone; ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" value="<?php echo $pelanggan_email ?>">
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="alamat" id="alamat" class="form-control" id="inputAlamat" name="alamat" aria-describedby="alamatHelp" value="<?php echo $pelanggan_alamat ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="provinsi" class="form-label">Provinsi</label>
                            <select name="provinsi" id="provinsi" class="form-control" required>
                                <option value="<?= $row['nama_provinsi']; ?>"><?= $row['nama_provinsi']; ?></option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="kota" class="form-label">Kota</label>
                            <select name="kota" id="kota" class="form-control" required>
                                <option value="<?= $row['nama_kota']; ?>"><?= $row['nama_kota']; ?></option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="kecamatan" class="form-label">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-control" required>
                                <option value="<?= $row['nama_kecamatan']; ?>"><?= $row['nama_kecamatan']; ?></option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="kelurahan" class="form-label">Kelurahan</label>
                            <select name="kelurahan" id="kelurahan" class="form-control" required>
                                <option value="<?= $row['nama_kelurahan']; ?>"><?= $row['nama_kelurahan']; ?></option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="kdps" class="form-label">Kode Pos</label>
                            <input type="text" name="kdps" id="kdps_manual"  class="form-control" value="<?=  $row['kode_pos']; ?>">
                        </div>
                    </div>

                    <input type="hidden" name="nama_provinsi" id="nama_provinsi" value="<?= $row['nama_provinsi']; ?>">
                    <input type="hidden" name="nama_kota" id="nama_kota" value="<?= $row['nama_kota']; ?>">
                    <input type="hidden" name="nama_kecamatan" id="nama_kecamatan" value="<?= $row['nama_kecamatan']; ?>">
                    <input type="hidden" name="nama_kelurahan" id="nama_kelurahan" value="<?= $row['nama_kelurahan']; ?>">


                    <div class="mb-3 row">
                        <label for="user_username" class="form-label">Username</label>
                        <div class="col">
                            <input type="text" name="username" id="username" class="form-control" value="<?php echo $pelanggan_username; ?>">
                        </div>
                    </div>

                    <div class="mb-3 d-grid gap-2 d-md-flex justify-content-md-start">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <a href="profil.php" class="btn btn-danger">Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    <!-- Close Edit profil -->

    <!-- Bootstrap CSS & JS (Toast) -->
    <script src="admin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="admin/assets/js/kaiadmin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap CSS & JS (Toast) -->

    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const provinsiSelect = document.getElementById('provinsi');
    const kotaSelect = document.getElementById('kota');
    const kecamatanSelect = document.getElementById('kecamatan');
    const kelurahanSelect = document.getElementById('kelurahan');
    const kodePosInput = document.getElementById('kdps_manual');

    const inputProvinsiNama = document.getElementById('nama_provinsi');
    const inputKotaNama = document.getElementById('nama_kota');
    const inputKecamatanNama = document.getElementById('nama_kecamatan');
    const inputKelurahanNama = document.getElementById('nama_kelurahan');

    // Default dari database
    const defaultProvinsi = "<?= $row['nama_provinsi']; ?>";
    const defaultKota = "<?= $row['nama_kota']; ?>";
    const defaultKecamatan = "<?= $row['nama_kecamatan']; ?>";
    const defaultKelurahan = "<?= $row['nama_kelurahan']; ?>";
    const defaultKodePos = "<?= $row['kode_pos']; ?>";

    let provinsiLoaded = false;

    provinsiSelect.addEventListener('click', function () {
        if (provinsiLoaded) return;

        fetch('wilayah.php?endpoint=provinces')
            .then(response => response.json())
            .then(data => {
                provinsiSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                data.data.forEach(prov => {
                    const option = new Option(prov.name, prov.code);
                    provinsiSelect.add(option);
                });
                provinsiLoaded = true;
            });
    });

    provinsiSelect.addEventListener('change', function () {
        const selectedId = this.value;
        inputProvinsiNama.value = this.options[this.selectedIndex].text;

        kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        kodePosInput.value = '';
        kodePosInput.disabled = true;

        inputKotaNama.value = '';
        inputKecamatanNama.value = '';
        inputKelurahanNama.value = '';

        kotaSelect.disabled = !selectedId;
        kecamatanSelect.disabled = true;
        kelurahanSelect.disabled = true;

        if (selectedId) loadKota(selectedId);
    });

    function loadKota(provinsiId, selected = '') {
        fetch(`wilayah.php?endpoint=regencies&id=${provinsiId}`)
            .then(response => response.json())
            .then(data => {
                kotaSelect.innerHTML = '<option value="">-- Pilih Kota --</option>';
                data.data.forEach(kota => {
                    const option = new Option(kota.name, kota.code);
                    if (kota.name === selected) {
                        option.selected = true;
                        inputKotaNama.value = kota.name;
                    }
                    kotaSelect.add(option);
                });
                kotaSelect.disabled = false;
            });
    }

    kotaSelect.addEventListener('change', function () {
        const selectedId = this.value;
        inputKotaNama.value = this.options[this.selectedIndex].text;

        kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        kodePosInput.value = '';
        kodePosInput.disabled = true;

        inputKecamatanNama.value = '';
        inputKelurahanNama.value = '';

        kecamatanSelect.disabled = !selectedId;
        kelurahanSelect.disabled = true;

        if (selectedId) loadKecamatan(selectedId);
    });

    function loadKecamatan(kotaId, selected = '') {
        fetch(`wilayah.php?endpoint=districts&id=${kotaId}`)
            .then(response => response.json())
            .then(data => {
                kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                data.data.forEach(kec => {
                    const option = new Option(kec.name, kec.code);
                    if (kec.name === selected) {
                        option.selected = true;
                        inputKecamatanNama.value = kec.name;
                    }
                    kecamatanSelect.add(option);
                });
                kecamatanSelect.disabled = false;
            });
    }

    kecamatanSelect.addEventListener('change', function () {
        const selectedId = this.value;
        inputKecamatanNama.value = this.options[this.selectedIndex].text;

        kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
        kodePosInput.value = '';
        kodePosInput.disabled = true;

        inputKelurahanNama.value = '';
        kelurahanSelect.disabled = !selectedId;

        if (selectedId) loadKelurahan(selectedId);
    });

    function loadKelurahan(kecamatanId, selected = '') {
        fetch(`wilayah.php?endpoint=villages&id=${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                kelurahanSelect.innerHTML = '<option value="">-- Pilih Kelurahan --</option>';
                data.data.forEach(village => {
                    const option = new Option(village.name, village.name);
                    if (village.name === selected) {
                        option.selected = true;
                        inputKelurahanNama.value = village.name;
                    }
                    kelurahanSelect.add(option);
                });
                kelurahanSelect.disabled = false;
            });
    }

    kelurahanSelect.addEventListener('change', function () {
            const kelurahanNama = this.value;
            inputKelurahanNama.value = kelurahanNama;
            if (kelurahanNama) {
                kodePosInput.disabled = false;
            } else {
                kodePosInput.disabled = true;
                kodePosInput.value = '';
            }
        });
    });
    </script>

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
        document.getElementById("formEditProfil").addEventListener("submit", function (e) {
            let nama = document.getElementById("nama").value.trim();
            let phone = document.getElementById("headphone").value.trim();
            let email = document.getElementById("email").value.trim();
            let alamat = document.getElementById("alamat").value.trim();
            let username = document.getElementById("username").value.trim();

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

            if (username === ""){
                e.preventDefault();
                showNotif("Username tidak boleh kosong");
                return false;
            }
        });
    </script>
    <?php if(isset($_SESSION['error'])) : ?>
        <script>showNotif("<?= $_SESSION['error']; ?>");</script>
        <?php unset($_SESSION['error']);?>
    <?php endif ?>

    <script>
        document.getElementById('uploadFotoBtn').addEventListener('click', function() {
            document.getElementById('uploadFotoInput').click();
        });

        document.getElementById('uploadFotoInput').addEventListener('change', function(){
            const file = this.files[0];
            const preview = document.getElementById('foto');

            if (file){
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            } else {
                preview.src = "./images/foto_pelanggan/<?php echo $pelanggan_foto ?>";
            }
        });
    </script>

    <?php if (isset($_SESSION['update_berhasil'])) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?php echo $_SESSION['update_berhasil']; ?>',
                confirmButtonText: 'Ok'
            });
        </script>
    <?php unset($_SESSION['update_berhasil']); endif ?>

    <?php if (isset($_SESSION['update_gagal'])) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?php echo $_SESSION['update_gagal']; ?>',
                confirmButtonText: 'Ok'
            });
        </script>
    <?php unset($_SESSION['update_gagal']); endif ?>
</body>

</html>