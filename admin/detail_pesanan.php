<?php
include '../functions/functions.php';

$invoice_id = $_GET['id'] ?? '';
$pesanan = getPesananByInvoiceId($con, $invoice_id);

if (!$pesanan) {
    $_SESSION['status'] = 'id_invalid';
    header("Location: index.php?halaman=daftarpesanan");
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

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Detail Pesanan</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarpesanan">Daftar Pesanan</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Detail Pesanan</a></li>
            </ul>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Detail Pesanan</h4>
                        <a href="cetak_invoice.php?id=<?= $invoice_id ?>" target="_blank" class="btn btn-success btn-sm">Cetak Invoice</a>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Info Pesanan -->
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

                    <!-- Data Pembeli & Penerima -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Data Pembeli</div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Nama</td>
                                                <td><?= $nama_pelanggan ?></td>
                                            </tr>
                                            <tr>
                                                <td>No Headphone</td>
                                                <td><?= $headphone_pelanggan ?></td>
                                            </tr>
                                            <tr>
                                                <td>Email</td>
                                                <td><?= $email_pelanggan ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Data Penerima</div>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>Nama Penerima</td>
                                                <td><?= $nama_penerima ?></td>
                                            </tr>
                                            <tr>
                                                <td>No Headphone</td>
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
                    </div>

                    <!-- Tabel Produk -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Produk</th>
                                        <th scope="col">Harga Satuan</th>
                                        <th scope="col">QTY</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= $no = 1;?>
                                    <?php foreach ($pesanan['produk'] as $detailPesanan): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($detailPesanan['nama_produk_satuan']); ?></td>
                                            <td>Rp. <?= number_format($detailPesanan['harga_produk_satuan'], 0, ',', '.'); ?></td>
                                            <td><?= htmlspecialchars($detailPesanan['jumlah']); ?></td>
                                            <td>Rp. <?= number_format($detailPesanan['subtotal_produk'], 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">SubTotal</td>
                                        <td>Rp. <?= $subtotal ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">PPN 11%</td>
                                        <td>Rp. <?= $total_ppn ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Total Harga</td>
                                        <td>Rp. <?= $total_harga ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div> <!-- end .card-body -->
            </div> <!-- end .card -->
        </div> <!-- end .col-md-12 -->
    </div> <!-- end .page-inner -->
</div> <!-- end .container -->
