<?php
require '../koneksi.php';
session_start();

if (!isset($_SESSION['id_admin']) && !isset($_SESSION['id_manajer'])) {
    header("Location: login.php");
    exit();
}

$dari = isset($_GET['dari']) ? $_GET['dari'] : '';
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : '';
$hasil = [];

function isValidDate($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date);
}

if ($dari && $sampai && isValidDate($dari) && isValidDate($sampai)) {
    if (strtotime($dari) <= strtotime($sampai)) {
        $stmt = $con->prepare("SELECT * FROM tb_transaksi 
                               JOIN tb_invoice ON tb_transaksi.id_invoice = tb_invoice.id_invoice 
                               JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan 
                               JOIN tb_pelanggan ON tb_pesanan.id_pelanggan = tb_pelanggan.id_pelanggan 
                               WHERE tb_transaksi.tgl_bayar BETWEEN ? AND ? AND status_transaksi = 'Berhasil'
                               ORDER BY tb_transaksi.id_transaksi");
        $stmt->bind_param("ss", $dari, $sampai);
        $stmt->execute();
        $hasil = $stmt->get_result();
    } else {
        echo "<script>alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.'); window.location='index.php?halaman=daftarlaporan';</script>";
        exit;
    }
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Laporan Transaksi</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarlaporan">Daftar Laporan</a></li>
            </ul>
        </div>
    
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Filter Laporan Transaksi</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="index.php">
                        <input type="hidden" name="halaman" value="daftarlaporan">
                        <div class="row">
                            <div class="col-md-5">
                                <label>Dari Tanggal</label>
                                <input type="date" name="dari" class="form-control" value="<?= $dari ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="sampai" class="form-control" value="<?= $sampai ?>" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-round ms-auto"><i class="fa fa-search"></i>    Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CARD 2: HASIL LAPORAN (JIKA ADA) -->
            <?php if ($dari && $sampai): ?>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Hasil Laporan dari <?= date('d M Y', strtotime($dari)) ?> sampai <?= date('d M Y', strtotime($sampai)) ?></h5>
                    <a href="cetak_laporan.php?dari=<?= $dari ?>&sampai=<?= $sampai ?>" target="_blank" class="btn btn-success btn-round ms-auto">Cetak Laporan</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Invoice</th>
                                    <th>No Pesanan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Status Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($hasil && $hasil->num_rows > 0): ?>
                            <?php $no = 1; foreach ($hasil as $row): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['no_invoice']) ?></td>
                                    <td><?= htmlspecialchars($row['no_po']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                                    <td>Rp <?= number_format($row['jumlah_pembayaran'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tgl_bayar'])) ?></td>
                                    <td style="text-align: center;">
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-success btn-sm btn-round" type="button" data-bs-toggle="dropdown">
                                                <?= htmlspecialchars($row['status_transaksi']); ?>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data untuk periode ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

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
        let awal = document.getElementById("awal").value.trim();
        let akhir = document.getElementById("akhir").value.trim();

        if (awal === "") {
            e.preventDefault();
            showNotif("Tanggal Awal tidak boleh kosong");
            return false;
        }

        if (akhir === "") {
            e.preventDefault();
            showNotif("Tanggal Akhir tidak boleh kosong");
            return false;
        }

        // Konversi ke format Date untuk perbandingan
        let tAwal = new Date(awal);
        let tAkhir = new Date(akhir);

        if (tAkhir < tAwal) {
            e.preventDefault();
            showNotif("Tanggal Akhir tidak boleh lebih kecil dari Tanggal Awal");
            return false;
        }
    });
</script>