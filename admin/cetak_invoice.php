<?php
include '../koneksi.php';
include '../functions/functions.php';

$invoice_id = $_GET['id'] ?? '';
$pesanan = getPesananByInvoiceId($con, $invoice_id);

if (!$pesanan) {
    $_SESSION['status'] = 'id_invalid';
    header("Location: index.php?halaman=daftarpesanan");
    exit;
}

$status_pesanan = htmlspecialchars($pesanan['status_pesanan']);
$no_po = htmlspecialchars($pesanan['no_po']);
$no_invoice = htmlspecialchars($pesanan['no_invoice']);
$tgl_invoice = htmlspecialchars($pesanan['tgl_invoice']);
$tgl_pemesanan = htmlspecialchars($pesanan['tgl_pemesanan']);
$nama_pelanggan = htmlspecialchars($pesanan['nama_pelanggan']);
$headphone_pelanggan = htmlspecialchars($pesanan['headphone_pelanggan']);
$email_pelanggan = htmlspecialchars($pesanan['email_pelanggan']);
$nama_penerima = htmlspecialchars($pesanan['nama_penerima']);
$headphone_penerima = htmlspecialchars($pesanan['headphone_penerima']);
$alamat_penerima = htmlspecialchars($pesanan['alamat_penerima']);
$subtotal = number_format($pesanan['subtotal'], 0, ',', '.');
$total_ppn = number_format($pesanan['total_ppn'], 0, ',', '.');
$total_tagihan = number_format($pesanan['total_harga'], 0, ',', '.');
$provinsi_pelanggan = htmlspecialchars($pesanan['nama_provinsi']);
$kota_pelanggan = htmlspecialchars($pesanan['nama_kota']);
$kecamatan_pelanggan = htmlspecialchars($pesanan['nama_kecamatan']);
$kdps_pelanggan = htmlspecialchars($pesanan['kode_pos']);
$jalan_pelanggan = htmlspecialchars($pesanan['alamat_pelanggan']);
$alamat_pelanggan = "$jalan_pelanggan, $kecamatan_pelanggan, $kota_pelanggan, $provinsi_pelanggan, $kdps_pelanggan";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice - PT Indo Kimia Abadi</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">

    <style>
        /* Define the blue color variable */
        :root {
            --primary-blue: #0d6efd;
        }

        /* Gaya dasar untuk tampilan web (opsional, akan ditimpa oleh @media print) */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa; /* Latar belakang abu-abu muda */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .invoice-box {
            width: 210mm; /* Lebar A4 */
            min-height: 297mm; /* Tinggi A4 */
            margin: 20px auto; /* Margin di layar */
            border: 1px solid #ddd;
            background: #fff;
            padding: 20mm 15mm; /* Padding untuk konten */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Header Logo & Info Perusahaan */
        .company-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .company-info img {
            max-width: 80px; /* Sesuaikan ukuran logo */
            height: auto;
            margin-right: 15px;
        }
        .company-info .text-info {
            line-height: 1.2; /* Mengurangi line-height untuk mengurangi jarak antar baris */
        }
        /* Mengubah warna teks PT INDO KIMIA ABADI */
        .company-info .text-info .company-name {
            font-size: 22px; /* Ditingkatkan sedikit, tapi tetap di bawah 40px (invoice title) */
            margin-bottom: 3px;
            color: var(--primary-blue); /* Warna biru */
            font-weight: bold; /* Tetap bold */
            display: block; /* Agar di baris terpisah */
        }
        /* Mengatur warna teks info lainnya menjadi hitam dan ukuran font */
        .company-info .text-info span {
            color: #000; /* Warna hitam */
            font-size: 14px; /* Ukuran font normal untuk alamat dan kontak */
        }


        /* Invoice Title */
        .invoice-title {
            position: absolute;
            top: 20mm; /* Sesuaikan posisi vertikal */
            right: 15mm; /* Sesuaikan posisi horizontal */
            font-size: 40px;
            font-weight: bold;
            color: var(--primary-blue);
            text-transform: uppercase;
        }

        /* Section Header (e.g., Kepada Yth:, Deskripsi Barang) */
        .section-header {
            font-size: 16px;
            font-weight: bold;
            color: #fff; /* Teks putih untuk background biru */
            background-color: var(--primary-blue); /* Background biru */
            margin-bottom: 10px;
            padding: 5px 10px; /* Padding di dalam background biru */
            border-bottom: none; /* Hapus garis bawah default */
            border-radius: 3px; /* Sedikit border-radius agar tidak terlalu kaku */
        }

        /* Tabel Umum */
        .table {
            margin-bottom: 15px;
            font-size: 14px;
        }
        /* Default padding dan vertical-align untuk semua sel tabel */
        .table th, .table td {
            padding: 4px;
            vertical-align: top;
        }
        .table tfoot td {
            padding-top: 5px;
            padding-bottom: 5px;
        }
        /* Mengatur lebar kolom label di tabel info invoice */
        .table-info-invoice td:first-child {
            width: 100px; /* Lebar kolom label, disesuaikan */
        }

        /* Styling untuk tabel info invoice (NO.INVOICE, TGL INVOICE, NO.PO, TGL PO) */
        .table-info-invoice td {
            text-align: center; /* Rata tengah untuk semua sel di tabel ini */
            border: none !important; /* Pastikan tidak ada border sama sekali di tabel ini, bahkan di browser */
        }
        .table-info-invoice tr:nth-child(1) td, /* Baris NO.INVOICE / TGL INVOICE */
        .table-info-invoice tr:nth-child(3) td { /* Baris NO.PO / TGL PO */
            background-color: var(--primary-blue); /* Background biru */
            color: #fff; /* Teks putih */
            font-weight: bold;
        }
        .table-info-invoice tr:nth-child(2) td, /* Baris nilai NO.INVOICE / TGL INVOICE */
        .table-info-invoice tr:nth-child(4) td { /* Baris nilai NO.PO / TGL PO */
            background-color: #fff; /* Background putih untuk nilai */
            color: #000; /* Teks hitam */
        }

        .info-header {
            border-top: 2px solid #000; /* Garis hitam tebal */
            padding-top: 16px; /* Beri jarak antara garis dan konten */
        }


        /* --- Styling untuk Tabel Produk (table.table-bordered) --- */
        .table.table-bordered {
            border-collapse: collapse; /* Penting untuk border yang rapi */
            width: 100%;
        }
        /* Ini akan menerapkan border hitam pada SEMUA sel (th dan td) di tabel produk */
        .table.table-bordered th,
        .table.table-bordered td {
            border: 1px solid #000 !important; /* FULL BORDER HITAM, TERMASUK SAAT DI BROWSER */
        }

        /* Header tabel produk */
        .table-bordered thead th {
            background-color: var(--primary-blue); /* Latar belakang biru */
            color: #fff; /* Teks putih */
            border-color: #000 !important; /* Border hitam */
            text-align: center; /* Rata tengah header */
        }


        /* Kolom QTY, Harga Satuan, Jumlah Harga */
        .table-bordered td:nth-child(4),
        .table-bordered td:nth-child(5) { /* Jumlah Harga */
            text-align: right;
        }
        .table-bordered th:nth-child(1),
        .table-bordered th:nth-child(2), .table-bordered td:nth-child(2),
        .table-bordered th:nth-child(3),
        .table-bordered th:nth-child(4), .table-bordered th:nth-child(5) { /* Satuan */
            text-align: center;
        }
        .table-bordered td:nth-child(1), .table-bordered td:nth-child(3){
            text-align: left;
        }


        /* Tfoot styling */
        .table-bordered tfoot td {
            border: 1px solid #000 !important; /* Border hitam untuk semua sel footer, termasuk saat di browser */
        }
        .table-bordered tfoot tr:last-child td {
            font-weight: bold; /* Jadikan total bold */
            color: #000; /* Pastikan teks total hitam */
        }

        /* ==== CSS untuk Media Cetak (Print) ==== */
        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact; /* Penting untuk mencetak warna latar belakang/border */
                print-color-adjust: exact;
            }
            .invoice-box {
                width: 210mm;
                min-height: 297mm;
                margin: 0; /* Hapus margin agar mengisi halaman */
                border: none; /* Hapus border kotak utama saat dicetak */
                background: #fff;
                box-shadow: none;
                /* Kurangi padding untuk memberi lebih banyak ruang konten */
                padding: 10mm 15mm; /* Mengurangi padding vertikal */
                font-size: 9.5pt; /* Ukuran font sedikit lebih kecil untuk cetak */
            }

            .invoice-title {
                top: 10mm; /* Sesuaikan posisi vertikal saat dicetak */
                right: 15mm;
                font-size: 32px; /* Ukuran font lebih kecil saat dicetak */
                color: var(--primary-blue) !important; /* Pastikan biru tercetak */
            }

            .company-info {
                margin-bottom: 10px; /* Kurangi margin bawah */
            }
            .company-info img {
                max-width: 60px; /* Sesuaikan ukuran logo saat dicetak */
            }
            .company-info .text-info {
                font-size: 11px; /* Ukuran font lebih kecil */
                line-height: 1.0; /* Lebih rapat */
            }
            .company-info .text-info .company-name {
                font-size: 16px; /* Ukuran font lebih kecil saat dicetak */
                color: var(--primary-blue) !important;
            }
            .company-info .text-info span {
                color: #000 !important;
                font-size: 10px; /* Ukuran font lebih kecil untuk alamat saat dicetak */
            }

            .section-header {
                font-size: 14px; /* Lebih kecil */
                margin-bottom: 5px; /* Kurangi margin */
                padding: 3px 8px; /* Kurangi padding */
                color: #fff !important;
                background-color: var(--primary-blue) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .table {
                margin-bottom: 10px; /* Kurangi margin bawah tabel */
                font-size: 12px; /* Font tabel lebih kecil saat cetak */
            }
            .table th, .table td {
                padding: 3px; /* Kurangi padding sel tabel */
            }

            /* Styling untuk tabel info invoice saat cetak */
            .table-info-invoice {
                margin-bottom: 10px; /* Kurangi margin */
            }
            .table-info-invoice td {
                font-size: 11px; /* Font lebih kecil */
                padding: 2px; /* Kurangi padding */
                border: none !important;
            }
            .table-info-invoice tr:nth-child(1) td,
            .table-info-invoice tr:nth-child(3) td {
                background-color: var(--primary-blue) !important;
                color: #fff !important;
            }
            .table-info-invoice tr:nth-child(2) td,
            .table-info-invoice tr:nth-child(4) td {
                background-color: #fff !important;
                color: #000 !important;
            }

            /* --- Styling untuk Tabel Produk (table.table-bordered) saat Cetak --- */
            .table.table-bordered th,
            .table.table-bordered td {
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .table-bordered thead th {
                background-color: var(--primary-blue) !important;
                color: #fff !important;
                border-color: #000 !important;
            }
            .table-bordered tfoot td {
                border: 1px solid #000 !important;
            }
            /* Menyesuaikan text-align untuk kolom di cetak jika berbeda dari layar */
            /* Kolom QTY, Harga Satuan, Jumlah Harga */
            .table-bordered td:nth-child(4),
            .table-bordered td:nth-child(5) { /* Jumlah Harga */
                text-align: right;
            }
            .table-bordered th:nth-child(1),
            .table-bordered th:nth-child(2), .table-bordered td:nth-child(2),
            .table-bordered th:nth-child(3),
            .table-bordered th:nth-child(4), .table-bordered th:nth-child(5) { /* Satuan */
                text-align: center;
            }
            .table-bordered td:nth-child(1), .table-bordered td:nth-child(3){
                text-align: left;
            }


            /* Signature area */
            .signature-area {
                margin-top: 30px; /* Kurangi margin atas */
                padding-right: 20px; /* Sesuaikan agar tidak terlalu pinggir saat cetak */
                font-size: 12px; /* Font lebih kecil */
            }
            .signature-line {
                width: 150px; /* Lebar garis tanda tangan lebih kecil */
                margin-top: 40px; /* Ruang untuk tanda tangan lebih rapat */
            }

            /* Hindari pemecahan halaman di dalam elemen penting */
            .table, .invoice-details, .invoice-products, .invoice-summary, .signature-area {
                page-break-inside: avoid !important;
            }
            /* Coba untuk mendorong tanda tangan ke halaman berikutnya jika terlalu dekat dengan akhir */
            .signature-area {
                page-break-before: auto; /* Biarkan browser memutuskan */
            }
        }
    </style>
    <script>
        // Otomatis cetak saat halaman dimuat
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="invoice-box">
        <div class="company-info">
            <img src="assets/img/favicon.svg" alt="Logo Perusahaan"> <div class="text-info">
                <span class="company-name">PT INDO KIMIA ABADI</span>
                <span>Kawasan Industri Millennium 11 B Blok F6 No.05-06</span><br>
                <span>Tigaraksa Panongan</span><br>
                <span>Telp : (021) 29007072</span><br>
                <span>Email : office@indokimiaabadi.com</span>
            </div>
        </div>

        <div class="invoice-title">
            INVOICE
        </div>

        <div class="row info-header" style="margin-top: 20px;"> <div class="col-6">
                <div class="section-header">Kepada Yth:</div>
                <p>
                    <strong><?= $nama_pelanggan ?></strong><br>
                    <?= nl2br($alamat_pelanggan) ?><br>
                    Telp: <?= $headphone_pelanggan ?><br>
                    Email: <?= $email_pelanggan ?>
                </p>
            </div>
            <div class="col-6">
                <table class="table table-info-invoice float-end" style="width: auto;">
                    <tr>
                        <td>NO.INVOICE</td>
                        <td>TGL INVOICE</td>
                    </tr>
                    <tr>
                        <td><?= $no_invoice ?></td>
                        <td><?= $tgl_invoice ?></td>
                    </tr>
                    <tr>
                        <td>NO.PO</td>
                        <td>TGL PO</td>
                    </tr>
                    <tr>
                        <td><?= $no_po ?></td>
                        <td><?= $tgl_pemesanan?></td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Deskripsi Barang</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pesanan['produk'])): ?>
                    <?php foreach ($pesanan['produk'] as $detailPesanan): ?>
                        <tr>
                            <td><?= htmlspecialchars($detailPesanan['nama_produk_satuan']); ?></td>
                            <td><?= htmlspecialchars($detailPesanan['jumlah']); ?></td>
                            <td><?= htmlspecialchars($detailPesanan['satuan_produk']); ?></td>
                            <td>Rp. <?= number_format($detailPesanan['harga_produk_satuan'], 0, ',', '.'); ?></td>
                            <td>Rp. <?= number_format($detailPesanan['subtotal_produk'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada produk dalam pesanan ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end">Sub Total</td> <td class="text-end">Rp. <?= $subtotal ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end">PPN 11%</td> <td class="text-end">Rp. <?= $total_ppn ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-end">Total</td> <td class="text-end"><strong>Rp. <?= $total_tagihan ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>