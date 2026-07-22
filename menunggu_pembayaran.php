<?php
// Cek apakah user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu untuk mengakses halaman ini.');
        window.location.href = 'login.php';
    </script>";
    exit();
}

$id = $_SESSION['id_pelanggan'];
$pesanan = getMenungguPembayaran($id);
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

    <!-- Load fonts style after rendering the layout styles -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="Your_Midtrans_Client_Key_Sandbox"></script>
    <!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Start Daftar Pesanan -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-normal mb-0 text-black">Pesanan Yang Belum Dibayar</h3>
    </div>
    <hr>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align: center;">No Invoice</th>
                            <th scope="col" style="text-align: center;">No Pre-Order</th>
                            <th scope="col" style="text-align: center;">Total Harga</th>
                            <th scope="col" style="text-align: center;">Tanggal Pemesanan</th>
                            <th scope="col" style="text-align: center;">Status Pesanan</th>
                            <th scope="col" style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pesanan)): ?>
                            <?php foreach ($pesanan as $menungguPembayaran):
                                $invoice_id = $menungguPembayaran['id_invoice'];
                                $no_invoice = htmlspecialchars($menungguPembayaran['no_invoice']);
                                $no_po = htmlspecialchars($menungguPembayaran['no_po']);
                                $tgl_po = htmlspecialchars($menungguPembayaran['tgl_pemesanan']);
                                $totalHarga = number_format($menungguPembayaran['total_tagihan'], 0, ',', '.');
                                $status = htmlspecialchars($menungguPembayaran['status_pesanan']); ?>
                                <tr>
                                    <td><?= $no_invoice ?></td>
                                    <td><?= $no_po ?></td>
                                    <td style="text-align: end;">Rp. <?= $totalHarga ?></td>
                                    <td style="text-align: center;"><?= $tgl_po ?></td>
                                    <td style="text-align: center;" align-content="center">
                                        <button class="btn <?= getWarnaStatus($status) ?> btn-sm btn-round">
                                            <?= $status ?>
                                        </button>
                                    </td>
                                    <td style="text-align: center;">
                                        <div class="d-grid gap-2">
                                            <a href="profil.php?daftar_pesanan=detail_pesanan&id=<?= $invoice_id ?>" class="btn btn-outline-secondary btn-sm">
                                                Lihat
                                            </a>
                                            <button class="btn btn-outline-primary btn-bayar" id="btn-bayar" data-id="<?= $invoice_id ?>">Bayar</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-black" style="text-align: center;">Tidak Ada Pesanan Menunggu Pembayaran.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Close Daftar Pesanan -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).on('click', '.btn-bayar', function() {
            var id_invoice_clicked = $(this).data('id'); // ID atau Nomor Invoice dari tombol

            // AJAX pertama: Meminta token pembayaran dari backend (midtrans_transaksi.php)
            $.ajax({
                url: 'pembayaran.php', // Skrip ini untuk mendapatkan token Midtrans
                method: 'POST',
                data: {
                    id: id_invoice_clicked
                }, // Kirim ID invoice ke backend untuk dibuatkan token
                dataType: 'json',
                success: function(response) {
                    if (response.token) {
                        // Jika token berhasil didapatkan, tampilkan popup pembayaran Midtrans
                        snap.pay(response.token, {
                            onSuccess: function(result) {
                                // --- MIDTRANS PEMBAYARAN BERHASIL ---
                                // Tampilkan SweetAlert untuk konfirmasi ke pengguna
                                Swal.fire({
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih, pembayaran kamu telah berhasil.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((res) => {
                                    if (res.isConfirmed) {
                                        // --- PENGGUNA KLIK OK PADA SWEETALERT ---
                                        // Panggil AJAX untuk UPDATE STATUS DI DATABASE ANDA SECARA INTERNAL
                                        $.ajax({
                                            url: 'proses_pembayaran.php', // Ganti dengan nama file PHP Anda
                                            method: 'POST',
                                            data: {
                                                no_invoice: id_invoice_clicked, // Kirim ID/Nomor Invoice yang sama ke backend
                                                status: 'Sudah Dibayar' // Status yang ingin Anda set secara internal
                                            },
                                            dataType: 'json',
                                            success: function(updateResponse) {
                                                if (updateResponse.success) {
                                                    // Jika update status berhasil, langsung redirect
                                                    window.location.href = 'profil.php?daftar_pesanan=true'; // Ganti dengan URL halaman daftar pesanan Anda
                                                } else {
                                                    // Jika update status gagal, tampilkan error
                                                    Swal.fire('Error Update Status!', 'Gagal memperbarui status di database: ' + updateResponse.error, 'error');
                                                    console.error('Error updating status:', updateResponse.error);
                                                    // Anda bisa memilih untuk merefresh atau tidak di sini
                                                    // location.reload(); 
                                                }
                                            },
                                            error: function(xhr, status, error) {
                                                Swal.fire('Error Koneksi!', 'Tidak dapat terhubung ke server untuk memperbarui status.', 'error');
                                                console.error('AJAX Error:', status, error);
                                                // Anda bisa memilih untuk merefresh atau tidak di sini
                                                // location.reload();
                                            }
                                        });
                                    }
                                });
                            },
                            onPending: function(result) {
                                // Pembayaran masih menunggu konfirmasi dari Midtrans
                                Swal.fire({
                                    title: 'Menunggu Pembayaran!',
                                    text: 'Pembayaran Anda sedang dalam proses verifikasi Midtrans. Harap selesaikan pembayaran.',
                                    icon: 'info',
                                    confirmButtonText: 'OK'
                                }).then((res) => {
                                    if (res.isConfirmed) {
                                        location.reload(); // Refresh halaman
                                    }
                                });
                            },
                            onError: function(result) {
                                // Pembayaran gagal di Midtrans
                                Swal.fire({
                                    title: 'Pembayaran Gagal!',
                                    text: 'Terjadi kesalahan saat memproses pembayaran Anda melalui Midtrans. Silakan coba lagi.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            },
                            onClose: function() {
                                // Pengguna menutup popup pembayaran Midtrans tanpa menyelesaikan
                                Swal.fire({
                                    title: 'Pembayaran Dibatalkan!',
                                    text: 'Anda menutup jendela pembayaran Midtrans.',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    } else {
                        Swal.fire('Error!', 'Gagal mendapatkan token pembayaran: ' + response.error, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error Koneksi!', 'Tidak dapat terhubung ke server untuk memulai pembayaran.', 'error');
                    console.error('AJAX Error:', status, error);
                }
            });
        });
    </script>

</body>

</html>