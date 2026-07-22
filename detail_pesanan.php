<?php
// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

$invoice_id = $_GET['id'] ?? '';
$pesanan = getPesananByInvoiceId($con, $invoice_id);

if (!$pesanan) {
    echo "<script>
        alert('Akses Ditolak.');
        window.location.href = 'index.php';
    </script>";
    exit();
}

    // Batasi akses berdasarkan sesi pelanggan
if ($pesanan['id_pelanggan'] != $_SESSION['id_pelanggan']) {
    echo "Akses ditolak.";
    exit;
}

$status_pesanan = htmlspecialchars($pesanan['status_pesanan']);
$no_po = htmlspecialchars($pesanan['no_po']);
$no_invoice = htmlspecialchars($pesanan['no_invoice']);
$tgl_pemesanan = htmlspecialchars($pesanan['tgl_pemesanan']);
$nama_pelanggan = htmlspecialchars($pesanan['nama_pelanggan']);
$headphone_pelanggan = htmlspecialchars($pesanan['headphone_pelanggan']);
$email_pelanggan = htmlspecialchars($pesanan['email_pelanggan']);
$nama_penerima = htmlspecialchars($pesanan['nama_penerima']);
$headphone_penerima = htmlspecialchars($pesanan['headphone_penerima']);
$alamat_penerima = htmlspecialchars($pesanan['alamat_penerima']);
$subtotal = number_format($pesanan['subtotal'], 0, ',', '.');
$total_ppn = number_format($pesanan['total_ppn'], 0, ',', '.');
$total_harga = number_format($pesanan['total_harga'], 0, ',', '.');
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
</head>

<body>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-normal mb-0 text-black">Detail Pesanan</h3>
        <div class="print-button-container">
             <a href="cetak_invoice.php?id=<?= $invoice_id ?>" target="_blank" class="btn btn-success btn-sm">Cetak Invoice</a>
        </div>
    </div>
    <hr>
    
    <div class="card-body">
        <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Status Pesanan</th>
                                    <th><?= $status_pesanan ?></th>
                                </tr>
                            </thead>
                            <tbody>                              
                                <tr>
                                    <td>No Pre Order</td>
                                    <td><?= $no_po ?></td>
                                </tr>
                                <tr>
                                    <td>No Invoice</td>
                                    <td><?= $no_invoice ?></td>
                                </tr>
                                <tr>
                                    <td>Tanggal Pesanan</td>
                                    <td><?= $tgl_pemesanan ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title">Informasi Penerima</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nama</td>
                                    <td><?= $nama_penerima ?></td>
                                </tr>
                                <tr>
                                    <td>No Telepon</td>
                                    <td><?= $headphone_penerima ?></td>
                                </tr>
                                <tr>
                                <td>Alamat</td>
                                <td><?= $alamat_penerima ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title">Informasi Produk</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col">Harga Satuan</th>
                                    <th scope="col">QTY</th>
                                    <th scope="col">Total Harga</th>
                                </tr>
                            </thead>

                            <tbody>
                               <?php if (!empty($pesanan['produk'])): ?>
                                    <?php $no = 1;?>
                                    <?php foreach ($pesanan['produk'] as $detailPesanan): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($detailPesanan['nama_produk_satuan']); ?></td>
                                            <td>Rp. <?= number_format($detailPesanan['harga_produk_satuan'], 0, ',', '.'); ?></td>
                                            <td><?= htmlspecialchars($detailPesanan['jumlah']); ?></td>
                                            <td>Rp. <?= number_format($detailPesanan['subtotal_produk'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada produk dalam pesanan ini.</td>
                                        </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Subtotal</td>
                                    <td>Rp. <?= $subtotal ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">PPN 11%</td>
                                    <td>Rp. <?= $total_ppn ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Grandtotal</td>
                                    <td>Rp. <?= $total_harga ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div> 
    </div> 
</body>

</html>