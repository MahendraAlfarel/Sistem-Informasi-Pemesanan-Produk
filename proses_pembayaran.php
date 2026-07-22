<?php
// update_status_internal.php
// Pastikan file koneksi.php Anda sudah benar dan berisi objek koneksi $con
require 'koneksi.php'; 

header('Content-Type: application/json'); // Penting untuk respons JSON

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Metode request tidak valid.']);
    exit;
}

// Dapatkan no_invoice dari data POST yang dikirim dari JavaScript
$order_id = $_POST['no_invoice'] ?? null; 
$new_status_from_frontend = $_POST['status'] ?? null;

if (!$order_id) {
    echo json_encode(['success' => false, 'error' => 'No Invoice tidak ditemukan.']);
    exit;
}

$new_status_invoice = $new_status_from_frontend ?: 'Sudah Dibayar'; 
$new_status_pesanan = 'Menunggu Konfirmasi'; 
$status_transaksi = 'Berhasil';
$metode = 'Virtual Account';
$now = date("Y-m-d");
$tanggal = date("Y-m-d H:i:s");

try {
    // Ambil id_invoice, id_pesanan, dan total_tagihan dari tb_invoice berdasarkan no_invoice
    // GUNAKAN PREPARED STATEMENTS UNTUK KEAMANAN SQL INJECTION!
    $stmt_select = mysqli_prepare($con, "SELECT id_invoice, id_pesanan, total_tagihan FROM tb_invoice WHERE id_invoice = ? LIMIT 1");
    if (!$stmt_select) {
        throw new Exception("Prepared statement select failed: " . mysqli_error($con));
    }
    mysqli_stmt_bind_param($stmt_select, "s", $order_id);
    mysqli_stmt_execute($stmt_select);
    $query_result = mysqli_stmt_get_result($stmt_select);
    $data = mysqli_fetch_assoc($query_result);
    mysqli_stmt_close($stmt_select);

    if ($data) {
        $id_invoice = $data['id_invoice'];
        $id_pesanan = $data['id_pesanan'];
        $jumlah_pembayaran = $data['total_tagihan']; // Mengambil dari total_tagihan di tb_invoice

        // Mulai transaksi database (opsional tapi sangat disarankan untuk operasi multi-tabel)
        mysqli_begin_transaction($con); 

        // 1. Update status invoice di tb_invoice
        $stmt_update_invoice = mysqli_prepare($con, "UPDATE tb_invoice SET status_invoice = ?, tgl_perbarui = ? WHERE id_invoice = ?");
        if (!$stmt_update_invoice) {
            throw new Exception("Prepared statement update invoice failed: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt_update_invoice, "sss", $new_status_invoice, $tanggal, $id_invoice);
        $update_invoice_success = mysqli_stmt_execute($stmt_update_invoice);
        mysqli_stmt_close($stmt_update_invoice);

        // 2. Update status pesanan di tb_pesanan
        $stmt_update_pesanan = mysqli_prepare($con, "UPDATE tb_pesanan SET status_pesanan = ?, tgl_perbarui = ? WHERE id_pesanan = ?");
        if (!$stmt_update_pesanan) {
            throw new Exception("Prepared statement update pesanan failed: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt_update_pesanan, "sss", $new_status_pesanan, $tanggal, $id_pesanan);
        $update_pesanan_success = mysqli_stmt_execute($stmt_update_pesanan);
        mysqli_stmt_close($stmt_update_pesanan);

        // 3. Insert data ke tabel tb_transaksi
        $stmt_insert_transaksi = mysqli_prepare($con, 
            "INSERT INTO tb_transaksi (id_invoice, metode_pembayaran, jumlah_pembayaran, tgl_bayar, status_transaksi, tgl_perbarui) 
             VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt_insert_transaksi) {
            throw new Exception("Prepared statement insert transaksi failed: " . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt_insert_transaksi, "isisss", 
            $id_invoice,
            $metode,
            $jumlah_pembayaran, 
            $now, 
            $status_transaksi, 
            $tanggal
        );
        $insert_transaksi_success = mysqli_stmt_execute($stmt_insert_transaksi);
        mysqli_stmt_close($stmt_insert_transaksi);

        // Jika semua operasi berhasil, commit transaksi
        if ($update_invoice_success && $update_pesanan_success && $insert_transaksi_success) {
            mysqli_commit($con);
            echo json_encode(['success' => true]);
        } else {
            // Jika ada yang gagal, rollback transaksi
            mysqli_rollback($con);
            echo json_encode(['success' => false, 'error' => 'Gagal memperbarui status atau insert transaksi.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Data invoice tidak ditemukan untuk No Invoice: ' . $order_id]);
    }

} catch (Exception $e) {
    // Pastikan rollback jika terjadi exception
    mysqli_rollback($con); 
    echo json_encode(['success' => false, 'error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
}
?>