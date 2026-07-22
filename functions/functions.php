<?php
// Mengambil Data Pelanggan Dari Session ID Pelanggan
function getProfilUser($id){
    global $con;
    $stmt = $con->prepare("SELECT * FROM tb_pelanggan WHERE id_pelanggan = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Valiadasi Foto
function validasiFoto($input_name) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $max_size = 2 * 1024 * 1024; // 2MB

    $file_name = $_FILES[$input_name]['name'];
    $file_tmp = $_FILES[$input_name]['tmp_name'];
    $file_size = $_FILES[$input_name]['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Cek ekstensi
    if (!in_array($file_ext, $allowed_ext)) {
        return false;
    }

    // Cek apakah file benar-benar gambar
    if (!getimagesize($file_tmp)) {
        return false;
    }

    // Cek ukuran file
    if ($file_size > $max_size) {
        return false;
    }

    return true;
}

// Memproses Foto
function prosesFoto($field, $fotoLama) {
    // Cek apakah file baru diupload dan tidak error
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {

        // Validasi file gambar dengan fungsi validasiFoto() yang sudah kamu buat
        if (!validasiFoto($field)) {
            return false; // Validasi gagal
        }

        // Ambil ekstensi file
        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));

        // Buat nama file acak
        $fotoBaru = uniqid('foto_', true) . '.' . $ext;

        // Path upload
        $uploadPath = "../images/foto_produk/" . $fotoBaru;

        // Upload file ke server
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadPath)) {
            // Hapus file lama jika ada
            if (!empty($fotoLama)) {
                $pathLama = "../images/" . $fotoLama;
                if (file_exists($pathLama)) {
                    unlink($pathLama);
                }
            }
            return $fotoBaru;
        } else {
            return false; // Upload gagal
        }
    }

    // Jika tidak ada file baru, kembalikan nama file lama
    return $fotoLama;
}

// Menampilkan Detail Produk Berdasarkan ID 
function getProdukById($con, $id) {
    if (!isset($id) || !ctype_digit($id)) {
        return false;
    }

    $id = (int)$id;

    $stmt = $con->prepare("SELECT * FROM tb_produk 
                           JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori 
                           WHERE id_produk = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    $stmt->close();

    return $produk ?: false;
}

// Menampilkan Manajer Berdasarkan ID 
function getManajerById($con, $id) {
    if (!isset($id) || !ctype_digit($id)) {
        return false;
    }

    $id = (int)$id;

    $stmt = $con->prepare("SELECT * FROM tb_manajer WHERE id_manajer = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $manajer = $result->fetch_assoc();
    $stmt->close();

    return $manajer ?: false;
}

// Menampilkan Admin Berdasarkan ID 
function getAdminById($con, $id) {
    if (!isset($id) || !ctype_digit($id)) {
        return false;
    }

    $id = (int)$id;

    $stmt = $con->prepare("SELECT * FROM tb_admin WHERE id_admin = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    return $admin ?: false;
}

// Menampilkan Detail Pelanggan Berdasarkan ID 
function getPelangganById($con, $id) {
    if (!isset($id) || !ctype_digit($id)) {
        return false;
    }

    $id = (int)$id;

    $stmt = $con->prepare("SELECT * FROM tb_pelanggan WHERE id_pelanggan = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    return $admin ?: false;
}

// Menampilkan Detail Pesanan Berdasarkan ID 
function getPesananByInvoiceId($con, $invoice_id) {
    if (!isset($invoice_id) || !ctype_digit($invoice_id)) {
        return false;
    }

    $invoice_id = (int)$invoice_id;

    // Ambil data invoice + pesanan + pelanggan
    $stmt = $con->prepare("SELECT * FROM tb_invoice 
        JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan 
        JOIN tb_pelanggan ON tb_pesanan.id_pelanggan = tb_pelanggan.id_pelanggan 
        WHERE tb_invoice.id_invoice = ?");
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoice_data = $result->fetch_assoc();
    $stmt->close();

    if (!$invoice_data) {
        return false;
    }

    // Ambil data produk dari pesanan
    $pesanan_id = $invoice_data['id_pesanan'];

    $stmt = $con->prepare("SELECT * FROM tb_detail_pesanan 
        JOIN tb_produk ON tb_detail_pesanan.id_produk = tb_produk.id_produk 
        WHERE tb_detail_pesanan.id_pesanan = ?");
    $stmt->bind_param("i", $pesanan_id);
    $stmt->execute();
    $produk_result = $stmt->get_result();

    $produk_list = [];
    while ($row = $produk_result->fetch_assoc()) {
        $produk_list[] = $row;
    }
    $stmt->close();

    // Gabungkan hasilnya jadi satu array
    $invoice_data['produk'] = $produk_list;

    return $invoice_data;
}

// Menampilkan Kategori
function getKategori() {
    global $con;
    $query = "SELECT * FROM tb_kategori WHERE status_kategori = 'Aktif'";
    $result = mysqli_query($con, $query);

    $kategori = [];
    while ($row = mysqli_fetch_assoc($result)){
        $kategori[] = $row;
    }
    return $kategori;
}

// Menampilkan Produk Baru
function getNewtProduk() {
    global $con;
    $query = "SELECT * FROM tb_produk JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori WHERE tb_produk.status_produk = 'Masih Diproduksi' AND tb_kategori.status_kategori = 'Aktif' ORDER BY id_produk DESC LIMIT 3";
    $result = mysqli_query($con, $query);

    $produk = [];
    while ($row = mysqli_fetch_assoc($result)){
        $produk[] = $row;
    }
    return $produk;
}

// Menampilkan Produk Lainnya di Detail Produk
function getRelatedProduct($produk_id) {
    global $con;
    // condition to check isset or not 
    $numToDisplay = 9;

    $query_related = "SELECT * FROM tb_produk JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori WHERE id_produk != ? AND status_produk='Masih Diproduksi' AND status_kategori='Aktif' ORDER BY rand() LIMIT ?";
    $stmt = $con->prepare($query_related);
    $stmt->bind_param("si", $produk_id, $numToDisplay);
    $stmt->execute();
    $result = $stmt->get_result();

    $produkList= [];
    while ($row = $result->fetch_assoc()){
        $produkList[] = $row;
    }

    return $produkList;
}

// Menampilkan Produk di Halaman Shop
function getProduk($kategori = null, $limit = 9, $offset = 0) {
    global $con;
    $sort = $_GET['sort'] ?? '';
    $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

    $sortQuery = "";
    switch($sort){
        case 'terbaru':
            $sortQuery = "ORDER BY tb_produk.id_produk DESC";
            break;
        case 'nama':
            $sortQuery = "ORDER BY tb_produk.nama_produk ASC";
            break;
        case 'harga_terendah':
            $sortQuery = "ORDER BY tb_produk.harga_produk ASC";
            break;
        case 'harga_tertinggi':
            $sortQuery = "ORDER BY tb_produk.harga_produk DESC";
            break;
        default:
            $sortQuery = "ORDER BY RAND(" . intval($_SESSION['rand_seed']) .")";
            break;
    }

    $query = "SELECT * FROM tb_produk JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori WHERE tb_produk.status_produk = 'Masih Diproduksi' AND tb_kategori.status_kategori = 'Aktif'";
    $params = [];
    $types = '';

    if($kategori !== null) {
        $query .= " AND tb_produk.id_kategori = ?";
        $types .= 'i';
        $params[] = $kategori; 
    }

    if(!empty($search)) {
        $query .= " AND tb_produk.nama_produk LIKE ?";
        $types .= 's';
        $params[] = '%' . $search . '%'; 
    }

    $query .= " $sortQuery LIMIT ? OFFSET ?";
    $types .= 'ii';
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $con->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Menampilkan Tombol Ke Halaman Selanjutnya
function getTotalProduk($kategori = null) {
    global $con;
    $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

    $query = "SELECT COUNT(*) as total FROM tb_produk 
              JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori 
              WHERE tb_produk.status_produk = 'Masih Diproduksi' 
              AND tb_kategori.status_kategori = 'Aktif'";

    $params = [];
    $types = '';

    if ($kategori !== null) {
        $query .= " AND tb_produk.id_kategori = ?";
        $types .= 'i';
        $params[] = $kategori;
    }

    if (!empty($search)) {
        $query .= " AND tb_produk.nama_produk LIKE ?";
        $types .= 's';
        $params[] = '%' . $search . '%';
    }

    $stmt = $con->prepare($query);

    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc();

    return $total['total'] ?? 0;
}

// Menambah Produk ke Keranjang Langsung dari Halaman Shop
function tambahKeranjang($produk_id){
    if (isset($_SESSION['keranjang'][$produk_id])) {
        $_SESSION['keranjang'][$produk_id] +=1;
    } else {
        $_SESSION['keranjang'][$produk_id] =1;
    }

    $_SESSION['notif'] = 'tambah-keranjang-success';
}

// Menampilkan Produk Di Keranjang dan Checkout
function getKeranjang(){
    global $con;
    $produk = [];

    if(!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
        return $produk;
    }

    foreach ($_SESSION['keranjang'] as $produk_id => $jumlah){
        $query = "SELECT * FROM tb_produk WHERE id_produk = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $produk_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if($row = $result->fetch_assoc()) {
            $row['jumlah'] = $jumlah;
            $row['subharga'] = $row['harga_produk'] * $jumlah;
            $produk[] = $row;
        }
    }

    return $produk;
}

// Menghapus Produk yang ada di keranjang
function hapusProdukKeranjang($produk_id) {
    if(isset($_SESSION['keranjang'][$produk_id])) {
        unset($_SESSION['keranjang'][$produk_id]);
        return true;
    }
    return false;
}

// Mendapatkan Waktu Estimasi Pemesanan
function getEstimasi($keranjang){
    global $con;
    $jumlahKeranjang = 0;
    foreach ($keranjang as $produk){
        $jumlahKeranjang += $produk['jumlah'];
    }
    $query = "SELECT SUM(jumlah) as total FROM tb_detail_pesanan JOIN tb_pesanan ON tb_detail_pesanan.id_pesanan = tb_pesanan.id_pesanan WHERE tb_pesanan.status_pesanan IN ('Sedang Diproses', 'Sedang Dikirim')";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $total_pesanan = (int)$row['total'];
    $total_produk = $jumlahKeranjang + $total_pesanan;
    $produksi_perhari = 100;
    $total_estimasi = ceil($total_produk / $produksi_perhari) + 1;
    return min($total_estimasi, 30)." Hari";
}

// Mendapatkan Total Harga Dari Produk Di Keranjang
function getTotalHarga(){
    global $con;
    if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
        return 0;
    }

    $total = 0;
    foreach ($_SESSION['keranjang'] as $produk_id => $jumlah) {
        $select_keranjang_query = "SELECT * FROM tb_produk WHERE id_produk ='$produk_id'";
        $select_keranjang_result = mysqli_query($con, $select_keranjang_query);
        while ($row = mysqli_fetch_assoc($select_keranjang_result)) {
            $produk_id = $row['id_produk'];
            $produk_harga = $row['harga_produk'];
            $subharga = $row['harga_produk'] * $jumlah;
            $total += $subharga;
        }
    }
    return $total;
}

function getAlamatUser() {
    global $con;
    $pelanggan_username = $_SESSION['username_pelanggan'];
    $get_detail_query = "SELECT * FROM tb_alamat_pelanggan tap JOIN tb_pelanggan tp ON tap.id_pelanggan = tp.id_pelanggan WHERE tp.username_pelanggan='$pelanggan_username'";
    $get_detail_result = mysqli_query($con, $get_detail_query);
    if (!isset($_GET['tambah_alamat']) && !isset($_GET['edit_alamat'])) {
        echo "
        <div class='d-flex justify-content-between align-items-center mb-4'>
            <h3 class='fw-normal mb-0 text-black'>Daftar Alamat</h3>
            <a href='profil.php?daftar_alamat=tambah_alamat' class='btn btn-success btn-sm rounded'>Tambah Alamat</a>
        </div>
        <hr>
        <div class='row'>
            <div class='col-md-12'>
                <div class='row'>
        ";

        if($get_detail_result && mysqli_num_rows($get_detail_result) > 0) {
            while ($row = mysqli_fetch_assoc($get_detail_result)) {
                $pelanggan_id_alamat = $row['id_alamat'];
                $pelanggan_nama = $row['nama_penerima'];
                $pelanggan_headphone = $row['headphone_penerima'];
                $pelanggan_alamat = $row['nama_jalan'];
                $pelanggan_provinsi = $row['nama_provinsi'];
                $pelanggan_kota = $row['nama_kota'];
                $pelanggan_kecamatan = $row['nama_kecamatan'];
                $pelanggan_kdps = $row['kode_pos'];

                echo "
                    <div class='col-sm-6 mb-3 mb-sm-3'>
                        <div class='card'>
                            <div class='card-header'>
                                Alamat Rumah
                                <div class='float-end'>
                                    <button type='button' class='btn btn-secondary btn-sm'>Utama</button>
                                </div>
                            </div>
                            <div class='card-body'>
                                <h5 class='card-title'>$pelanggan_nama</h5>
                                <p class='card-text'>
                                    $pelanggan_headphone<br><br>
                                    $pelanggan_alamat, $pelanggan_kecamatan, $pelanggan_kota,<br>
                                    $pelanggan_provinsi, Indonesia, $pelanggan_kdps
                                </p>
                                <div class='d-grid gap-2 d-md-flex justify-content-md-end'>
                                    <a href='profil.php?daftar_alamat=edit_alamat&id=" .$row['id_alamat']. "' class='btn btn-outline-primary me-md-2'>Edit</a>
                                    <button class='btn btn-outline-danger' onclick='confirmDelete(".$row['id_alamat'].")'>Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>";
                    }
                } else {
                    echo "<div class='text-center'><p class='text-muted'> Belum ada alamat yang tersedia. Silahkan tambah alamat anda.</p></div>";
                }
        echo" 
                </div>
            </div>
        </div>";
    }
}

// Mengubah Bulan Ke Angka Romawi
function bulanKeRomawi($bulan) {
    $romawi = [
        1  => 'I',
        2  => 'II',
        3  => 'III',
        4  => 'IV',
        5  => 'V',
        6  => 'VI',
        7  => 'VII',
        8  => 'VIII',
        9  => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];
    return $romawi[(int)$bulan];
}

// Mendapatkan No PO
function getNoPO() {
    global $con;

    $tahun = date('Y');
    $bulan = date('m');
    $bulan_romawi = bulanKeRomawi((int)$bulan);
    $prefix = "/PO/$bulan_romawi/IKA/$tahun";

    $ambil = "SELECT MAX(CAST(SUBSTRING_INDEX(no_po, '/', 1) AS UNSIGNED)) AS urutan 
              FROM tb_pesanan 
              WHERE YEAR(tgl_pemesanan) = ? AND MONTH(tgl_pemesanan) = ? 
              AND no_po LIKE CONCAT('%', ?)";

    $stmt = $con->prepare($ambil);
    $stmt->bind_param("iis", $tahun, $bulan, $prefix);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $urutan = (int)$result['urutan'] + 1;

    $no_po = "$urutan/PO/$bulan_romawi/IKA/$tahun";
    return $no_po;
}

// Mendapatkan No Invoice
function getNoInvoice($id_invoice, $tanggal_invoice) {
    global $con;
    $tahun_invoice = date('Y', strtotime($tanggal_invoice));
    $bulan = date('n', strtotime($tanggal_invoice));
    $bulan_invoice = bulanKeRomawi($bulan);

    return  "$id_invoice/INV/$bulan_invoice/IKA/$tahun_invoice";
}

// Mendapatkan Warna Status Pesanan
function getWarnaStatus($status) {
    return match ($status) {
        'Menunggu Pembayaran' => 'btn-warning',
        'Menunggu Konfirmasi' => 'btn-secondary' ,
        'Sedang Diproses' => 'btn-primary',
        'Sedang Dikirim' => 'btn-info',
        'Selesai' => 'btn-success',
        'Dibatalkan' => 'btn-danger',
        default => 'btn-dark'
    };
}

// Menampilkan Daftar Pesanan
function getDaftarPesanan($id, $status) {
    global $con;
    $pesanan = [];

    $query = "SELECT * FROM tb_invoice ti JOIN tb_pesanan tps ON ti.id_pesanan = tps.id_pesanan JOIN tb_pelanggan tp ON tps.id_pelanggan = tp.id_pelanggan WHERE tp.id_pelanggan= ?";

    if($status === 'Semua'){
        $query .= " AND tps.status_pesanan != 'Menunggu Pembayaran'";
    } else {
       $query .= " AND tps.status_pesanan = ?";
    }

    $query .= " ORDER BY ti.id_invoice DESC";

    if($status === 'Semua'){
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $id);
    } else {
        $stmt = $con->prepare($query);
        $stmt->bind_param("is", $id, $status);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $pesanan[] = $row;
    }

    return $pesanan;
}

// Menampilkan Daftar Pesanan Yang Belum Dibayar
function getMenungguPembayaran($id) {
    global $con;
    $query = "SELECT * FROM tb_invoice ti JOIN tb_pesanan tps ON ti.id_pesanan = tps.id_pesanan JOIN tb_pelanggan tp ON tps.id_pelanggan = tp.id_pelanggan WHERE tp.id_pelanggan= ? AND tps.status_pesanan = 'Menunggu Pembayaran' ORDER BY ti.id_invoice DESC";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $pesanan = [];
    while ($row = $result->fetch_assoc()) {
        $pesanan[] = $row;
    }

    return $pesanan;
}