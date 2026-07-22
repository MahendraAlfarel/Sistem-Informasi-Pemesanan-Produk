<?php
// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

$id = $_SESSION['id_pelanggan'];

$tabList = [
    'Semua' => 'Semua Pesanan',
    'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
    'Sedang Diproses' => 'Sedang Diproses',
    'Sedang Dikirim' => 'Sedang Dikirim',
    'Selesai' => 'Selesai',
    'Dibatalkan' => 'Dibatalkan'
];
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
    <!-- Start Daftar Pesanan -->
    <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-normal mb-0 text-black">Daftar Pesanan Saya</h3>
    </div>
    <hr>
    <div class="col-md-12">
		<!-- buat lapisan awal <div class="card"> -->
			<div class="card-body">
				<ul class="nav nav-tabs nav-line nav-color-secondary" id="line-tab" role="tablist">
					<?php $i = 0; foreach ($tabList as $tab => $nama) : ?>
                    <li class="nav-item" style="text-align: center;">
						<a class="nav-link <?= $i === 0 ? 'active' : '' ?> "id="tab-<?= $i ?>" data-bs-toggle="pill" href="#content-<?= $i ?>" role="tab" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>">
                            <?= $nama ?>
                        </a>
					</li>
                    <?php $i++; endforeach; ?>
				</ul>

				<div class="tab-content mt-3 mb-3" id="line-tabContent">
                    <?php $i = 0;
                    foreach ($tabList as $status => $nama):
                        $DaftarPesanan = getDaftarPesanan($id, $status); ?>
					<div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="content-<?= $i ?>" role="tabpanel">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="text-align: center;">No Invoice</th>
                                                <th scope="col" style="text-align: center;">No Pre-Order</th>
                                                <th scope="col" style="text-align: center;">Total Harga</th>
                                                <th scope="col" style="text-align: center;">Tanggal Pemesanan</th>
                                                <th scope="col" style="text-align: center;">Status Pesanan</th>
                                                <th scope="col" style="text-align: center;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($DaftarPesanan) > 0): ?>
                                            <?php foreach($DaftarPesanan as $pesanan): 
                                                $invoice_id = $pesanan['id_invoice'];
                                                $pesanan_id = $pesanan['id_pesanan'];
                                                $no_invoice = $pesanan['no_invoice'];
                                                $no_po = $pesanan['no_po'];
                                                $totalHarga = number_format($pesanan['total_harga'], 0, ',', '.');
                                                $tgl_po = $pesanan['tgl_pemesanan'];
                                                $status = $pesanan['status_pesanan'];?>
                                            <tr>
                                                <td><?= $no_invoice ?></td>
                                                <td><?= $no_po ?></td>
                                                <td style="text-align: end;">Rp. <?= $totalHarga ?></td>
                                                <td style="text-align: center;"><?= $tgl_po ?></td>
                                                <td style="text-align: center;">
                                                    <button class="btn <?= getWarnaStatus($status)?> btn-sm btn-round">
                                                        <?= $status ?>
                                                    </button>
                                                </td>
                                                <td style="text-align: center;">
                                                    <div class="d-grip gap-2">
                                                        <a href="profil.php?daftar_pesanan=detail_pesanan&id=<?= $invoice_id ?>" class="btn btn-outline-secondary btn-sm">
                                                            Lihat
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-black" style="text-align: center;">Tidak Ada Pesanan.</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $i++; endforeach; ?>
				</div>
			</div>
		<!-- </div> -->
	</div>
    <!-- Close Daftar Pesanan -->
</body>
</html>