<?php
// Proses Hapus produk
if (isset($_GET['hapus'])) {
    $id_produk = $_GET['hapus'];
    $con->query("DELETE FROM tb_produk WHERE id_produk='$id_produk'");
    $_SESSION['status'] = "delete_success";
}
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
              <h3 class="fw-bold mb-3">Daftar Produk</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarproduk">Daftar Produk</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                      <h4 class="card-title">Data Produk</h4>
                      <a class="btn btn-primary btn-round ms-auto" href="index.php?halaman=tambahproduk">
                        <i class="fa fa-plus"></i>
                        Tambah Produk
                      </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                              <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Gambar</th>
                                <th style="width: 10%;">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $ambil=$con->query("SELECT * FROM tb_produk JOIN tb_kategori ON tb_produk.id_kategori = tb_kategori.id_kategori ORDER BY id_produk");
                                while ($produk = $ambil -> fetch_assoc()) { 
                                $id_produk = $produk['id_produk']; ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $produk['nama_produk']; ?></td>
                                    <td>Rp <?= number_format($produk['harga_produk'], 0, ',', '.'); ?></td>
                                    <td><?= $produk['nama_kategori']; ?></td>
                                    <td><img src="../images/foto_produk/<?php echo $produk['foto_produk']; ?>" width="100"></td>
                                    <td>
                                    <div class="form-button-action">
                                        <a href="index.php?halaman=editproduk&id=<?php echo $produk['id_produk']; ?>" class="btn btn-link btn-primary btn-lg"><i class="fa fa-edit"></i></a>
                                        <a href="index.php?halaman=detailproduk&id=<?php echo $produk['id_produk']; ?>"class="btn btn-link btn-success btn-lg"><i class="fa fa-eye"></i></a>
                                        <!-- <button type="button" class="btn btn-link btn-danger btn-delete"data-id="<?= $id_produk; ?>">
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