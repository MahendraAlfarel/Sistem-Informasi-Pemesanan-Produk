<?php
session_start();
include 'koneksi.php';
include './functions/functions.php';

$keranjang = getKeranjang();

// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

if(isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id_produk'])) {
  $produk_id = htmlspecialchars($_GET['id_produk']);
  if(hapusProdukKeranjang($produk_id)) {
    $_SESSION['notif'] = 'hapus-produk-berhasil';
  } else {
      $_SESSION['notif'] = 'hapus-produk-gagal';
  }
  header('Location: keranjang.php');
  exit;
}

if (isset($_POST['update_keranjang'])) {
    foreach ($_POST['jumlah'] as $produk_id => $jumlah) {
        $jumlah = (int) $jumlah;

        if ($jumlah > 0) {
            $_SESSION['keranjang'][$produk_id] = $jumlah;
        } elseif ($jumlah === 0) {
            // Hapus item jika jumlah 0
            unset($_SESSION['keranjang'][$produk_id]);
        } else {
            // Tidak boleh negatif, abaikan update dan beri notifikasi
            $_SESSION['notif'] = 'keranjang-error-negatif';
            header('Location: keranjang.php');
            exit;
        }
    }

    // Jika semua berhasil
    $_SESSION['notif'] = 'keranjang-update';
    header('Location: keranjang.php');
    exit;
}
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
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Header -->
    <?php include 'includes/header.php';?>
    <!-- Close Header -->

    <!-- Start Content Page -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1 text-black">Keranjang Belanja</h1>
        </div>
    </div>

    <!-- Start Content -->
    <div class="site-section">
        <div class="container">
          <div class="row mb-5">
            <form class="col-md-12" method="post">
            <div class="site-blocks-table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Gambar</th>
                    <th class="product-name">Nama Produk</th>
                    <th class="product-price">Harga</th>
                    <th class="product-quantity">QTY</th>
                    <th class="product-total">Total Harga</th>
                    <th class="product-remove">Hapus</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(empty($keranjang)) {
                    echo "<tr><td colspan='6' class='text-center text-black'> Keranjang Kosong </td></tr>";
                  } else {
                    foreach($keranjang as $produk):
                      $produk_id = $produk['id_produk'];
                      $produk_nama = htmlspecialchars($produk['nama_produk']);
                      $produk_harga = number_format($produk['harga_produk'], 0, ',', '.');
                      $produk_foto = htmlspecialchars($produk['foto_produk']);
                      $jumlah = htmlspecialchars((int)$produk['jumlah']);
                      $subharga = number_format($produk['subharga'], 0, ',', '.'); ?>
                  <tr>
                    <td class="product-thumbnail">
                        <img src="images/foto_produk/<?= $produk_foto ?>" alt="Image" class="img-fluid"  width="80">
                    </td>
                    <td class="product-name">
                        <h5 class="text-black text-start"><?= $produk_nama ?></h5>
                    </td>
                    <td>Rp. <?= $produk_harga ?></td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center align-items-center gap-1">
                            <button class="btn qty-btn js-btn-minus px-2 py-1" type="button">&minus;</button>
                            <input type="number" name="jumlah[<?= $produk_id ?>]" class="form-control text-center qty-input" value="<?= $jumlah ?>" style="width: 60px;">
                            <button class="btn qty-btn js-btn-plus px-2 py-1" type="button">&plus;</button>
                        </div>
                    </td>
                    <td>Rp. <?= $subharga ?></td>
                    <td><button class="btn btn-danger btn-sm btn-hapus" data-id="<?= $produk_id ?>">X</button></td>
                </tr>
                <?php endforeach; } ?>
                </tbody>
              </table>
            </div>
          </div>
  
          <div class="row">
            <div class="col-md-6">
              <div class="d-flex gap-3 mb-5">
                  <button type="submit" name="update_keranjang" class="btn btn-primary btn-sm btn-block" id="btn-update-keranjang">Update keranjang</button>
                  <button type="button" class="btn btn-outline-primary btn-sm btn-block" id="btn-lanjut">Lanjut Belanja</button>
              </div>
            </div>
            </form>
            <div class="col-md-6 pl-5">
              <div class="row justify-content-end">
                <div class="col-md-7">
                  <div class="row">
                    <div class="col-md-12 text-right border-bottom mb-5">
                      <h3 class="text-black h4 text-uppercase">Total Keranjang</h3>
                    </div>
                  </div>
                  <?php $totalHarga = getTotalHarga(); ?>
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <span class="text-black">Sub total</span>
                    </div>
                    <div class="col-md-6 text-right">
                      <strong class="text-black">Rp. <?= number_format($totalHarga, 0, ',', '.'); ?>
                    </div>
                  </div>
  
                  <div class="row">
                    <div class="col-md-12">
                      <button class="btn btn-primary btn-lg py-3 btn-block" name="btn-checkout" id="btn-checkout">Checkout</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- End Content -->

    <!-- Start Footer -->
    <?php include 'includes/footer.php';?>
    <!-- End Footer -->

    <div id="spinner-overlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-btn-minus').forEach(function(btn) {
                    btn.addEventListener('click', function () {    
                    const input = btn.parentElement.querySelector('.qty-input');
                    let value = parseInt(input.value) || 1;
                    if (value > 1) input.value = value - 1;
                });
            });

            document.querySelectorAll('.js-btn-plus').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const input = btn.parentElement.querySelector('.qty-input');
                    let value = parseInt(input.value) || 1;
                    input.value = value + 1;
                });
            });
        });
    </script>

    <script>
        const button = document.getElementById('btn-lanjut');

        button.addEventListener('click', function() {
            window.location.href = 'shop.php';
        });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const lanjutBtn = document.getElementById('btn-update-keranjang');
        const isEmpty = <?= empty($_SESSION['keranjang']) ? 'true' : 'false'; ?>;
        if(isEmpty) {
          lanjutBtn.disabled = true;
        }
      });
    </script>

  <script>
      document.addEventListener("DOMContentLoaded", function() {
        const lanjutBtn = document.getElementById('btn-checkout');
        const isEmpty = <?= empty($_SESSION['keranjang']) ? 'true' : 'false'; ?>;
        if(isEmpty) {
          lanjutBtn.disabled = true;
        }
      });
    </script>

    <!-- Start Button Checkout Disabled And Spinner Load Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const buyNowBtn = document.querySelector("button[name='btn-checkout']");
            const spinner = document.getElementById("spinner-overlay");

            if (buyNowBtn && form) {
                buyNowBtn.addEventListener("click", function() {
                    spinner.classList.add('show');

                    setTimeout(() => {
                      window.location.href = 'checkout.php';
                    }, 250);
                });
            }
        });

        window.addEventListener("pageshow", function(event) {
          if (event.persisted) {
              const spinner = document.getElementById("spinner-overlay");
              if (spinner) spinner.classList.remove('show');
          }
      });
    </script>
    <!-- End Spinner Load Script -->


    <script>
      document.querySelectorAll('.btn-hapus').forEach(function(button) {
        button.addEventListener('click', function(event) {
          event.preventDefault();
          const id = this.dataset.id;

          Swal.fire({
            title: 'Info',
            text: 'Yakin ingin menghapus produk ini',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                cancelButton: 'btn btn-danger mx-2',
                confirmButton: 'btn btn-primary'},
          }).then((result) => {
            if(result.isConfirmed) {
              window.location.href = 'keranjang.php?aksi=hapus&id_produk=' + id;
            }
          });
        });
      });
    </script>

  <?php if (isset($_SESSION['notif'])): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      <?php if ($_SESSION['notif'] === 'hapus-produk-berhasil'): ?>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Produk berhasil dihapus dari keranjang'
        });
      <?php elseif ($_SESSION['notif'] === 'hapus-produk-gagal'): ?>
        Swal.fire({
          icon: 'error',
          title: 'Gagal',
          text: 'Produk gagal dihapus. Silakan coba lagi'
        });
      <?php elseif ($_SESSION['notif'] === 'keranjang-update'): ?>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Keranjang berhasil diperbarui'
        });
      <?php elseif ($_SESSION['notif'] === 'keranjang-error-negatif'): ?>
        Swal.fire({
          icon: 'error',
          title: 'Jumlah Tidak Valid',
          text: 'Jumlah produk tidak boleh kurang dari 1'
        });
      <?php endif; ?>
      <?php unset($_SESSION['notif']); ?>
    });
  </script>
  <?php endif; ?>
    <!-- End Script -->
</body>

</html>