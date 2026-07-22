-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Jul 2026 pada 12.56
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ika`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(255) NOT NULL,
  `headphone_admin` varchar(13) NOT NULL,
  `email_admin` varchar(50) NOT NULL,
  `alamat_admin` text NOT NULL,
  `foto_admin` varchar(255) NOT NULL,
  `username_admin` varchar(30) NOT NULL,
  `password_admin` varchar(255) NOT NULL,
  `status_admin` enum('Aktif','Tidak Aktif','','') NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_manajer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `nama_admin`, `headphone_admin`, `email_admin`, `alamat_admin`, `foto_admin`, `username_admin`, `password_admin`, `status_admin`, `tgl_perbarui`, `id_manajer`) VALUES
(1, 'Admin', '088989898989', 'admin@mail.com', 'Jl. Besar Raya', 'admin_687f52f595a678.81302217.png', 'admin02', '$2y$10$t73LV6D.LYbdOCGUZO2feudj3i7zKBi5O/AZn4lUZNeWzZf1vILI.', 'Aktif', '2026-07-22 06:35:17', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_detail_pesanan`
--

CREATE TABLE `tb_detail_pesanan` (
  `id_detail_pesanan` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_produk_satuan` varchar(255) NOT NULL,
  `harga_produk_satuan` int(15) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal_produk` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_invoice`
--

CREATE TABLE `tb_invoice` (
  `id_invoice` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `no_invoice` varchar(30) NOT NULL,
  `subtotal` int(15) NOT NULL,
  `total_ppn` int(15) NOT NULL,
  `total_tagihan` int(15) NOT NULL,
  `tgl_invoice` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `token_pembayaran` text NOT NULL,
  `status_invoice` enum('Belum Dibayar','Sudah Dibayar','Kedaluwarsa','Dibatalkan') NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `gambar_kategori` varchar(255) NOT NULL,
  `status_kategori` enum('Aktif','Tidak Aktif','','') NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nama_kategori`, `gambar_kategori`, `status_kategori`, `tgl_perbarui`) VALUES
(1, 'Otomotif', 'kategori_687f53aeee09d1.67473146.jpg', 'Aktif', '2025-08-06 04:34:11'),
(2, 'Perlengkapan Kebersihan', 'kategori_687f53dcb18009.79176681.jpg', 'Aktif', '2025-08-04 07:22:48'),
(3, 'Peralatan Rumah Tangga', 'kategori_687f53ee34d2a8.37561776.jpeg', 'Aktif', '2025-07-22 09:03:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_manajer`
--

CREATE TABLE `tb_manajer` (
  `id_manajer` int(11) NOT NULL,
  `nama_manajer` varchar(255) NOT NULL,
  `headphone_manajer` varchar(13) NOT NULL,
  `email_manajer` varchar(50) NOT NULL,
  `alamat_manajer` text NOT NULL,
  `foto_manajer` varchar(255) NOT NULL,
  `username_manajer` varchar(30) NOT NULL,
  `password_manajer` varchar(255) NOT NULL,
  `status_manajer` enum('Aktif','Tidak Aktif','','') NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_manajer`
--

INSERT INTO `tb_manajer` (`id_manajer`, `nama_manajer`, `headphone_manajer`, `email_manajer`, `alamat_manajer`, `foto_manajer`, `username_manajer`, `password_manajer`, `status_manajer`, `tgl_perbarui`) VALUES
(1, 'Manajer', '08998989898', 'manajer@mail.com', 'Jl. Besar Raya', 'manajer_6a609509a4f4c6.84768730.png', 'admin01', '$2y$10$QhxrEkGWdx/fx/WqIgpjiu8c3JxVz6ZnRHRB8XEPRGfPctswPD3NC', 'Aktif', '2026-07-22 10:01:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pelanggan`
--

CREATE TABLE `tb_pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(255) NOT NULL,
  `headphone_pelanggan` varchar(13) NOT NULL,
  `email_pelanggan` varchar(50) NOT NULL,
  `alamat_pelanggan` text NOT NULL,
  `id_provinsi` int(11) NOT NULL,
  `nama_provinsi` varchar(60) NOT NULL,
  `nama_kota` varchar(100) NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL,
  `nama_kelurahan` varchar(150) NOT NULL,
  `kode_pos` int(5) NOT NULL,
  `foto_pelanggan` varchar(255) NOT NULL,
  `username_pelanggan` varchar(30) NOT NULL,
  `password_pelanggan` varchar(255) NOT NULL,
  `tgl_register` datetime NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_pelanggan`
--

INSERT INTO `tb_pelanggan` (`id_pelanggan`, `nama_pelanggan`, `headphone_pelanggan`, `email_pelanggan`, `alamat_pelanggan`, `id_provinsi`, `nama_provinsi`, `nama_kota`, `nama_kecamatan`, `nama_kelurahan`, `kode_pos`, `foto_pelanggan`, `username_pelanggan`, `password_pelanggan`, `tgl_register`, `tgl_perbarui`) VALUES
(1, 'Nama Lengkap', '089898998989', 'test@mail.com', 'Jalan Besar Raya', 5, 'Gorontalo', 'Kabupaten Bone Bolango', 'Pinogu', 'Dataran Hijau', 13124, 'pelanggan_6a6062d49f2c4.png', 'username', '$2y$10$fRfINNhFys.6fLFfYqNwAOhvMeFOYfO12fj2m248Wm/SSqty8ngCC', '2025-07-22 16:23:54', '2026-07-22 10:54:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` int(11) NOT NULL,
  `no_po` varchar(30) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `nama_penerima` varchar(255) NOT NULL,
  `headphone_penerima` varchar(13) NOT NULL,
  `alamat_penerima` text NOT NULL,
  `total_harga` int(15) NOT NULL,
  `catatan_pesanan` text NOT NULL,
  `estimasi` varchar(10) NOT NULL,
  `tgl_pemesanan` date NOT NULL,
  `status_pesanan` enum('Menunggu Pembayaran','Menunggu Konfirmasi','Sedang Diproses','Sedang Dikirim','Selesai','Dibatalkan') NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_produk`
--

CREATE TABLE `tb_produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `harga_produk` int(11) NOT NULL,
  `variasi_produk` varchar(30) NOT NULL,
  `berat_produk` int(15) NOT NULL,
  `deskripsi_produk` text NOT NULL,
  `satuan_produk` varchar(30) NOT NULL,
  `foto_produk` varchar(255) NOT NULL,
  `foto_produk_2` varchar(255) NOT NULL,
  `foto_produk_3` varchar(255) NOT NULL,
  `status_produk` enum('Masih Diproduksi','Tidak Diproduksi','','') NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_produk`
--

INSERT INTO `tb_produk` (`id_produk`, `nama_produk`, `id_kategori`, `harga_produk`, `variasi_produk`, `berat_produk`, `deskripsi_produk`, `satuan_produk`, `foto_produk`, `foto_produk_2`, `foto_produk_3`, `status_produk`, `tgl_perbarui`) VALUES
(1, 'Rexco 18 Contact Cleaner 220 ML/ 145 G/ 7.4 fl OZ', 1, 260000, '220 Ml', 145, 'REXCO 18 CONTACT CLEANER adalah aerosol cairan pembersih yang tidak meninggalkan residu, tidak menghantarkan listrik, dan dapat menghambat terjadinya korosi. Cairan khusus ini bisa dipergunakan untuk membersihkan part-part elektronik. Dengan daya bersih yang 2x lebih efektif dan penguapan 2x lebih cepat jika dibandingkan dengan merk lain maka sangat cocok untuk digunakan sebagai pembersih komponen elektronik seperti panel elektrik mobil, papan PCB, motherboard komputer, kumparan dinamo, dan komponen elektronik lainnya yang memerlukan pembersihan. Komponen elektronik yang akan dibersihkan harus dalam keadaan mati / tidak ada arus listrik untuk menghindari korslet\r\n\r\nKegunaan:\r\n-panel listrik\r\n-panel telekomunikasi\r\n-panel elektrik mobil\r\n-panel sirkuit\r\n-komputer', 'Box', 'foto_687f5664c1a241.58702234.jpg', 'foto_687f5664c1e2b3.74857915.jpg', 'foto_687f5664c20e46.56560790.png', 'Masih Diproduksi', '2025-08-26 04:12:54'),
(2, 'Rexco 18 Contact Cleaner 500 ML/ 330 G/ 16.9 fl OZ', 1, 360000, '500 Ml', 330, 'REXCO 18 CONTACT CLEANER adalah aerosol cairan pembersih yang tidak meninggalkan residu, tidak menghantarkan listrik, dan dapat menghambat terjadinya korosi. Cairan khusus ini bisa dipergunakan untuk membersihkan part-part elektronik. Dengan daya bersih yang 2x lebih efektif dan penguapan 2x lebih cepat jika dibandingkan dengan merk lain maka sangat cocok untuk digunakan sebagai pembersih komponen elektronik seperti panel elektrik mobil, papan PCB, motherboard komputer, kumparan dinamo, dan komponen elektronik lainnya yang memerlukan pembersihan. Komponen elektronik yang akan dibersihkan harus dalam keadaan mati / tidak ada arus listrik untuk menghindari korslet\r\n\r\nKegunaan:\r\n-panel listrik\r\n-panel telekomunikasi\r\n-panel elektrik mobil\r\n-panel sirkuit\r\n-komputer', 'Box', 'Foto_Produk_1_687f5648797d45.71708177_3427.jpg', 'Foto_Produk_2_687f564879a6f2.07049615_4133.png', 'Foto_Produk_3_687f56487a2de4.97173456_6990.png', 'Masih Diproduksi', '2025-08-26 04:13:01'),
(3, 'Rexco 20 Ultimate Hand Cleaner 443,5 ML/ 422 G/ 15 fl OZ', 2, 263000, '443,5 Ml', 422, 'REXCO 20 ULTIMATE HAND CLEANER adalah cairan gel pembersih tangan dari segala jenis kotoran, minyak, oli, gemuk, dan sisa lem ringan. Dengan daya bersih 3x lebih cepat jika dibandingkan dengan merk lain dan ramah terhadap kulit Anda. Cairan ini dilengkapi dengan pelembab dan anti bakteri serta tidak mengandung pelarut solvent keras yang dapat menyebabkan kulit telapak tangan menjadi kasar.\r\nCukup sekali bilas, tangan akan kembali bersih dan harum.\r\n\r\n*Dapat digunakan dengan atau tanpa air\r\n\r\nFungsi :\r\nRexco 20 digunakan untuk pembersih tangan dari segala jenis kotoran, minyak, oli, dll.\r\n\r\nUnique Selling Point :\r\nDaya bersih 3x lebih cepat. Ramah terhadap kulit.', 'Box', 'Foto_Produk_1_687f571aeac420.80817686_3017.jpg', 'Foto_Produk_2_687f571aeb3251.14080669_8013.jpg', '', 'Masih Diproduksi', '2025-08-26 04:13:14'),
(4, 'Rexco 25 Chain Lube 120 ML/ 107 G/ 4.2 fl OZ', 1, 235000, '120 Ml', 107, 'REXCO 25 CHAIN LUBE adalah cairan khusus yang berfungsi melumasi dan memperlambat pengikisan pada rantai, memperpanjang usia rantai dan roda gigi, menjaga performa rantai dalam beban tinggi, serta melindungi rantai dari karat. Dengan lubrikasi 3x lebih efektif, daya lekat 2x lebih tahan lama, serta anti debu dan kotoran 2x lebih baik dibandingkan dengan merk lain membuat cairan ini dapat diaplikasikan pada motor, gergaji mesin, roda gigi mesin, mesin katrol, serta komponen mesin lainnya yang terdapat roda gigi ataupun rantai\r\n\r\nFungsi :\r\n– Rexco 25 Chain Lube berfungsi membersihkan debu dan kotoran pada rantai.\r\n\r\nUnique Selling Point :\r\nLubrikasi 3x lebih efektif. Daya lekat 2x lebih tahan lama. Anti debu &amp; kotoran 2x lebih baik.', 'Box', 'Foto_Produk_1_687f57fde37b93.66049422_3308.jpg', 'Foto_Produk_2_687f57fde3ce68.59474151_3638.jpg', 'Foto_Produk_3_687f57fde43653.37521662_6893.png', 'Masih Diproduksi', '2025-08-26 04:13:21'),
(5, 'Rexco 25 Chain Lube 350 ML/ 312 G/ 11.8 fl OZ', 1, 330000, '350 Ml', 312, 'REXCO 25 CHAIN LUBE adalah cairan khusus yang berfungsi melumasi dan memperlambat pengikisan pada rantai, memperpanjang usia rantai dan roda gigi, menjaga performa rantai dalam beban tinggi, serta melindungi rantai dari karat. Dengan lubrikasi 3x lebih efektif, daya lekat 2x lebih tahan lama, serta anti debu dan kotoran 2x lebih baik dibandingkan dengan merk lain membuat cairan ini dapat diaplikasikan pada motor, gergaji mesin, roda gigi mesin, mesin katrol, serta komponen mesin lainnya yang terdapat roda gigi ataupun rantai\r\n\r\nFungsi :\r\n– Rexco 25 Chain Lube berfungsi membersihkan debu dan kotoran pada rantai.\r\n\r\nUnique Selling Point :\r\nLubrikasi 3x lebih efektif. Daya lekat 2x lebih tahan lama. Anti debu &amp; kotoran 2x lebih baik.', 'Box', 'Foto_Produk_1_687f58512696e1.70373708_8851.jpeg', 'Foto_Produk_2_687f5851271497.78081126_9396.jpeg', '', 'Masih Diproduksi', '2025-08-26 04:13:26'),
(6, 'Rexco 50 Lubricant 120 ML/ 100 G/ 4.2 fl OZ', 1, 248000, '120 Ml', 100, 'REXCO 50 MULTI PURPOSE LUBRICANT adalah cairan multifungsi yang berfungsi untuk membersihkan, melumasi dan memproteksi mesin serta peralatan yang terbuat dari metal. Dengan penetrasi 2x lebih cepat, lubrikasi 3x lebih efektif, dan formula anti karat 3x lebih lama dari merk lain, rexco 50 sangat baik dalam melindungi metal dari karat dan korosi, melepaskan mur dan baut yang berkarat dengan kemampuan penetrasi yang cepat, menghilangkan bunyi derit akibat gesekan, dan dapat membersihkan kotoran oli, aspal, serta debu dari komponen barang anda. Cairan ini sangat efektif untuk digunakan pada mesin industri, engsel pintu, aki mobil, dan bagian dari body mobil ataupun motor.\r\n\r\nFUNGSI :\r\n1. MELINDUNGI MESIN INDUSTRI DARI KARAT\r\n2. MELUMASI ENGSEL PINTU\r\n3. MELINDUNGI PIPA KILANG DARI KARAT\r\n4. MELINDUNGI KEPALA AKI DARI PROSES OKSIDASI\r\n5. MENGHILANGKAN KELEMBAPAN PADA BUSI MOTOR\r\n6. MEMBERSIHKAN ASPAL DI BADAN MOBIL\r\n7. MELONGGARKAN BAGIAN YANG BERKARAT\r\n8. MELINDUNGI DARI KARAT\r\n9. MENGHILANGKAN KELEMBAPAN\r\n10. MENGHILANGKAN BUNYI DERIT\r\n11. MEMBERSIHKAN &amp; MENGHILANGKAN OLI, ASPAL, &amp; DEBU\r\n\r\nUnique Selling Point :\r\nDijamin Kualitas 3x lebih baik &amp; Harga lebih murah.', 'Box', 'Foto_Produk_1_687f58d91934f6.27890263_7117.jpg', 'Foto_Produk_2_687f58d9197079.01441030_6464.jpg', 'Foto_Produk_3_687f58d9199700.50272706_5904.jpeg', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(7, 'Rexco 50 Lubricant 220 ML/ 183 G/ 7.4 fl OZ', 1, 288000, '220 Ml', 183, 'REXCO 50 MULTI PURPOSE LUBRICANT adalah cairan multifungsi yang berfungsi untuk membersihkan, melumasi dan memproteksi mesin serta peralatan yang terbuat dari metal. Dengan penetrasi 2x lebih cepat, lubrikasi 3x lebih efektif, dan formula anti karat 3x lebih lama dari merk lain, rexco 50 sangat baik dalam melindungi metal dari karat dan korosi, melepaskan mur dan baut yang berkarat dengan kemampuan penetrasi yang cepat, menghilangkan bunyi derit akibat gesekan, dan dapat membersihkan kotoran oli, aspal, serta debu dari komponen barang anda. Cairan ini sangat efektif untuk digunakan pada mesin industri, engsel pintu, aki mobil, dan bagian dari body mobil ataupun motor.\r\n\r\nFUNGSI :\r\n1. MELINDUNGI MESIN INDUSTRI DARI KARAT\r\n2. MELUMASI ENGSEL PINTU\r\n3. MELINDUNGI PIPA KILANG DARI KARAT\r\n4. MELINDUNGI KEPALA AKI DARI PROSES OKSIDASI\r\n5. MENGHILANGKAN KELEMBAPAN PADA BUSI MOTOR\r\n6. MEMBERSIHKAN ASPAL DI BADAN MOBIL\r\n7. MELONGGARKAN BAGIAN YANG BERKARAT\r\n8. MELINDUNGI DARI KARAT\r\n9. MENGHILANGKAN KELEMBAPAN\r\n10. MENGHILANGKAN BUNYI DERIT\r\n11. MEMBERSIHKAN &amp; MENGHILANGKAN OLI, ASPAL, &amp; DEBU\r\n\r\nUnique Selling Point :\r\nDijamin Kualitas 3x lebih baik &amp; Harga lebih murah.', 'Box', 'Foto_Produk_1_687f591e112bf6.01742511_2827.jpeg', 'Foto_Produk_2_687f591e1178c0.97994889_2065.jpg', 'Foto_Produk_3_687f591e11e217.62147188_5035.jpeg', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(8, 'Rexco 50 Lubricant 350 ML/ 291 G/ 11.8 fl OZ', 1, 367000, '350 Ml', 291, 'REXCO 50 MULTI PURPOSE LUBRICANT adalah cairan multifungsi yang berfungsi untuk membersihkan, melumasi dan memproteksi mesin serta peralatan yang terbuat dari metal. Dengan penetrasi 2x lebih cepat, lubrikasi 3x lebih efektif, dan formula anti karat 3x lebih lama dari merk lain, rexco 50 sangat baik dalam melindungi metal dari karat dan korosi, melepaskan mur dan baut yang berkarat dengan kemampuan penetrasi yang cepat, menghilangkan bunyi derit akibat gesekan, dan dapat membersihkan kotoran oli, aspal, serta debu dari komponen barang anda. Cairan ini sangat efektif untuk digunakan pada mesin industri, engsel pintu, aki mobil, dan bagian dari body mobil ataupun motor.\r\n\r\nFUNGSI :\r\n1. MELINDUNGI MESIN INDUSTRI DARI KARAT\r\n2. MELUMASI ENGSEL PINTU\r\n3. MELINDUNGI PIPA KILANG DARI KARAT\r\n4. MELINDUNGI KEPALA AKI DARI PROSES OKSIDASI\r\n5. MENGHILANGKAN KELEMBAPAN PADA BUSI MOTOR\r\n6. MEMBERSIHKAN ASPAL DI BADAN MOBIL\r\n7. MELONGGARKAN BAGIAN YANG BERKARAT\r\n8. MELINDUNGI DARI KARAT\r\n9. MENGHILANGKAN KELEMBAPAN\r\n10. MENGHILANGKAN BUNYI DERIT\r\n11. MEMBERSIHKAN &amp; MENGHILANGKAN OLI, ASPAL, &amp; DEBU\r\n\r\nUnique Selling Point :\r\nDijamin Kualitas 3x lebih baik &amp; Harga lebih murah.', 'Box', 'Foto_Produk_1_687f5963419534.75281145_5372.jpg', 'Foto_Produk_2_687f596341f5e9.45351904_5841.jpg', 'Foto_Produk_3_687f5963424027.86049573_2464.jpeg', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(9, 'Rexco 50 Lubricant 500 ML/ 415 G/ 16.9 fl OZ', 1, 444000, '500 Ml', 415, 'REXCO 50 MULTI PURPOSE LUBRICANT adalah cairan multifungsi yang berfungsi untuk membersihkan, melumasi dan memproteksi mesin serta peralatan yang terbuat dari metal. Dengan penetrasi 2x lebih cepat, lubrikasi 3x lebih efektif, dan formula anti karat 3x lebih lama dari merk lain, rexco 50 sangat baik dalam melindungi metal dari karat dan korosi, melepaskan mur dan baut yang berkarat dengan kemampuan penetrasi yang cepat, menghilangkan bunyi derit akibat gesekan, dan dapat membersihkan kotoran oli, aspal, serta debu dari komponen barang anda. Cairan ini sangat efektif untuk digunakan pada mesin industri, engsel pintu, aki mobil, dan bagian dari body mobil ataupun motor.\r\n\r\nFUNGSI :\r\n1. MELINDUNGI MESIN INDUSTRI DARI KARAT\r\n2. MELUMASI ENGSEL PINTU\r\n3. MELINDUNGI PIPA KILANG DARI KARAT\r\n4. MELINDUNGI KEPALA AKI DARI PROSES OKSIDASI\r\n5. MENGHILANGKAN KELEMBAPAN PADA BUSI MOTOR\r\n6. MEMBERSIHKAN ASPAL DI BADAN MOBIL\r\n7. MELONGGARKAN BAGIAN YANG BERKARAT\r\n8. MELINDUNGI DARI KARAT\r\n9. MENGHILANGKAN KELEMBAPAN\r\n10. MENGHILANGKAN BUNYI DERIT\r\n11. MEMBERSIHKAN &amp; MENGHILANGKAN OLI, ASPAL, &amp; DEBU\r\n\r\nUnique Selling Point :\r\nDijamin Kualitas 3x lebih baik &amp; Harga lebih murah.', 'Box', 'Foto_Produk_1_687f59dc8d2d09.05996864_5097.jpg', 'Foto_Produk_2_687f59dc8e09c3.00586490_2116.jpg', 'Foto_Produk_3_687f59dc8e3e97.98027050_4780.jpeg', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(10, 'Rexco 70 Multi Purpose Degreaser 220 ML/ 224 G/ 7.57 fl OZ', 1, 230000, '220 Ml', 224, 'FUNGSI :\r\n– MEMBERSIHKAN OLI GEMUK DI BERBAGAI MEDIA\r\n– MEMBERSIHKAN KOMPOR YG KOTOR KARENA MINYAK\r\n– MEMBERSIHKAN MESIN YG KOTOR KARENA OLI DAN GEMUK\r\n\r\nUnique Selling Point :\r\nDaya bersih 3x lebih cepat &amp; efektif. Melindungi 2x lebih lama.', 'Box', 'Foto_Produk_1_687f5a78dbd7f2.06597194_4802.jpeg', 'Foto_Produk_2_687f5a78dc3987.19093976_3081.jpeg', '', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(11, 'Rexco 70 Multi Purpose Degreaser 500 ML/ 485 G/ 16,9 fl OZ', 1, 352000, '500 Ml', 485, 'FUNGSI :\r\n– MEMBERSIHKAN OLI GEMUK DI BERBAGAI MEDIA\r\n– MEMBERSIHKAN KOMPOR YG KOTOR KARENA MINYAK\r\n– MEMBERSIHKAN MESIN YG KOTOR KARENA OLI DAN GEMUK\r\n\r\nUnique Selling Point :\r\nDaya bersih 3x lebih cepat &amp; efektif. Melindungi 2x lebih lama.', 'Box', 'Foto_Produk_1_687f5ac8862314.67744071_5751.jpg', 'Foto_Produk_2_687f5ac8869e99.44971893_6723.jpg', 'Foto_Produk_3_687f5ac88720e5.85403558_2054.jpeg', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(12, 'Rexco 81 Carb & Injection Cleaner 300 ML/ 225 G/ 10.1 fl OZ', 1, 250000, '300 Ml', 225, 'REXCO 81 CARB & INJECTOR CLEANER berfungsi untuk membersihkan seluruh bagian dari karburator dan injektor. Cairan ini efektif untuk membersihkan kotoran yang biasanya berada di ruang bakar, karburator, dan injector seperti zat karbon, pernis, resin. Cairan ini dilengkapi dengan anti korosi dengan penetrasi yang cepat, daya bersih 3x lebih cepat dan efektif dibandingkan dengan merk lain membuat performa kendaraan anda meningkat dan menurunkan emisi gas buang sehingga ramah lingkungan.\r\n\r\nFungsi :\r\nRexco 81 Carb & Injector berfungsi untuk membersihkan seluruh bagian dari karburator dan injektor.\r\n\r\nUnique Selling Point :\r\ndaya bersih 3x lebih cepat & efektif. Meningkatkan performa kendaraan.', 'Box', 'Foto_Produk_1_687f5beb594139.11444968_4703.jpeg', 'Foto_Produk_2_687f5beb597501.13910846_7314.jpeg', 'Foto_Produk_3_687f5beb59af76.39636373_9124.jpeg', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(13, 'Rexco 81 Carb & Injection Cleaner 500 ML/ 425 G/ 16.9 fl OZ', 1, 394000, '500 Ml', 425, 'REXCO 81 CARB & INJECTOR CLEANER berfungsi untuk membersihkan seluruh bagian dari karburator dan injektor. Cairan ini efektif untuk membersihkan kotoran yang biasanya berada di ruang bakar, karburator, dan injector seperti zat karbon, pernis, resin. Cairan ini dilengkapi dengan anti korosi dengan penetrasi yang cepat, daya bersih 3x lebih cepat dan efektif dibandingkan dengan merk lain membuat performa kendaraan anda meningkat dan menurunkan emisi gas buang sehingga ramah lingkungan.\r\n\r\nFungsi :\r\nRexco 81 Carb & Injector berfungsi untuk membersihkan seluruh bagian dari karburator dan injektor.\r\n\r\nUnique Selling Point :\r\ndaya bersih 3x lebih cepat & efektif. Meningkatkan performa kendaraan.', 'Box', 'Foto_Produk_1_687f5c24724013.79649835_2572.jpg', 'Foto_Produk_2_687f5c24727048.05966635_3006.jpg', 'Foto_Produk_3_687f5c24729918.78172658_6913.jpeg', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(14, 'Rexco 82 Spare Part & Brake Cleaner 220 ML / 176 G/ 7.43 fl OZ', 1, 273000, '220 Ml', 176, 'Dengan kemampuan Power Booster, produk ini dapat dengan cepat membersihkan kotoran pada mesin, sisa minyak gemuk, pelumas dan cairan rem yang membandel.\r\nMembersihkan secara efektif tanpa pembongkaran.\r\nMeningkatkan umur suku cadang mesin dan kinerja rem.\r\n\r\nFungsi:\r\nDiformulasikan untuk membersihkan permukaan disk brake tanpa harus dibongkar. Bekerja sangat cepat melarutkan dan', 'Box', 'Foto_Produk_1_687f5d993426b8.93334485_6383.jpg', 'Foto_Produk_2_687f5d9934fcd9.67819345_1470.jpg', '', 'Masih Diproduksi', '2025-08-26 04:16:05'),
(15, 'Rexco 82 Spare Part & Brake Cleaner 500 ML/ 395 G/ 16.9 fl OZ', 1, 415000, '500 Ml', 395, 'Dengan kemampuan Power Booster, produk ini dapat dengan cepat membersihkan kotoran pada mesin, sisa minyak gemuk, pelumas dan cairan rem yang membandel.\r\nMembersihkan secara efektif tanpa pembongkaran.\r\nMeningkatkan umur suku cadang mesin dan kinerja rem.\r\n\r\nFungsi:\r\nDiformulasikan untuk membersihkan permukaan disk brake tanpa harus dibongkar. Bekerja sangat cepat melarutkan dan', 'Box', 'Foto_Produk_1_687f5df472da03.72973114_7300.jpg', 'Foto_Produk_2_687f5df4731658.07405824_2560.jpg', 'Foto_Produk_3_687f5df4756c76.73242228_3123.jpg', 'Masih Diproduksi', '2025-08-26 04:16:05');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_invoice` int(11) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `jumlah_pembayaran` int(11) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `status_transaksi` enum('Berhasil','Dibatalkan','') NOT NULL,
  `tgl_perbarui` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_manajer` (`id_manajer`);

--
-- Indeks untuk tabel `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  ADD PRIMARY KEY (`id_detail_pesanan`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `tb_invoice`
--
ALTER TABLE `tb_invoice`
  ADD PRIMARY KEY (`id_invoice`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indeks untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tb_manajer`
--
ALTER TABLE `tb_manajer`
  ADD PRIMARY KEY (`id_manajer`);

--
-- Indeks untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_invoice` (`id_invoice`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  MODIFY `id_detail_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `tb_invoice`
--
ALTER TABLE `tb_invoice`
  MODIFY `id_invoice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_manajer`
--
ALTER TABLE `tb_manajer`
  MODIFY `id_manajer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD CONSTRAINT `tb_admin_ibfk_1` FOREIGN KEY (`id_manajer`) REFERENCES `tb_manajer` (`id_manajer`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  ADD CONSTRAINT `tb_detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `tb_pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `tb_produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_invoice`
--
ALTER TABLE `tb_invoice`
  ADD CONSTRAINT `tb_invoice_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `tb_pesanan` (`id_pesanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD CONSTRAINT `tb_pesanan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `tb_pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_pesanan_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `tb_admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD CONSTRAINT `tb_produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_ibfk_1` FOREIGN KEY (`id_invoice`) REFERENCES `tb_invoice` (`id_invoice`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
