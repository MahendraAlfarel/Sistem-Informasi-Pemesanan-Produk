<?php
require_once dirname(__FILE__) . '/vendor/midtrans/Midtrans.php';
require_once 'koneksi.php';

\Midtrans\Config::$serverKey = 'Your_Midtrans_Server_Key_Sandbox';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

header('Content-Type: application/json');

$id_invoice = $_POST['id'];

$query = mysqli_query($con, "SELECT * FROM tb_invoice ti
    JOIN tb_pesanan tp ON ti.id_pesanan = tp.id_pesanan
    JOIN tb_pelanggan pl ON tp.id_pelanggan = pl.id_pelanggan
    WHERE ti.id_invoice = '$id_invoice' LIMIT 1");

$data = mysqli_fetch_assoc($query);
if (!$data) {
    echo json_encode(['error' => 'Transaksi tidak ditemukan']);
    exit;
}

$current_time = date('Y-m-d');
if (!empty($data['token_pembayaran']) && !empty($data['token_pembayaran'])) {
    if ($current_time < $data['jatuh_tempo']) {
        echo json_encode(['token' => $data['token_pembayaran']]);
        exit;
    }
}

$item_details = array();
$query_item = mysqli_query($con, "SELECT * FROM tb_detail_pesanan tds 
    JOIN tb_produk tpk ON tds.id_produk = tpk.id_produk 
    WHERE tds.id_pesanan = '" . $data['id_pesanan'] . "'");

$gross_amount = 0;

while ($row = mysqli_fetch_assoc($query_item)) {
    $subtotal = (int)$row['harga_produk_satuan'] * (int)$row['jumlah'];
    $gross_amount += $subtotal;

    $item_details[] = array(
        'id' => $row['id_produk'],
        'price' => (int)$row['harga_produk_satuan'],
        'quantity' => (int)$row['jumlah'],
        'name' => substr($row['nama_produk_satuan'], 0, 50)
    );
}

// Hitung dan tambahkan PPN 11% sebagai item terpisah
$ppn_amount = round($gross_amount * 0.11);
$item_details[] = array(
    'id' => 'PPN',
    'price' => $ppn_amount,
    'quantity' => 1,
    'name' => 'PPN 11%'
);

// Total tagihan = harga produk + PPN
$total_tagihan = $gross_amount + $ppn_amount;

$alamat_lengkap = $data['alamat_pelanggan'] . ', Kel. ' . $data['nama_kelurahan'] . ', Kec. ' . $data['nama_kecamatan'] . ', Kota ' . $data['nama_kota'] . ', ' . $data['nama_provinsi'] . ', ' . $data['kode_pos'];

$jatuh_tempo = $data['jatuh_tempo'];
$skrng = time();
$waktu_tempo = strtotime($jatuh_tempo);

$durasi = ceil($waktu_tempo - $skrng) / (60 * 60 * 24);
$durasi = (int)$durasi;

if ($durasi < 1) {
    $durasi = 1;
}

$billing_address = array(
    'first_name'    => $data['nama_pelanggan'],
    'email'         => $data['email_pelanggan'],
    'phone'         => $data['headphone_pelanggan'],
    'address'       => $alamat_lengkap,
    'city'          => $data['nama_kota'],
    'postal_code'   => $data['kode_pos'],
    'country_code'  => 'IDN'
);

$shipping_address = array(
    'first_name'    => $data['nama_penerima'],
    'email'         => $data['email_pelanggan'],
    'phone'         => $data['headphone_penerima'],
    'address'       => $data['alamat_penerima'],
    'city'          => $data['nama_kota'],
    'postal_code'   => $data['kode_pos'],
    'country_code'  => 'IDN'
);

$transaction_details = array(
    'order_id'      => $data['no_invoice'],
    'gross_amount'  => $total_tagihan,
);

$customer_details = array(
    'first_name'        => $data['nama_pelanggan'],
    'email'             => $data['email_pelanggan'],
    'phone'             => $data['headphone_pelanggan'],
    'billing_address'   => $billing_address,
    'shipping_address'  => $shipping_address
);

$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details'    => $customer_details,
    'item_details'        => $item_details,
    'expiry'              => array(
        'start_time' => date('Y-m-d H:i:s O'),
        'unit' => 'day',
        'duration' => $durasi
    )
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    $update = mysqli_query($con, "UPDATE tb_invoice SET token_pembayaran = '$snapToken' WHERE id_invoice = '$id_invoice'");
    echo json_encode(['token' => $snapToken]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
