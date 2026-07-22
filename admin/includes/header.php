<?php
include '../koneksi.php';

ob_start();
if (session_status() == PHP_SESSION_NONE) { 
  session_start();
}

$admin = false;
$manajer = false;
$username = '';
$user_data = null;

if(isset($_SESSION['username_admin'])) {
  $username = $_SESSION['username_admin'];
  $admin = true;
  // Query untuk mengambil data berdasarkan akun admin yang digunakan
  $query = $con->prepare("SELECT * FROM tb_admin WHERE username_admin = ?");
  $query->bind_param("s", $username);
  $query->execute();
  $result = $query->get_result();
  $user_data = $result->fetch_assoc();
} elseif(isset($_SESSION['username_manajer'])) {
  $username = $_SESSION['username_manajer'];
  $manajer = true;
  // Query untuk mengambil data berdasarkan akun manajer yang digunakan
  $query = $con->prepare("SELECT * FROM tb_manajer WHERE username_manajer = ?");
  $query->bind_param("s", $username);
  $query->execute();
  $result = $query->get_result();
  $user_data = $result->fetch_assoc();
}

// Jika data tidak ditemukan, logout paksa
if (!$user_data) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$admin_name = '';
$admin_email = '';
$admin_foto = '';
$admin_id = null;
$detailProfil = '';
$editProfil = '';

if ($admin){
  $admin_name = $user_data['nama_admin'];
  $admin_email = $user_data['email_admin'];
  $admin_foto = $user_data['foto_admin'];
  $admin_id = $user_data['id_admin'];
  $detailProfil = 'detailadmin';
  $editProfil = 'editadmin';
} elseif ($manajer) {
  
  $admin_name = $user_data['nama_manajer'];
  $admin_email = $user_data['email_manajer'];
  $admin_foto = $user_data['foto_manajer'];
  $admin_id = $user_data['id_manajer'];
  $detailProfil = 'detailmanajer';
  $editProfil = 'editmanajer';
}

// Query untuk mengambil data pelanggan
$query_pelanggan = $con->query("SELECT * FROM tb_pelanggan ORDER BY id_pelanggan DESC LIMIT 5");
$pelanggan_baru = $query_pelanggan->fetch_all(MYSQLI_ASSOC);

// Fuction untuk Insial
function getInitials($nama){
  $insial_pelanggan = explode(' ', $nama);
  $initials = '';
  foreach ($insial_pelanggan as $insial) {
    if (isset($insial[0])){
      $initials .= strtoupper($insial[0]);
    }
  }
  return substr($initials, 0, 2);
}

// Function Warna Background Pelanggan
function getRandomColorClass(){
  $colors = ['primary','success','info','warning','danger','secondary'];
  return $colors[array_rand($colors)];
}

// Query untuk mengambil data pemesanan
$query_pesanan = $con->query("SELECT * FROM tb_pesanan ORDER BY id_pesanan DESC LIMIT 5");
$pesanan_baru = $query_pesanan->fetch_all(MYSQLI_ASSOC);

$date_now = date('Y-m-d');
$con->query("UPDATE tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan SET tb_pesanan.status_pesanan = 'Dibatalkan' WHERE tb_invoice.jatuh_tempo < '$date_now' AND tb_pesanan.status_pesanan = 'Menunggu Pembayaran'");
$con->query("UPDATE tb_invoice JOIN tb_pesanan ON tb_invoice.id_pesanan = tb_pesanan.id_pesanan SET tb_invoice.status_invoice = 'Kedaluwarsa' WHERE tb_invoice.jatuh_tempo < '$date_now' AND tb_invoice.status_invoice = 'Belum Bayar'");
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PT Indo Kimia Abadi - Admin</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />
    <style>
      .logo-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 10px 20px;
        box-sizing: border-box;
      }
      
      .logo-header .logo {
        margin-right: auto;
        margin-left: auto;
        flex-shrink: 0;
      }

      .logo-header .logo img {
          display: block;
          margin: 0 auto;
      }

      .logo-header .header-right-buttons {
          display: flex;
          align-items: center;
          gap: 10px;
          flex-shrink: 0;
      }

      .logo-header .nav-toggle {
          display: flex;
          align-items: center;
          gap: 5px;
      }

      .logo-header button {
          background: none;
          border: none;
          cursor: pointer;
          font-size: 1.5em;
          color: #333;
      }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <?php include 'sidebar.php'; ?>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar avatar-online">
                      <img
                        src="../images/foto_admin/<?php echo htmlspecialchars($admin_foto); ?>"
                        alt="Foto Profil"
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold"><?php echo htmlspecialchars($admin_name);?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="../images/foto_admin/<?php echo htmlspecialchars($admin_foto); ?>"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4><?php echo htmlspecialchars($admin_name);?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($admin_email);?></p>
                            <a
                              href="index.php?halaman=<?php echo $detailProfil; ?>&id=<?php echo $admin_id; ?>"
                              class="btn btn-xs btn-primary btn-sm"
                              >Lihat Profil</a
                            >
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.php?halaman=<?php echo $editProfil ?>&id=<?php echo $admin_id; ?>">Edit Profil</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="confirmLogout();">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>
