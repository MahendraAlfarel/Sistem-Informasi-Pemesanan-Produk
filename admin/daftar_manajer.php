<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'manajer') {
    // Jika bukan level manajer
    header("Location: index.php");
    exit;
}
// Proses Hapus manajer
if (isset($_GET['hapus'])) {
    $id_manajer = $_GET['hapus'];
    $con->query("DELETE FROM tb_manajer WHERE id_manajer='$id_manajer'");
    $_SESSION['status'] = "delete_success";
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
              <h3 class="fw-bold mb-3">Daftar Manajer</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarmanajer">Daftar Manajer</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                      <h4 class="card-title">Data Manajer</h4>
                      <a class="btn btn-primary btn-round ms-auto" href="index.php?halaman=tambahmanajer">
                        <i class="fa fa-plus"></i>
                        Tambah Manajer
                      </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                              <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Nomor Headphone</th>
                                <th>Email</th>
                                <th style="width: 10%">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $ambil=$con->query("SELECT * FROM tb_manajer");
                                while ($manajer = $ambil -> fetch_assoc()) { 
                                $id_manajer = $manajer['id_manajer']; ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $manajer['nama_manajer']; ?></td>
                                    <td><?= $manajer['headphone_manajer']; ?></td>
                                    <td><?= $manajer['email_manajer']; ?></td>
                                    <td>
                                    <div class="form-button-action">
                                        <a href="index.php?halaman=editmanajer&id=<?php echo $manajer['id_manajer']; ?>" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></a>
                                        <a href="index.php?halaman=detailmanajer&id=<?php echo $manajer['id_manajer']; ?>"class="btn btn-link btn-success btn-lg"><i class="fa fa-eye"></i></a>
                                        <!-- <button type="button" class="btn btn-link btn-danger btn-delete"data-id="<?= $id_manajer; ?>">
                                                    <i class="fa fa-times"></i>
                                                </button> -->
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