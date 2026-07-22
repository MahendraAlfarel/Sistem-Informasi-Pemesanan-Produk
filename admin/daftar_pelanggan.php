<div class="container">
    <div class="page-inner">
        <div class="page-header">
              <h3 class="fw-bold mb-3">Daftar Pelanggan</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Daftar Pelanggan</a></li>
            </ul>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                      <h4 class="card-title">Data Pelanggan</h4>
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
                                $ambil=$con->query("SELECT * FROM tb_pelanggan");
                                while ($pelanggan = $ambil -> fetch_assoc()) { 
                                $id_pelanggan = $pelanggan['id_pelanggan']; ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $pelanggan['nama_pelanggan']; ?></td>
                                    <td><?= $pelanggan['headphone_pelanggan']; ?></td>
                                    <td><?= $pelanggan['email_pelanggan']; ?></td>
                                    <td>
                                    <div class="form-button-action">
                                        <a href="index.php?halaman=detailpelanggan&id=<?php echo $pelanggan['id_pelanggan']; ?>" class="btn btn-link btn-success btn-lg"><i class="fa fa-eye"></i></a>
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