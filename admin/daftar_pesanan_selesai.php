<?php
// Proses Edit Status Pesanan
if (isset($_POST['update'])) {
    $id_pesanan = trim($_POST['id_pesanan']);
    $status_pesanan = trim($_POST['status_pesanan']);
    $query = $con->prepare("UPDATE tb_pesanan SET status_pesanan = ? WHERE id_pesanan = ?");
    $query->bind_param("si", $status_pesanan, $id_pesanan);
    if($query->execute()){
        $_SESSION['status'] = "update_success";
        $query->close();
    } else {
        $_SESSION['status'] = "update_failed";
        $query->close();
    }
}
?>


<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Pesanan Selesai</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarkategori">Daftar Pesanan Selesai</a></li>
            </ul>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Pesanan Selesai</h4>
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
                                $ambil = $con->query("SELECT * FROM tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan JOIN tb_pelanggan ON tb_pesanan.id_pelanggan = tb_pelanggan.id_pelanggan WHERE status_pesanan = 'Selesai' ORDER BY tb_pesanan.id_pesanan ASC");
                                while ($pesanan = $ambil->fetch_assoc()) { 
                                    $id_pesanan = $pesanan['id_pesanan']; ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($pesanan['no_invoice']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['no_po']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['nama_pelanggan']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['headphone_pelanggan']); ?></td>
                                        <td><?= htmlspecialchars($pesanan['tgl_pemesanan']); ?></td>
                                        <td style="text-align: center;">
                                            <div class="btn-group dropdown">
                                                <button class="btn btn-success btn-sm btn-round" type="button" data-bs-toggle="dropdown">
                                                    <?= htmlspecialchars($pesanan['status_pesanan']); ?>
                                                </button>
                                            </div>
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