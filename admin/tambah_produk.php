<?php
include '../functions/functions.php';
if (isset($_POST['tambah'])) {
    $nama = trim($_POST['nama']);
    $kategori = trim($_POST['kategori']);
    $kategori = intval($kategori);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = trim($_POST['harga']);
    $harga = intval($harga);
    $variasi = trim($_POST['variasi']);
    $berat = trim($_POST['berat']);
    $berat = intval($berat);
    $satuan = trim($_POST['satuan']);
    $status = "Masih Diproduksi";
    // Validasi field kosong
    if (
        empty($nama) || empty($kategori) || empty($deskripsi) || empty($harga) ||
        empty($variasi) || empty($berat) || empty($satuan)
    ) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarproduk");
        exit();
    }

        // Validasi harga dan berat numerik
    if (!is_numeric($harga) || $harga < 0 || !is_numeric($berat) || $berat < 0) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarproduk");
        exit();
    }

    // Validasi dan upload foto 1 (wajib)
    if (!validasiFoto('foto_produk')) {
        $_SESSION['status'] = "add_failed";
        exit(header("Location: index.php?halaman=daftarproduk"));
    }
    $ext1 = strtolower(pathinfo($_FILES['foto_produk']['name'], PATHINFO_EXTENSION));
    $foto1 = uniqid('Foto_Produk_1_', true) . '_' . random_int(1000, 9999) . '.' . $ext1;
    move_uploaded_file($_FILES['foto_produk']['tmp_name'], "../images/foto_produk/" . $foto1);

    // Foto opsional
    $foto2 = '';
    $foto3 = '';

    if (!empty($_FILES['foto_produk2']['name'])) {
        if (!validasiFoto('foto_produk2')) {
            $_SESSION['status'] = "add_failed";
            exit(header("Location: index.php?halaman=daftarproduk"));
        }
        $ext2 = strtolower(pathinfo($_FILES['foto_produk2']['name'], PATHINFO_EXTENSION));
        $foto2 = uniqid('Foto_Produk_2_', true) . '_' . random_int(1000, 9999) . '.' . $ext2;
        move_uploaded_file($_FILES['foto_produk2']['tmp_name'], "../images/foto_produk/" . $foto2);
    }

    if (!empty($_FILES['foto_produk3']['name'])) {
        if (!validasiFoto('foto_produk3')) {
            $_SESSION['status'] = "add_failed";
            exit(header("Location: index.php?halaman=daftarproduk"));
        }
        $ext3 = strtolower(pathinfo($_FILES['foto_produk3']['name'], PATHINFO_EXTENSION));
        $foto3 = uniqid('Foto_Produk_3_', true) . '_' . random_int(1000, 9999) . '.' . $ext3;
        move_uploaded_file($_FILES['foto_produk3']['tmp_name'], "../images/foto_produk/" . $foto3);
    }

    $cek = $con->prepare("SELECT * FROM tb_produk WHERE nama_produk = ? AND variasi_produk = ?");
    $cek->bind_param("ss", $nama, $variasi);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarproduk");
        exit();
    }

    $query = $con->prepare("INSERT INTO tb_produk
            (nama_produk, id_kategori, harga_produk, variasi_produk, berat_produk, deskripsi_produk, satuan_produk, foto_produk, foto_produk_2, foto_produk_3, status_produk) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("siisissssss", $nama, $kategori, $harga, $variasi, $berat, $deskripsi, $satuan, $foto1, $foto2, $foto3, $status);
    if($query->execute()){
        $_SESSION['status'] = "add_success";
        header("Location: index.php?halaman=daftarproduk");
        $query->close();
        exit();
    } else {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarproduk");
        $query->close();
        exit();
    }
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Form Tambah Produk</h3>
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
                    <a href="index.php?halaman=daftarproduk">Daftar Produk</a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Form Tambah Produk</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tambah Produk</div>
                    </div>
                    <div class="card-body">
                        <!-- Form mulai -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Produk">
                            </div>
                            <div class="form-group">
                                 <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" id="harga" name="harga" class="form-control"  placeholder="Masukkan Harga Produk">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" id="variasi" name="variasi" class="form-control"  placeholder="Masukkan Variasi Produk">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" id="berat" name="berat" class="form-control"  placeholder="Masukkan Berat Produk">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" id="satuan" name="satuan" class="form-control"  placeholder="Masukkan Satuan Produk">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <select id="kategori" name="kategori" class="form-select form-control" id="defaultSelect">
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php
                                    $query = mysqli_query($con, "SELECT * FROM tb_kategori WHERE status_kategori = 'Aktif'");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo "<option value='{$row['id_kategori']}'>{$row['nama_kategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Masukkan Deskripsi Produk"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Foto Produk 1</label>
                                <input class="form-control" type="file" id="foto1" name="foto_produk">
                            </div>
                            <div class="form-group">
                                <label>Foto Produk 2</label>
                                <input class="form-control" id="foto2" type="file" name="foto_produk2">
                            </div>
                            <div class="form-group">
                                <label>Foto Produk 3</label>
                                <input class="form-control" id="foto3" type="file" name="foto_produk3">
                            </div>
                            <div class="card-action">
                                <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                                <button type="reset" class="btn btn-primary">Reset</button>
                                <a class="btn btn-danger" href="index.php?halaman=daftarproduk">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Script untuk tambah variasi baru
function tambahVariasi() {
    const container = document.getElementById('variasi-container');
    const div = document.createElement('div');
    div.classList.add('row', 'variasi-item', 'mb-3');
    div.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="ukuran[]" class="form-control" placeholder="Ukuran (contoh: 1L, 120ml, XL)" required>
        </div>
        <div class="col-md-3">
            <input type="number" name="harga[]" class="form-control" placeholder="Harga (contoh: 15000)" required>
        </div>
        <div class="col-md-3">
            <input type="number" step="0.01" name="berat[]" class="form-control" placeholder="Berat (gram/ml)" required>
        </div>
    `;
    container.appendChild(div);
}
</script>

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
        let harga = document.getElementById("harga").value.trim();
        let variasi = document.getElementById("variasi").value.trim();
        let berat = document.getElementById("berat").value.trim();
        let satuan = document.getElementById("satuan").value.trim();
        let kategori = document.getElementById("kategori");
        let deskripsi = document.getElementById("deskripsi").value.trim();
        let foto1 = document.getElementById("foto1");
        let foto2 = document.getElementById("foto2");
        let foto3 = document.getElementById("foto3");
        const maxFileSize = 2*1024*1024;
        const formatFoto = ["jpg","jpeg",'png','webp'];

        if (nama === ""){
            e.preventDefault();
            showNotif("Nama produk tidak boleh kosong");
            return false;
        }

        if (harga === ""){
            e.preventDefault();
            showNotif("Harga produk tidak boleh kosong");
            return false;
        }
        
        if (!/^\d+$/.test(harga)){
            e.preventDefault();
            showNotif("Harga produk hanya boleh angka");
            return false;
        }

        if (variasi === ""){
            e.preventDefault();
            showNotif("Variasi produk tidak boleh kosong");
            return false;
        }

        if (berat === ""){
            e.preventDefault();
            showNotif("Berat produk tidak boleh kosong");
            return false;
        }

        if (!/^\d+$/.test(berat)){
            e.preventDefault();
            showNotif("Berat produk hanya boleh angka");
            return false;
        }

        if (satuan === ""){
            e.preventDefault();
            showNotif("Satuan produk tidak boleh kosong");
            return false;
        }

        if (kategori && kategori.value === ""){
            e.preventDefault();
            showNotif("Kategori produk tidak boleh kosong");
            return false;
        }

        if (deskripsi === ""){
            e.preventDefault();
            showNotif("Deskripsi produk tidak boleh kosong");
            return false;
        }

        if (!foto1.files.length){
            e.preventDefault();
            showNotif("Foto produk 1 tidak boleh kosong. Silahkan masukkan foto produk");
            return false;
        } else {
            const file = foto1.files[0];
            const ext = file.name.split('.').pop().toLowerCase();
            if (!formatFoto.includes(ext)) {
                e.preventDefault();
                showNotif("Foto produk 1 tidak sesuai format (hanya boleh JPG, PNG, JPEG)");
                return false;
            }
            if (file.size > maxFileSize) {
                e.preventDefault();
                showNotif("Ukuran Foto produk 1 tidak boleh lebih dari 2 MB");
                return false;
            }
        }

        if (foto2.files.length) {
                const file = foto2.files[0];
                const ext = file.name.split('.').pop().toLowerCase();
                if(!formatFoto.includes(ext)) {
                    e.preventDefault();
                    showNotif('Foto produk Produk 2 tidak sesuai format (hanya boleh JPG, PNG, JPEG');
                    return false;
                }
                if (file.size > maxFileSize) {
                    e.preventDefault();
                    showNotif('Foto produk 2 tidak boleh lebih dari 2 MB');
                    return false;
                }
            }

            if (foto3.files.length) {
                const file = foto3.files[0];
                const ext = file.name.split('.').pop().toLowerCase();
                if(!formatFoto.includes(ext)) {
                    e.preventDefault();
                    showNotif('Foto produk Produk 3 tidak sesuai format (hanya boleh JPG, PNG, JPEG');
                    return false;
                }
                if (file.size > maxFileSize) {
                    e.preventDefault();
                    showNotif('Foto produk 3 tidak boleh lebih dari 2 MB');
                    return false;
                }
            }
    });
</script>
