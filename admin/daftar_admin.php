<?php
if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'manajer') {
    // Jika bukan level manajer
    header("Location: index.php");
    exit;
}
// Proses Hapus admin
if (isset($_GET['hapus'])) {
    $id_admin = $_GET['hapus'];
    $con->query("DELETE FROM tb_admin WHERE id_admin='$id_admin'");
    $_SESSION['status'] = "delete_success";
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
              <h3 class="fw-bold mb-3">Daftar Admin</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftaradmin">Daftar Admin</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                      <h4 class="card-title">Data Admin</h4>
                      <a class="btn btn-primary btn-round ms-auto" href="index.php?halaman=tambahadmin">
                        <i class="fa fa-plus"></i>
                        Tambah Admin
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
                                $ambil=$con->query("SELECT * FROM tb_admin");
                                while ($admin = $ambil -> fetch_assoc()) { 
                                $id_admin = $admin['id_admin']; ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $admin['nama_admin']; ?></td>
                                    <td><?= $admin['headphone_admin']; ?></td>
                                    <td><?= $admin['email_admin']; ?></td>
                                    <td>
                                    <div class="form-button-action">
                                        <a href="index.php?halaman=editadmin&id=<?php echo $admin['id_admin']; ?>" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></a>
                                        <a href="index.php?halaman=detailadmin&id=<?php echo $admin['id_admin']; ?>"class="btn btn-link btn-success btn-lg"><i class="fa fa-eye"></i></a>
                                        <!-- <button type="button" class="btn btn-link btn-danger btn-delete"data-id="<?= $id_admin; ?>">
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