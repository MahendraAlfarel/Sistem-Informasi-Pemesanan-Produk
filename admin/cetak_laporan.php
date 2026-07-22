<?php
include '../koneksi.php';

$dari = isset($_GET['dari']) ? $_GET['dari'] : '';
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : '';
$hasil = [];

function isValidDate($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date);
}

if ($dari && $sampai && isValidDate($dari) && isValidDate($sampai)) {
    if (strtotime($dari) <= strtotime($sampai)) {
        $stmt = $con->prepare("SELECT * FROM tb_transaksi 
                               JOIN tb_invoice ON tb_transaksi.id_invoice = tb_invoice.id_invoice 
                               JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan 
                               JOIN tb_pelanggan ON tb_pesanan.id_pelanggan = tb_pelanggan.id_pelanggan 
                               WHERE tb_transaksi.tgl_bayar BETWEEN ? AND ? AND status_transaksi = 'Berhasil'
                               ORDER BY tb_transaksi.id_transaksi");
        $stmt->bind_param("ss", $dari, $sampai);
        $stmt->execute();
        $hasil = $stmt->get_result();
    } else {
        echo "<script>alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.'); window.location='index.php?halaman=daftarlaporan';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PT Indo Kimia Abadi - Laporan Transaksi</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon" />
    <style>
        /* Variabel warna biru primary Bootstrap */
        :root {
            --primary-blue: #0d6efd;
        }

        body { 
            font-family: Arial, sans-serif;
            margin: 0; 
            padding: 20px; /* Tambahkan padding umum untuk konten */
            position: relative; /* Penting: Jadikan body sebagai acuan untuk positioning absolut */
            min-height: 100vh; /* Agar body cukup tinggi untuk posisi absolut */
        }
        
        /* Gaya untuk logo dan informasi perusahaan */
        .company-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px; 
            /* Perubahan di sini: Tebalkan border-bottom */
            border-bottom: 3px solid #000; /* Dulu 2px solid #ccc, sekarang 3px solid #000 (hitam tebal) */
            padding-bottom: 10px;
            padding-left: 20px; /* Sesuaikan dengan padding body */
            padding-top: 10px; /* Sedikit padding atas */
        }
        .company-header img {
            max-width: 80px; 
            height: auto;
            margin-right: 15px;
        }
        .company-info-text {
            line-height: 1.3;
        }
        .company-info-text .company-name {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-blue);
        }
        .company-info-text span {
            font-size: 14px;
            color: #555;
        }

        /* Container baru untuk Laporan Transaksi dan Periode */
        .report-title-container {
            position: absolute; /* Posisikan secara absolut */
            top: 40px; /* Sesuaikan jarak dari atas */
            right: 40px; /* Sesuaikan jarak dari kanan */
            text-align: right;
            line-height: 1.2;
        }

        .report-title-container h2 { 
            font-size: 28px; /* Ukuran font lebih besar */
            font-weight: bold;
            color: #000; /* Warna tetap hitam */
            margin: 0; /* Hapus margin default h2 */
            text-align: right; /* Pastikan rata kanan */
        }

        .report-title-container p.periode {
            font-size: 16px; /* Ukuran font lebih besar */
            color: #333;
            margin-top: 5px; /* Jarak dari judul laporan */
            margin-bottom: 0; /* Hapus margin default p */
            text-align: right; /* Pastikan rata kanan */
        }
        
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-bottom: 20px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: left; 
            font-size: 13px; 
        }
        
        th { 
            background-color: var(--primary-blue); 
            color: #fff; 
            border-color: #000; 
            text-align: center; 
        }

        /* Penyesuaian untuk kolom angka */
        td:nth-child(5),
        td:nth-child(7) { 
            text-align: right;
        }
        td:nth-child(3), 
        td:nth-child(6) { 
            text-align: center;
        }
        
        tfoot td {
            font-size: 13px; 
            border-top: 2px solid #000; 
        }
        /* Perubahan di sini: Ubah warna teks total transaksi menjadi hitam */
        tfoot td:last-child {
            color: #000; /* Dulu var(--primary-blue), sekarang #000 */
            font-size: 13px;
            text-align: right;
            font-weight: bold; /* Tambahkan bold agar menonjol */
        }

        /* Gaya khusus untuk media cetak */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                padding: 10mm; /* Atur padding untuk cetak agar tidak terlalu pinggir */
                font-size: 10pt; 
                position: relative; /* Penting untuk cetak juga */
            }
            .company-header {
                margin-bottom: 15px; 
                /* Perubahan di sini: Tebalkan border-bottom saat cetak juga */
                border-bottom: 2px solid #000 !important; /* Dulu 1px solid #ddd, sekarang 2px solid #000 */
                padding-bottom: 5px;
                padding-left: 10mm; /* Sesuaikan dengan padding body di print */
                padding-top: 5mm;
            }
            .company-header img {
                max-width: 60px; 
            }
            .company-info-text .company-name {
                font-size: 18px;
                color: var(--primary-blue) !important; 
            }
            .company-info-text span {
                font-size: 11px;
                color: #555 !important;
            }

            /* Penyesuaian untuk cetak report-title-container */
            .report-title-container {
                top: 20mm; /* Sesuaikan jarak dari atas saat dicetak */
                right: 10mm; /* Sesuaikan jarak dari kanan saat dicetak (sama dengan padding body) */
                font-size: 10pt; /* Ukuran font lebih kecil untuk cetak */
            }
            .report-title-container h2 {
                font-size: 22px; /* Ukuran font judul laporan saat cetak */
                color: #000 !important; /* Pastikan tetap hitam */
            }
            .report-title-container p.periode {
                font-size: 13px; /* Ukuran font periode saat cetak */
                color: #333 !important; /* Pastikan tetap abu-abu gelap */
            }
            
            table {
                margin-top: 80px; /* Beri ruang agar tidak tertutup oleh judul laporan saat dicetak */
                page-break-inside: auto;
            }
            th, td {
                font-size: 10px; 
                padding: 5px; 
            }
            th {
                background-color: var(--primary-blue) !important;
                color: #fff !important;
                border-color: #000 !important;
            }
            tfoot td {
                font-size: 12px; 
                border-top: 1px solid #000; 
            }
            /* Perubahan di sini: Ubah warna teks total transaksi menjadi hitam saat cetak */
            tfoot td:last-child {
                color: #000 !important; /* Dulu var(--primary-blue) !important, sekarang #000 !important */
                font-size: 10px;
                font-weight: bold; /* Tambahkan bold */
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>

    <div class="company-header">
        <img src="assets/img/favicon.svg" alt="Logo Perusahaan"> <div class="company-info-text">
            <div class="company-name">PT INDO KIMIA ABADI</div>
            <span>Kawasan Industri Millennium 11 B Blok F6 No.05-06</span><br>
            <span>Tigaraksa Panongan</span><br>
            <span>Telp : (021) 29007072</span><br>
            <span>Email : office@indokimiaabadi.com</span>
        </div>
    </div>

    <div class="report-title-container">
        <h2>Laporan Transaksi</h2>
        <p class="periode"><strong>Periode:</strong> <?= htmlspecialchars($dari) ?> Sampai <?= htmlspecialchars($sampai) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No Invoice</th>
                <th>No Pesanan</th>
                <th>Tanggal Pemesanan</th>
                <th>Nama Pelanggan</th>
                <th>Jumlah Dibayar</th>
                <th>Tanggal Bayar</th>
                <th>Jumlah Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($hasil) && $hasil->num_rows > 0): 
                $totalTransaksi = 0;
                while ($row = $hasil->fetch_assoc()): 
                $totalTransaksi += $row['jumlah_pembayaran'];?>
                    <tr>
                        <td><?= htmlspecialchars($row['no_invoice']); ?></td>
                        <td><?= htmlspecialchars($row['no_po']); ?></td>
                        <td><?= htmlspecialchars($row['tgl_pemesanan']); ?></td>
                        <td><?= htmlspecialchars($row['nama_pelanggan']); ?></td>
                        <td>Rp <?= number_format($row['total_tagihan'], 0, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($row['tgl_bayar']); ?></td>
                        <td>Rp <?= number_format($row['jumlah_pembayaran'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">Tidak ada data transaksi</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right;"><strong>Total Transaksi:</strong></td>
                <td colspan="3"><strong>Rp <?= number_format($totalTransaksi, 0, ',', '.'); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <script>
        window.print(); // Otomatis buka dialog print
    </script>

</body>
</html>