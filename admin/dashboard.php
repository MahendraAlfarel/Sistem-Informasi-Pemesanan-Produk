 <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Selamat Datang Admin PT Indo Kimia Abadi</h6>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small">
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                        <?php $totalpelanggan = $con->query("SELECT COUNT(*) AS total_pelanggan FROM tb_pelanggan")->fetch_assoc()['total_pelanggan']; ?>
                          <p class="card-category">Total Pelanggan</p>
                          <h4 class="card-title"><?= $totalpelanggan; ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small" style="background-color: grey;"
                        >
                          <i class="far fa-user"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <?php $totaladmin = $con->query("SELECT COUNT(*) AS total_admin FROM tb_admin")->fetch_assoc()['total_admin']; ?>
                          <p class="card-category">Total Admin</p>
                          <h4 class="card-title"><?= $totaladmin; ?>  </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small" style="background-color: black;"
                        >
                          <i class="fas fa-box"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <?php $totalproduk = $con->query("SELECT COUNT(*) AS total_produk FROM tb_produk")->fetch_assoc()['total_produk']; ?>
                          <p class="card-category">Total Produk</p>
                          <h4 class="card-title"><?= $totalproduk; ?>  </h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        > <!-- kalou mau ubah warna customer tambah ini   style="background-color: red -->
                          <i class="fas fa-clipboard-list"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <?php $totalpesanan = $con->query("SELECT COUNT(*) AS total_pesanan FROM tb_pesanan")->fetch_assoc()['total_pesanan']; ?>
                          <p class="card-category">Total Pesanan</p>
                          <h4 class="card-title"><?= $totalpesanan ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-danger bubble-shadow-small"
                        >
                          <i class="fas fa-cart-plus"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <?php $totalbaru = $con->query("SELECT COUNT(*) AS total_pesanan FROM tb_pesanan WHERE status_pesanan = 'Menunggu Konfirmasi'")->fetch_assoc()['total_pesanan']; ?>
                          <p class="card-category">Pesanan Baru</p>
                          <h4 class="card-title"><?= $totalbaru ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-warning bubble-shadow-small"
                        >
                          <i class="fas fa-cogs"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <?php $totalproses = $con->query("SELECT COUNT(*) AS total_pesanan FROM tb_pesanan WHERE status_pesanan = 'Sedang Diproses'")->fetch_assoc()['total_pesanan']; ?>
                          <p class="card-category">Pesanan Diproses</p>
                          <h4 class="card-title"><?= $totalproses ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-secondary bubble-shadow-small"
                        >
                          <i class="fas fa-shipping-fast"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <?php $totalkirim = $con->query("SELECT COUNT(*) AS total_pesanan FROM tb_pesanan WHERE status_pesanan = 'Sedang Dikirim'")->fetch_assoc()['total_pesanan']; ?>
                          <p class="card-category">Pesanan Dikirim</p>
                          <h4 class="card-title"><?= $totalkirim ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-success bubble-shadow-small"
                        >
                          <i class="far fa-check-circle"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <?php $totalselesai = $con->query("SELECT COUNT(*) AS total_pesanan FROM tb_pesanan WHERE status_pesanan = 'Selesai'")->fetch_assoc()['total_pesanan']; ?>
                          <p class="card-category">Pesanan Selesai</p>
                          <h4 class="card-title"><?= $totalselesai ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="card card-round">
                  <div class="card-body">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Customers Baru</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="index.php?halaman=daftarpelanggan">Daftar Pelanggan</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-list py-4">
                      <?php foreach ($pelanggan_baru as $pelanggan): ?>
                      <div class="item-list">
                        <div class="avatar">
                          <span class="avatar-title rounded-circle bg-<?= getRandomColorClass(); ?>">
                            <?= getInitials($pelanggan['nama_pelanggan']); ?>
                          </span>
                        </div>
                        <div class="info-user ms-3">
                          <div class="username"><?= ($pelanggan['nama_pelanggan']); ?></div>
                          <div class="status"><?= ($pelanggan['email_pelanggan']); ?></div>
                        </div>
                        <a href="index.php?halaman=detailpelanggan&id=<?php echo $pelanggan['id_pelanggan']; ?>" class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-eye"></i>
                        </a>
                      </div>
                    <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Transaksi Baru</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="index.php?halaman=daftarpesanan">Daftar Transaksi</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <!-- Projects table -->
                      <table class="table align-items-center mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">Nomor Pesanan</th>
                            <th scope="col" class="text-cebter">Tanggal Pre-Order</th>
                            <th scope="col" class="text-center">Total Transaksi</th>
                            <th scope="col" class="text-center">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pesanan_baru as $pesanan): 
                          $status = $pesanan['status_pesanan'];
                          $status_warna = '';
                          switch($status) {
                              case 'Menunggu Pembayaran':
                                  $status_warna = 'badge-danger';
                                  break;
                              case 'Menunggu Konfirmasi':
                                  $status_warna = 'badge-warning';
                                  break;
                              case 'Sedang Diproses':
                                  $status_warna = 'badge-primary';
                                  break;
                              case 'Sedang Dikirim':
                                  $status_warna = 'badge-info';
                                  break;
                              case 'Selesai':
                                  $status_warna = 'badge-success';
                                  break;
                              case 'Dibatalkan';
                                  $status_warna = 'badge-danger';
                                  break;
                              default:
                                  $status_warna = 'badge-dark';
                                  break;
                          }
                          ?>
                          <tr>
                            <th scope="row">
                            <?= ($pesanan['no_po']); ?>
                            </th>
                            <td class="text-center"><?= ($pesanan['tgl_pemesanan']); ?></td>
                            <td class="text-center">Rp. <?=  number_format($pesanan['total_harga'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                              <span class="badge <?= $status_warna ?>"><?= ($pesanan['status_pesanan']); ?></span>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>