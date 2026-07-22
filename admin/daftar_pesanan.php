<?php
$date_now = date('Y-m-d');
$con->query("UPDATE tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan SET tb_pesanan.status_pesanan = 'Dibatalkan' WHERE tb_invoice.jatuh_tempo < '$date_now' AND tb_pesanan.status_pesanan = 'Menunggu Pembayaran'");

$con->query("UPDATE tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan SET tb_invoice.status_invoice = 'Kedaluwarsa' WHERE tb_invoice.jatuh_tempo < '$date_now' AND tb_invoice.status_invoice = 'Belum Bayar'");

// Proses Edit Status Pesanan
if (isset($_POST['update'])) {
    $id_pesanan = trim($_POST['id_pesanan']);
    $status_pesanan = trim($_POST['status_pesanan']);
    $id_admin = $_SESSION['id_admin'];

    $cek = $con->prepare("SELECT status_pesanan FROM tb_pesanan WHERE id_pesanan = ?");
    $cek->bind_param("i", $id_pesanan);
    $cek->execute();
    $hasil = $cek->get_result();
    $data = $hasil->fetch_assoc();
    $status = $data['status_pesanan'];
    $cek->close();

 // Update status pesanan
    $update_pesanan = $con->prepare("UPDATE tb_pesanan SET status_pesanan = ?, id_admin = ? WHERE id_pesanan = ?");
    $update_pesanan->bind_param("sii", $status_pesanan, $id_admin, $id_pesanan);

    if ($update_pesanan->execute()) {
        $update_pesanan->close();

        // Jika status baru adalah Dibatalkan
        if ($status_pesanan == 'Dibatalkan') {
            // Ambil ID Invoice berdasarkan ID Pesanan
            $stmt = $con->prepare("SELECT id_invoice FROM tb_invoice WHERE id_pesanan = ?");
            $stmt->bind_param("i", $id_pesanan);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id_invoice = $row['id_invoice'];

                // Update status invoice ke Dibatalkan
                $update_invoice = $con->prepare("UPDATE tb_invoice SET status_invoice = 'Dibatalkan' WHERE id_invoice = ?");
                $update_invoice->bind_param("i", $id_invoice);
                $update_invoice->execute();
                $update_invoice->close();

                // Jika status sebelumnya adalah salah satu dari ini, update juga transaksi
                $status_skrng = ['Menunggu Konfirmasi', 'Sedang Diproses', 'Sedang Dikirim'];

                if (in_array($status, $status_skrng)) {
                    $update_transaksi = $con->prepare("UPDATE tb_transaksi SET status_transaksi = 'Dibatalkan' WHERE id_invoice = ?");
                    $update_transaksi->bind_param("i", $id_invoice);
                    $update_transaksi->execute();
                    $update_transaksi->close();
                }
            }

            $stmt->close();
        }

        $_SESSION['status'] = "update_success";
    } else {
        $update_pesanan->close();
        $_SESSION['status'] = "update_failed";
    }
}
?>


<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Pesanan</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarkategori">Daftar Pesanan</a></li>
            </ul>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Pesanan</h4>
                    </div>
                </div>
                
                <div class="card-body">

                    <!-- Tabel -->
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No Invoice</th>
                                    <th>No Pesanan</th>
                                    <th>Nama Pemesanan</th>
                                    <th>Nomer Headphone Pemesanan</th>
                                    <th>Tanggal Pre Order</th>
                                    <th>Status Pesanan</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $ambil = $con->query("SELECT * FROM tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan JOIN tb_pelanggan ON tb_pesanan.id_pelanggan = tb_pelanggan.id_pelanggan ORDER BY tb_pesanan.id_pesanan DESC");
                                while ($pesanan = $ambil->fetch_assoc()) { 
                                    $id_pesanan = $pesanan['id_pesanan']; 
                                    $status = $pesanan['status_pesanan'];
                                    $status_warna = '';
                                    switch($status) {
                                        case 'Menunggu Pembayaran':
                                            $status_warna = 'btn-warning';
                                            break;
                                        case 'Menunggu Konfirmasi':
                                            $status_warna = 'btn-info';
                                            break;
                                        case 'Sedang Diproses':
                                            $status_warna = 'btn-secondary';
                                            break;
                                        case 'Sedang Dikirim':
                                            $status_warna = 'btn-primary';
                                            break;
                                        case 'Selesai':
                                            $status_warna = 'btn-success';
                                            break;
                                        case 'Dibatalkan';
                                            $status_warna = 'btn-danger';
                                            break;
                                        default:
                                            $status_warna = 'btn-dark';
                                            break;
                                    }?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($pesanan['no_invoice']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['no_po']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['nama_pelanggan']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['headphone_pelanggan']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['tgl_pemesanan']); ?></td>
                                        <td style="text-align: center;">
                                            <?php if ($_SESSION['level'] === 'admin'): ?>
                                                <!-- Versi Admin: bisa ubah status -->
                                                <div class="btn-group dropdown">
                                                    <button class="btn btn-sm btn-round <?= $status_warna ?> dropdown-toggle" 
                                                            type="button" data-bs-toggle="dropdown">
                                                        <?= htmlspecialchars($pesanan['status_pesanan']); ?>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php $next_status = [];
                                                        switch ($status) {
                                                            case 'Menunggu Pembayaran':
                                                                $next_status = ['Dibatalkan'];
                                                                break;
                                                            case 'Menunggu Konfirmasi':
                                                                $next_status = ['Sedang Diproses', 'Dibatalkan'];
                                                                break;
                                                            case 'Sedang Diproses':
                                                                $next_status = ['Sedang Dikirim', 'Dibatalkan'];
                                                                break;
                                                            case 'Sedang Dikirim':
                                                                $next_status = ['Selesai', 'Dibatalkan'];
                                                                break;
                                                            default:
                                                                $next_status = [];
                                                                break;
                                                        }
                                                        foreach ($next_status as $new_status): ?>
                                                            <li>
                                                                <form method="POST" action="">
                                                                    <input type="hidden" name="id_pesanan" value="<?= htmlspecialchars($id_pesanan); ?>">
                                                                    <input type="hidden" name="status_pesanan" value="<?= $new_status; ?>">
                                                                    <button type="submit" name="update" class="dropdown-item">
                                                                        <?= $new_status; ?>
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>

                                            <?php elseif ($_SESSION['level'] === 'manajer'): ?>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-round <?= $status_warna ?>" type="button">
                                                        <?= htmlspecialchars($pesanan['status_pesanan']); ?>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="form-button-action">
                                                <a href="index.php?halaman=detailpesanan&id=<?php echo $pesanan['id_invoice']; ?>"class="btn btn-link btn-success btn-lg"><i class="fa fa-eye"></i></a>
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