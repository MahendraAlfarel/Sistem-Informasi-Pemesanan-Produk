<?php
include '../functions/functions.php';
$produk_id = $_GET['id'] ?? '';
$produk = getProdukById($con, $produk_id);

if (!$produk) {
    $_SESSION['status'] = 'id_invalid';
    header("Location: index.php?halaman=daftarproduk");
    exit;
}

$produk_nama = htmlspecialchars($produk['nama_produk']);
$produk_harga = htmlspecialchars($produk['harga_produk']);
$produk_variasi = htmlspecialchars($produk['variasi_produk']);
$produk_berat = htmlspecialchars($produk['berat_produk']);
$produk_satuan = htmlspecialchars($produk['satuan_produk']);
$produk_kategori = htmlspecialchars($produk['id_kategori']);
$produk_deskripsi = htmlspecialchars($produk['deskripsi_produk']);
$produk_status = htmlspecialchars($produk['status_produk']);
$produk_foto1 = htmlspecialchars($produk['foto_produk']);
$produk_foto2 = htmlspecialchars($produk['foto_produk_2']);
$produk_foto3 = htmlspecialchars($produk['foto_produk_3']);

$data_kategori = $con->query("SELECT * FROM tb_kategori WHERE status_kategori = 'Aktif'");

if (isset($_POST['ubah'])) {
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
    $status = trim($_POST['status']);
    // Validasi field kosong
    if (
        empty($nama) || empty($kategori) || empty($deskripsi) || empty($harga) ||
        empty($variasi) || empty($berat) || empty($satuan) || empty($status)
    ) {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarproduk");
        exit();
    }

        // Validasi harga dan berat numerik
    if (!is_numeric($harga) || $harga < 0 || !is_numeric($berat) || $berat < 0) {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarproduk");
        exit();
    }

    // Ambil data lama
    $get = $con->prepare("SELECT foto_produk, foto_produk_2, foto_produk_3 FROM tb_produk WHERE id_produk = ?");
    $get->bind_param("i", $produk_id);
    $get->execute();
    $get->bind_result($fotoLama1, $fotoLama2, $fotoLama3);
    $get->fetch();
    $get->close();

    // Proses upload/update foto (semua tetap aman walau tidak diubah)
    $foto1 = prosesFoto('foto_produk', $fotoLama1);
    $foto2 = prosesFoto('foto_produk2', $fotoLama2);
    $foto3 = prosesFoto('foto_produk3', $fotoLama3);

    // Cek apakah ada kesalahan validasi
    if ($foto1 === false || $foto2 === false || $foto3 === false) {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarproduk");
        exit();
    }

    $cek = $con->prepare("SELECT * FROM tb_produk WHERE (nama_produk = ? AND variasi_produk = ?) AND id_produk != ?");
    $cek->bind_param("ssi", $nama, $variasi, $produk_id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarproduk");
        exit();
    }

    $query = "UPDATE tb_produk SET 
              nama_produk = ?,
              id_kategori = ?,
              harga_produk = ?,
              variasi_produk = ?,
              berat_produk = ?,
              deskripsi_produk = ?,
              satuan_produk = ?,
              foto_produk = ?,
              foto_produk_2 = ?,
              foto_produk_3 = ?,
              status_produk = ?
              WHERE id_produk = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("siisissssssi", $nama, $kategori, $harga, $variasi, $berat, $deskripsi, $satuan, $foto1, $foto2, $foto3, $status, $produk_id);
    if($stmt->execute()){
        $_SESSION['status'] = "update_success";
        header("Location: index.php?halaman=daftarproduk");
        $stmt->close();
        exit();
    } else {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarproduk");
        $stmt->close();
        exit();
    }
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Form Edit Produk</h3>
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
                    <a href="#">Form Edit Produk</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Produk</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                        <div class="col-md-4">
                                <div id="carouselProduk" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="../images/foto_produk/<?= $produk_foto1 ?>" class="d-block w-100" alt="Foto Produk Utama">
                                        </div>
                                        <?php if (!empty($produk_foto2)): ?>
                                        <div class="carousel-item">
                                            <img src="../images/foto_produk/<?= $produk_foto2 ?>" class="d-block w-100" alt="Foto Produk 2">
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($produk_foto3)): ?>
                                        <div class="carousel-item">
                                            <img src="../images/foto_produk/<?= $produk_foto3 ?>" class="d-block w-100" alt="Foto Produk 3">
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php
                                    // Hitung jumlah foto yang tersedia
                                    $fotoCount = 1;
                                    if (!empty($produk_foto2)) $fotoCount++;
                                    if (!empty($produk_foto3)) $fotoCount++;
                                    ?>

                                    <?php if ($fotoCount > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduk" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Sebelumnya</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselProduk" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Berikutnya</span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div style="border-bottom: 1px solid #000; margin-bottom: 15px;">
                                    <h3><b><?= $produk_nama ?></b></h3>
                                </div>
                                <form action="" id="formedit" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="disableinput">ID Produk</label>
                                        <input type="text" id="disableinput" class="form-control" value="<?= $produk_id ?>" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Produk</label>
                                        <input type="text" name="nama" id="nama" class="form-control" value="<?= $produk_nama ?>">
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Harga Produk</label>
                                                <input type="text" name="harga" id="harga" class="form-control" value="<?= $produk_harga ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Variasi Produk</label>
                                                <input type="text" name="variasi" id="variasi" class="form-control" value="<?= $produk_variasi ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Berat Produk</label>
                                                <input type="number" name="berat" id="berat" class="form-control" value="<?= $produk_berat ?>">
                                            </div>
                                            <div class="col-md-3">
                                                <label>Satuan Produk</label>
                                                <input type="text" name="satuan" id="satuan" class="form-control" value="<?= $produk_satuan ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="kategori" id="kategori" class="form-control">
                                            <?php while ($kategori = $data_kategori->fetch_assoc()) { ?>
                                                <option value="<?= $kategori['id_kategori']; ?>" 
                                                    <?= ($kategori['id_kategori'] == $produk_kategori) ? 'selected' : ''; ?>>
                                                    <?= $kategori['nama_kategori']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="deskripsi" id="deskripsi" class="form-control"><?= $produk_deskripsi ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Status Produk</label>
                                        <select name="status" class="form-control">
                                                <option value="Masih Diproduksi" <?= ($produk_status == 'Masih Diproduksi') ? 'selected' : ''; ?>>Masih Diproduksi
                                                </option>
                                                <option value="Tidak Diproduksi" <?= ($produk_status == 'Tidak Diproduksi') ? 'selected' : ''; ?>>Tidak Diproduksi
                                                </option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Foto Produk 1</label>
                                        <input class="form-control" id="foto1" type="file" name="foto_produk">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Foto Produk 2</label>
                                        <input class="form-control" id="foto2" type="file" name="foto_produk2" />
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Foto Produk 3</label>
                                        <input class="form-control" id="foto3" type="file" name="foto_produk3" />
                                    </div>
                                </form>
                            </div>
                            <div class="card-action">
                                <button type="submit" name="ubah" class="btn btn-success" form="formedit">Simpan</button>
                                <a class="btn btn-danger" href="index.php?halaman=daftarproduk">Kembali</a>
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

    document.querySelector("#formedit").addEventListener("submit", function (e) {
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
        const formatFoto = ["jpg","jpeg",'png'];

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

        if (foto1.files.length){
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

