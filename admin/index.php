<?php 
include 'includes/header.php';?>

    <?php 
    if (isset($_GET['halaman'])) {
        if ($_GET['halaman']=="daftarproduk") {
            include 'daftar_produk.php';
        }

            elseif ($_GET['halaman']=="tambahproduk") 
        {
            include 'tambah_produk.php';
        }

            elseif ($_GET['halaman']=="editproduk") 
        {
            include 'edit_produk.php';
        }

            elseif ($_GET['halaman']=="detailproduk") 
        {
            include 'detail_produk.php';
        }
            elseif ($_GET['halaman']=="daftarkategori") 
        {
            include 'daftar_kategori.php';
        }
            elseif ($_GET['halaman']=="daftarpesanan") 
        {
            include 'daftar_pesanan.php';
        }
            elseif ($_GET['halaman']=="detailpesanan") 
        {
            include 'detail_pesanan.php';
        }
            elseif ($_GET['halaman']=="pesananbaru") 
        {
            include 'daftar_pesanan_baru.php';
        }
            elseif ($_GET['halaman']=="menunggupembayaran") 
        {
            include 'menunggu_pembayaran.php';
        }
            elseif ($_GET['halaman']=="pesanandiproses") 
        {
            include 'daftar_pesanan_diproses.php';
        }
            elseif ($_GET['halaman']=="pesanandikirim") 
        {
            include 'daftar_pesanan_dikirim.php';
        }
            elseif ($_GET['halaman']=="pesananselesai") 
        {
            include 'daftar_pesanan_selesai.php';
        }
            elseif ($_GET['halaman']=="daftarlaporan") 
        {
            include 'daftar_laporan.php';
        }
            elseif ($_GET['halaman']=="daftarmanajer") 
        {
            include 'daftar_manajer.php';
        }
            elseif ($_GET['halaman']=="detailmanajer") 
        {
            include 'detail_manajer.php';
        }
            elseif ($_GET['halaman']=="tambahmanajer") 
        {
            include 'tambah_manajer.php';
        }
            elseif ($_GET['halaman']=="editmanajer") 
        {
            include 'edit_manajer.php';
        }
            elseif ($_GET['halaman']=="daftaradmin") 
        {
            include 'daftar_admin.php';
        }
            elseif ($_GET['halaman']=="detailadmin") 
        {
            include 'detail_admin.php';
        }
            elseif ($_GET['halaman']=="tambahadmin") 
        {
            include 'tambah_admin.php';
        }
            elseif ($_GET['halaman']=="editadmin") 
        {
            include 'edit_admin.php';
        }
            elseif ($_GET['halaman']=="daftarpelanggan") 
        {
            include 'daftar_pelanggan.php';
        }
            elseif ($_GET['halaman']=="detailpelanggan") 
        {
            include 'detail_pelanggan.php';
        }
            elseif ($_GET['halaman']=="logout") 
        {
            include 'logout.php';
        }
    }
    else
    {
        include 'dashboard.php';
    }

    ?>

<?php include 'includes/footer.php';?>
