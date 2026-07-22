<?php
$date_now = date('Y-m-d');
$con->query("UPDATE tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan SET tb_pesanan.status_pesanan = 'Dibatalkan' WHERE tb_invoice.jatuh_tempo < '$date_now' AND tb_pesanan.status_pesanan = 'Menunggu Pembayaran'");

$con->query("UPDATE tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan SET tb_invoice.status_invoice = 'Kedaluwarsa' WHERE tb_invoice.jatuh_tempo < '$date_now' AND tb_invoice.status_invoice = 'Belum Bayar'");

// Proses Edit Status Pesanan
if (isset($_POST['update'])) {
    $id_pesanan = trim($_POST['id_pesanan']);
    $status_pesanan = trim($_POST['status_pesanan']);

    // Update status pesanan terlebih dahulu
    $query = $con->prepare("UPDATE tb_pesanan SET status_pesanan = ? WHERE id_pesanan = ?");
    $query->bind_param("si", $status_pesanan, $id_pesanan);

    if ($query->execute()) {
        $query->close();

        // Jika status baru adalah 'Dibatalkan', update invoice dan transaksi juga
        if ($status_pesanan == "Dibatalkan") {
            // Cari ID invoice berdasarkan ID pesanan
            $stmt = $con->prepare("SELECT id_invoice FROM tb_invoice WHERE id_pesanan = ?");
            $stmt->bind_param("i", $id_pesanan);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id_invoice = $row['id_invoice'];

                // Update status invoice jadi 'Dibatalkan'
                $stmt_invoice = $con->prepare("UPDATE tb_invoice SET status_invoice = 'Dibatalkan' WHERE id_invoice = ?");
                $stmt_invoice->bind_param("i", $id_invoice);
                $stmt_invoice->execute();
                $stmt_invoice->close();
            }

            $stmt->close();
        }

        $_SESSION['status'] = "update_success";
    } else {
        $query->close();
        $_SESSION['status'] = "update_failed";
    }
}
?>


<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Pesanan Baru</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarkategori">Daftar Pesanan Baru</a></li>
            </ul>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Pesanan Baru</h4>
                    </div>
                </div>
                
                <div class="card-body">

                    <!-- Tabel -->
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Pesanan</th>
                                    <th>Nama Pemesanan</th>
                                    <th>Nomer Headphone Pemesanan</th>
                                    <th>Tanggal Pre Order</th>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <th>Status Pesanan</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $ambil = $con->query("SELECT * FROM tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan JOIN tb_pelanggan ON tb_pesanan.id_pelanggan = tb_pelanggan.id_pelanggan WHERE status_pesanan = 'Menunggu Pembayaran'");
                                while ($pesanan = $ambil->fetch_assoc()) { 
                                    $id_pesanan = $pesanan['id_pesanan']; ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($pesanan['no_po']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['nama_pelanggan']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['headphone_pelanggan']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['tgl_pemesanan']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['jatuh_tempo']); ?></td>
                                        <td style="text-align: center;">
                                            <?php if ($_SESSION['level'] === 'admin'): ?>
                                            <div class="btn-group dropdown">
                                                <button class="btn btn-warning btn-sm btn-round dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <?= htmlspecialchars($pesanan['status_pesanan']); ?>
                                                </button>
                                                <ul class="dropdown-menu" role="menu">
                                                    <li>
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan); ?>">
                                                            <input type="hidden" name="status_pesanan" value="Dibatalkan">
                                                            <button type="submit" name="update" class="dropdown-item">Dibatalkan</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            <?php elseif ($_SESSION['level'] === 'manajer'): ?>
                                                <div class="btn-group">
                                                    <button class="btn btn-warning btn-sm btn-round" type="button">
                                                        <?= htmlspecialchars($pesanan['status_pesanan']); ?>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="index.php?halaman=detailpesanan&id=<?php echo $pesanan['id_pesanan']; ?>"class="btn btn-link btn-success btn-lg"><i class="fa fa-eye"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- End Card Body -->
            </div> <!-- End Card -->
        </div> <!-- End col-md-12 -->
    </div> <!-- End page-inner -->
</div> <!-- End container -->