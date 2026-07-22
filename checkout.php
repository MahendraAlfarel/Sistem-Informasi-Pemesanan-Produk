<?php 
session_start();
include 'koneksi.php';
include './functions/functions.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Mendapatkan data pelanggan
$row = getProfilUser($_SESSION['id_pelanggan']);
if($row){
    $pelanggan_id = $row['id_pelanggan'];
    $pelanggan_nama = $row['nama_pelanggan'];
    $pelanggan_alamat = $row['alamat_pelanggan'];
    $pelanggan_headphone = $row['headphone_pelanggan'];
    $pelanggan_provinsi = $row['nama_provinsi'];
    $pelanggan_kota = $row['nama_kota'];
    $pelanggan_kecamatan = $row['nama_kecamatan'];
    $pelanggan_kelurahan = $row['nama_kelurahan'];
    $pelanggan_kdps = $row['kode_pos']; 
}

// Menngambil data di keranjang
$keranjang = getKeranjang();

// Menghitung waktu estimasi
$estimasi = getEstimasi($keranjang);

// Menghitung Total Harga
$subtotal = getTotalHarga();
$ppn = $subtotal * 0.11;
$grandTotal = $subtotal + $ppn;

$notif = false;

if (isset($_POST["pemesanan"]) && $_POST['pemesanan'] === 'konfirmasi_pemesanan') {
    $nama_penerima = trim(mysqli_real_escape_string($con, $_POST['nama']));
    $headpone_penerima = trim(mysqli_real_escape_string($con, $_POST['phone']));
    $nama_jalan = trim(mysqli_real_escape_string($con, $_POST['alamat']));
    $catatan = trim(mysqli_real_escape_string($con, $_POST['catatan']));
    
    $alamatPilihan = isset($_POST['alamatPilihan']) ? $_POST['alamatPilihan'] : 'lama';
    if ($alamatPilihan === 'baru') {
        $provinsi_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_provinsi_baru']));
        $kota_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_kota_baru']));
        $kecamatan_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_kecamatan_baru']));
        $kelurahan_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_kelurahan_baru']));
        $kdps_penerima = trim(mysqli_real_escape_string($con, $_POST['kode_pos_baru']));
    } else {
        $provinsi_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_provinsi']));
        $kota_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_kota']));
        $kecamatan_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_kecamatan']));
        $kelurahan_penerima = trim(mysqli_real_escape_string($con, $_POST['nama_kelurahan']));
        $kdps_penerima = trim(mysqli_real_escape_string($con, $_POST['kode_pos']));
    }
    // Gabungkan semua menjadi satu string alamat lengkap
    $alamat_penerima = "$nama_jalan, $kelurahan_penerima, $kecamatan_penerima, $kota_penerima, $provinsi_penerima, $kdps_penerima";

    // Data lainnya
    $no_PO = getNoPO();
    $tanggal_pemesanan = date('Y-m-d');
    $status_pesanan = 'Menunggu Pembayaran';

    // Simpan pesanan
    $tambahpesanan = $con->prepare("INSERT INTO tb_pesanan 
        (no_po, id_pelanggan, nama_penerima, headphone_penerima, alamat_penerima, total_harga, catatan_pesanan, estimasi, tgl_pemesanan, status_pesanan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $tambahpesanan->bind_param("sisssissss", $no_PO, $pelanggan_id, $nama_penerima, $headpone_penerima, $alamat_penerima, $grandTotal, $catatan, $estimasi, $tanggal_pemesanan, $status_pesanan);
    $tambahpesanan->execute();
    $pesanan_id = $con->insert_id;
    $tambahpesanan->close();

    $tambahDetail = $con->prepare("INSERT INTO tb_detail_pesanan 
    (id_pesanan, id_produk, nama_produk_satuan, harga_produk_satuan, jumlah, subtotal_produk) 
    VALUES (?, ?, ?, ?, ?, ?)");

    // Simpan detail produk
    foreach ($_SESSION['keranjang'] as $produk_id => $jumlah) {
        $ambil = $con->query("SELECT * FROM tb_produk WHERE id_produk='$produk_id'");
        $produk = $ambil->fetch_assoc();
        $produk_nama = $produk['nama_produk'];
        $produk_harga = $produk['harga_produk'];
        $subHarga_Satuan = $produk_harga * $jumlah;
        $tambahDetail->bind_param("iisiii", $pesanan_id, $produk_id, $produk_nama, $produk_harga, $jumlah, $subHarga_Satuan);
        $tambahDetail->execute();
    }
    $tambahDetail->close();

    // Simpan invoice
    $no_invoice= "";
    $tanggal_invoice = date('Y-m-d');
    $jatuh_tempo = date('Y-m-d', strtotime($tanggal_invoice . ' +3 days'));
    $status_invoice = 'Belum Dibayar';

    $tambahInvoice = $con->prepare("INSERT INTO tb_invoice 
        (id_pesanan, no_invoice, subtotal, total_ppn, total_tagihan, tgl_invoice, jatuh_tempo, status_invoice) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $tambahInvoice->bind_param("isiiisss", $pesanan_id, $no_invoice, $subtotal, $ppn, $grandTotal, $tanggal_invoice, $jatuh_tempo, $status_invoice);
    $updateInvoice = $tambahInvoice->execute();
    $id_invoice = $con->insert_id;
    $tambahInvoice->close();

    if($updateInvoice){
        $no_invoice = getNoInvoice($id_invoice, $tanggal_invoice);

        $update = "UPDATE tb_invoice SET no_invoice = ? WHERE id_invoice = ?";
        $stmt = $con->prepare($update);
        $stmt->bind_param("si", $no_invoice, $id_invoice);
        $stmt->execute();
        $stmt->close();
        
        unset($_SESSION['keranjang']);
        $notif = true;
    } else {
        $notif = false;
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
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Header -->
    <?php include 'includes/header.php';?>
    <!-- Close Header -->

    <!-- Start Content Page -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1 text-black">Checkout</h1>
        </div>
    </div>

    <!-- Start Content -->
    <div class="site-section">
        <div class="container">
        <form action="" method="post" class="checkoutForm" id="checkoutForm">
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Data Diri Penerima</h2>
                    <div class="p-3 p-lg-5 border">
                        <div class="mb-3">
                            <label class="col-4 col-form-label text-black">Alamat Pengiriman</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="alamatPilihan" id="alamatlama" value="lama" onchange="gantiAlamat(this.value)" checked>
                                    <label class="form-check-label text-black" for="alamatlama">
                                        Saya ingin menggunakan alamat lama
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="alamatPilihan" id="alamatbaru" value="baru" onchange="gantiAlamat(this.value)">
                                    <label class="form-check-label text-black" for="alamatbaru">
                                        Saya ingin menggunakan alamat baru
                                    </label>
                                </div>
                         </div>
                        
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <label for="nama" class="text-black">Nama </label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama Penerima" value="<?php echo $pelanggan_nama; ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="text-black">Nomor Headphone</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan Nomor Headphone Penerima" value="<?php echo $pelanggan_headphone ?>"  readonly>
                            </div>
                        </div>
                        
                        <div class="form-group row mb-3">
                            <div class="col-md-12">
                                <label for="alamat" class="text-black">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat Penerima" value="<?php echo $pelanggan_alamat; ?>"  readonly>
                            </div>
                        </div>

                        <div class="form-group row mb-3" id="wilayah-lama">
                            <div class="col-md-2">
                                <label class="text-black">Provinsi</label>
                                <input type="text" class="form-control" name="nama_provinsi" readonly value="<?= $pelanggan_provinsi ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="text-black">Kota</label>
                                <input type="text" class="form-control" name="nama_kota" readonly value="<?= $pelanggan_kota ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="text-black">Kecamatan</label>
                                <input type="text" class="form-control" name="nama_kecamatan" readonly value="<?= $pelanggan_kecamatan ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="text-black">Kelurahan</label>
                                <input type="text" class="form-control" name="nama_kelurahan" readonly value="<?= $pelanggan_kelurahan ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="text-black">Kode Pos</label>
                                <input type="text" class="form-control" name="kode_pos" readonly value="<?= $pelanggan_kdps ?>">
                            </div>
                        </div>

                        <div class="form-group row mb-3 d-none" id="wilayah-baru">
                            <div class="col-md-2">
                                <label class="text-black">Provinsi</label>
                                <select class="form-control" id="provinsi" name="provinsi"></select>
                            </div>
                            <div class="col-md-2">
                                <label class="text-black">Kota</label>
                                <select class="form-control" id="kota" name="kota" disabled></select>
                            </div>
                            <div class="col-md-3">
                                <label class="text-black">Kecamatan</label>
                                <select class="form-control" id="kecamatan" name="kecamatan" disabled></select>
                            </div>
                            <div class="col-md-2">
                                <label class="text-black">Kelurahan</label>
                                <select class="form-control" id="kelurahan" name="kelurahan" disabled></select>
                            </div>
                            <div class="col-md-3">
                                <label class="text-black">Kode Pos</label>
                                <input type="text" class="form-control" id="kode_pos" name="kode_pos" disabled>
                            </div>
                        </div>
                        
                        <input type="hidden" name="id_provinsi" id="id_provinsi">
                        <input type="hidden" name="id_kota" id="id_kota">
                        <input type="hidden" name="id_kecamatan" id="id_kecamatan">
                        <input type="hidden" name="id_kelurahan" id="id_kelurahan">
                        <input type="hidden" name="nama_provinsi_baru" id="nama_provinsi_hidden">
                        <input type="hidden" name="nama_kota_baru" id="nama_kota_hidden">
                        <input type="hidden" name="nama_kecamatan_baru" id="nama_kecamatan_hidden">
                        <input type="hidden" name="nama_kelurahan_baru" id="nama_kelurahan_hidden">
                        <input type="hidden" name="kode_pos_baru" id="kode_pos_hidden">
                                                
                        <div class="form-group">
                            <div class="col-md-12">
                            <label for="catatan" class="text-black">Catatan Pesanan</label>
                            <textarea name="catatan" id="catatan" cols="30" rows="3" style="width: 100%; height: 90px;" class="form-control" placeholder="Masukkan Catatan Pesanan..."></textarea>
                        </div></div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Pesanan Kamu</h2>
                            <div class="p-3 p-lg-5 border">
                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </thead>
                                    
                                    <tbody>
                                        <?php if(empty($keranjang)) {
                                            echo "<tr><td colspan='6' class='text-center text-black'> Keranjang Kosong </td></tr>";
                                        } else {
                                            foreach($keranjang as $produk):
                                                $produk_id = $produk['id_produk'];
                                                $produk_nama = htmlspecialchars($produk['nama_produk']);
                                                $produk_harga = number_format($produk['subharga'], 0, ',', '.');
                                                $jumlah = htmlspecialchars($produk['jumlah']); ?>
                                        <tr>
                                            <td><?= $produk_nama ?></td>
                                            <td class="text-center"><?= $jumlah ?></td>
                                            <td>Rp. <?= $produk_harga ?></td>
                                        </tr>
                                        <?php endforeach; } ?>
                                        <tr>
                                            <td colspan="2" class="text-black font-weight-bold"><strong>Sub total</strong></td>
                                            <td class="text-black">Rp. <?= number_format($subtotal, 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-black font-weight-bold"><strong>PPN 11%</strong></td>
                                            <td class="text-black font-weight-bold">Rp. <?= number_format($ppn , 0, ',', '.'); ?></td>
                                        </tr>
                                         <tr>
                                            <td colspan="2" class="text-black font-weight-bold"><strong>Total</strong></td>
                                            <td class="text-black font-weight-bold" id="grand-total"><strong>Rp. <?= number_format($grandTotal, 0, ',', '.'); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-black font-weight-bold"><strong>Estimasi Pre Order</strong></td>
                                            <td class="text-black font-weight-bold"><strong><?= $estimasi ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-danger small">* Estimasi waktu pre-order hanya perkiraan waktu pengerjaan. Waktu pre-order bisa lebih cepat dan bisa lebih lama tergantung pesanan yang masuk.(Waktu pre-order terhitung sejak pesanan dikonfirmasi oleh Admin)</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg py-3 btn-block btn-order" name="btn-pemesanan"><input type="hidden" name="pemesanan" value="konfirmasi_pemesanan">Pesan</button>
                                    <a class="btn btn-danger btn-lg py-3 btn-block" href="keranjang.php">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </form>
        </div>
    </div> 
    <!-- Finish Content -->


    <!-- Start Footer -->
    <?php include 'includes/footer.php';?>
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="admin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <script src="admin/assets/js/kaiadmin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const provinsiSelect = document.getElementById('provinsi');
            const kotaSelect = document.getElementById('kota');
            const kecamatanSelect = document.getElementById('kecamatan');
            const kelurahanSelect = document.getElementById('kelurahan');
            const kodePosInput = document.getElementById('kode_pos');

            const id_provinsi = document.getElementById('id_provinsi');
            const id_kota = document.getElementById('id_kota');
            const id_kecamatan = document.getElementById('id_kecamatan');
            const id_kelurahan = document.getElementById('id_kelurahan');

            const nama_provinsi_hidden = document.getElementById('nama_provinsi_hidden');
            const nama_kota_hidden = document.getElementById('nama_kota_hidden');
            const nama_kecamatan_hidden = document.getElementById('nama_kecamatan_hidden');
            const nama_kelurahan_hidden = document.getElementById('nama_kelurahan_hidden');
            const kode_pos_hidden = document.getElementById('kode_pos_hidden');

            // Reset semua wilayah
            function resetWilayah() {
                provinsiSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kodePosInput.value = '';

                kotaSelect.disabled = true;
                kecamatanSelect.disabled = true;
                kelurahanSelect.disabled = true;
                kodePosInput.disabled = true;

                nama_provinsi_hidden.value = '';
                nama_kota_hidden.value = '';
                nama_kecamatan_hidden.value = '';
                kode_pos_hidden.value = '';
            }

            // Load Provinsi
            function loadProvinsi() {
                fetch('wilayah.php?endpoint=provinces')
                    .then(res => res.json())
                    .then(data => {
                        resetWilayah();
                        data.data.forEach(prov => {
                            provinsiSelect.add(new Option(prov.name, prov.code));
                        });
                        provinsiSelect.disabled = false;
                    });
            }

            // Event: Provinsi dipilih
            provinsiSelect.addEventListener('change', function () {
                const id = this.value;
                const text = this.options[this.selectedIndex].text;
                id_provinsi.value = id;
                nama_provinsi_hidden.value = text;

                kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kodePosInput.value = '';
                kotaSelect.disabled = true;
                kecamatanSelect.disabled = true;
                kelurahanSelect.disabled = true;
                kodePosInput.disabled = true;

                if (id !== '') {
                    fetch(`wilayah.php?endpoint=regencies&id=${id}`)
                        .then(res => res.json())
                        .then(data => {
                            data.data.forEach(item => {
                                kotaSelect.add(new Option(item.name, item.code));
                            });
                            kotaSelect.disabled = false;
                        });
                }
            });

            // Event: Kota dipilih
            kotaSelect.addEventListener('change', function () {
                const id = this.value;
                const text = this.options[this.selectedIndex].text;
                id_kota.value = id;
                nama_kota_hidden.value = text;

                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kodePosInput.value = '';
                kecamatanSelect.disabled = true;
                kelurahanSelect.disabled = true;
                kodePosInput.disabled = true;

                if (id !== '') {
                    fetch(`wilayah.php?endpoint=districts&id=${id}`)
                        .then(res => res.json())
                        .then(data => {
                            data.data.forEach(item => {
                                kecamatanSelect.add(new Option(item.name, item.code));
                            });
                            kecamatanSelect.disabled = false;
                        });
                }
            });

            // Event: Kecamatan dipilih
            kecamatanSelect.addEventListener('change', function () {
                const id = this.value;
                const text = this.options[this.selectedIndex].text;
                id_kecamatan.value = id;
                nama_kecamatan_hidden.value = text;

                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kodePosInput.value = '';
                kelurahanSelect.disabled = true;
                kodePosInput.disabled = true;

                if (id !== '') {
                    fetch(`wilayah.php?endpoint=villages&id=${id}`)
                        .then(res => res.json())
                        .then(data => {
                            data.data.forEach(item => {
                                kelurahanSelect.add(new Option(item.name, item.id));
                            });
                            kelurahanSelect.disabled = false;
                        });
                }
            });

            // Event: Kelurahan dipilih
            kelurahanSelect.addEventListener('change', function () {
                kodePosInput.disabled = this.value === '';
                if (this.value === '') {
                    kodePosInput.value = '';
                    kode_pos_hidden.value = '';
                }
            });

            // Event: Kode pos manual input
            kodePosInput.addEventListener('input', function () {
                kode_pos_hidden.value = this.value;
            });

            // Load provinsi saat halaman dimuat
            loadProvinsi();
        });
    </script>

    <script>
        function gantiAlamat(value) {
            const isBaru = value === 'baru';

            // Toggle wilayah
            document.getElementById('wilayah-lama').classList.toggle('d-none', isBaru);
            document.getElementById('wilayah-baru').classList.toggle('d-none', !isBaru);

            // Form input lainnya
            const namaInput = document.getElementById('nama');
            const telpInput = document.getElementById('phone');
            const alamatInput = document.getElementById('alamat');
            const kelurahanSelect = document.getElementById('kelurahan');
            const kodePosInput = document.getElementById('kode_pos');

            if (isBaru) {
                namaInput.readOnly = false;
                telpInput.readOnly = false;
                alamatInput.readOnly = false;

                namaInput.value = '';
                telpInput.value = '';
                alamatInput.value = '';
                kodePosInput.value = '';

                // Reset select dropdown
                document.getElementById('provinsi').innerHTML = '<option value="">Pilih Provinsi</option>';
                document.getElementById('kota').innerHTML = '<option value="">Pilih Kota</option>';
                document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
                document.getElementById('kelurahan').innerHTML = '<option value="">Pilih Kelurahan</option>';

                document.getElementById('kota').disabled = true;
                document.getElementById('kecamatan').disabled = true;
                document.getElementById('kelurahan').disabled = true;

                // Fetch ulang provinsi
                fetch('wilayah.php?endpoint=provinces')
                    .then(res => res.json())
                    .then(data => {
                        data.data.forEach(prov => {
                            const option = new Option(prov.name, prov.code);
                            document.getElementById('provinsi').add(option);
                        });
                    });

            } else {
                namaInput.readOnly = true;
                telpInput.readOnly = true;
                alamatInput.readOnly = true;

                namaInput.value = "<?= $pelanggan_nama ?>";
                telpInput.value = "<?= $pelanggan_headphone ?>";
                alamatInput.value = "<?= $pelanggan_alamat ?>";
                kodePosInput.value = "<?= $pelanggan_kdps ?>"; // jika tersedia

                // Hidden wilayah baru
                document.getElementById('provinsi').innerHTML = '<option value="">Pilih Provinsi</option>';
                document.getElementById('kota').innerHTML = '<option value="">Pilih Kota</option>';
                document.getElementById('kecamatan').innerHTML = '<option value="">Pilih Kecamatan</option>';
                document.getElementById('kelurahan').innerHTML = '<option value="">Pilih Kelurahan</option>';
            }
        }
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
                        setTimeout(() => {
                            if (element && element.get(0)) {
                                element.get(0).classList.add('notify-exit');
                            }
                        }, 200);
                    }
                }
            );
        }
        document.querySelector('.checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault(); // selalu hentikan submit default dulu

            const form = this;
            const alamatBaruDipilih = document.getElementById("alamatbaru").checked;

            // Ambil data input
            let nama = document.getElementById("nama").value.trim();
            let headphone = document.getElementById("phone").value.trim();
            let alamat = document.getElementById("alamat").value.trim();
            let provinsi = document.getElementById("provinsi");
            let kota = document.getElementById("kota");
            let kecamatan = document.getElementById("kecamatan");
            let kelurahan = document.getElementById("kelurahan");
            let kdps = document.getElementById("kode_pos");

            // Jika alamat baru dipilih, lakukan validasi
            if (alamatBaruDipilih) {
                if (nama === "") {
                    showNotif("Nama Penerima tidak boleh kosong");
                    return;
                }

                if (headphone === "") {
                    showNotif("No Headphone tidak boleh kosong");
                    return;
                }

                if (!/^\d+$/.test(headphone)) {
                    showNotif("No Headphone hanya boleh angka");
                    return;
                }

                if (alamat === "") {
                    showNotif("Alamat Penerima tidak boleh kosong");
                    return;
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
            }

            // Jika validasi lolos atau alamat lama digunakan, tampilkan SweetAlert
            Swal.fire({
                title: 'Yakin Ingin Melakukan pesanan?',
                text: 'Pastikan produk yang anda pesan sudah sesuai, dan data diri anda sudah sesuai',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pesan',
                cancelButtonText: 'Batal',
                customClass: {
                    cancelButton: 'btn btn-danger mx-2',
                    confirmButton: 'btn btn-primary'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

      
    <?php if(isset($_POST["pemesanan"]) && $_POST['pemesanan'] === 'konfirmasi_pemesanan'): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        <?php if ($notif): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Selamat Pesanan Kamu Berhasil.',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = 'profil.php?menunggu_pembayaran=true';
            });
            <?php else: ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Pesanan kamu gagal. Silahkan coba lagi.',
                showConfirmButton: true,
                confirmButtonText: 'Oke'
            })
        <?php endif ?>
        });
    </script>
    <?php endif ?>
    <!-- End Script -->
</body>

</html>