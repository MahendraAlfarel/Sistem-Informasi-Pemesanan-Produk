 <!-- Start Top Nav -->
 <nav class="navbar navbar-expand-lg bg-black navbar-light d-none d-lg-block" id="templatemo_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="#"><i class="fa fa-envelope mx-1"></i>office@indokimiaabadi.com</a>
                    <span class="vertical-line mx-1"></span>
                    <a class="navbar-sm-brand text-light text-decoration-none" href="#"><i class="fa fa-phone mx-1"></i>021-29007072</a>
                </div>
                <div>
                    <?php
                    if(!isset($_SESSION['username_pelanggan'])){
                        echo '<a class="navbar-sm-brand text-light text-decoration-none" href="register.php"><i class="fa fa-user-edit mx-1"></i>Daftar</a>';
                    }else{
                        echo '<a class="navbar-sm-brand text-light text-decoration-none" href="profil.php">'.$_SESSION['nama_pelanggan'].'</a>';
                    }
                    ?>
                    <span class="vertical-line mx-1"></span>
                    <?php
                    if(!isset($_SESSION['username_pelanggan'])){
                        echo '<a class="navbar-sm-brand text-light text-decoration-none" href="login.php"><i class="fa fa-sign-in-alt mx-2"></i>Masuk</a>';
                    }else{
                        echo '<a class="navbar-sm-brand text-light text-decoration-none btn-logout" href="#"><i class="fa fa-sign-out-alt mx-2"></i>Keluar</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">
                <div>
                    <a href="index.php" class="logo">
                    <img src="assets/img/logo.svg" height="75" width="75" alt="Indo Kimia Abadi">
                    </a>
                </div>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#templatemo_main_nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="templatemo_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="shop.php">Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Kontak</a>
                        </li>
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <div class="d-lg-none flex-sm-fill mt-3 mb-4 col-7 col-sm-auto pr-3">
                        <form action="shop.php" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" id="inputMobileSearch" placeholder="Search ..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <div class="input-group-text">
                                <button type="submit" class="btn p-0 border-0 bg-transparent">
                                    <i class="fa fa-fw fa-search"></i>
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <a class="nav-icon d-none d-lg-inline" href="#" data-bs-toggle="modal" data-bs-target="#templatemo_search">
                        <i class="fa fa-fw fa-search text-dark mr-2"></i>
                    </a>
                    <?php if(!isset($_SESSION['username_pelanggan'])): ?>
                    <a class="nav-icon position-relative text-decoration-none" href="#" onclick="loginRequired(event)">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"></span>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="#" onclick="loginRequired(event)">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                    </a>
                    <?php else: ?>
                    <?php 
                        $jumlah_produk_keranjang = 0;
                        if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
                            $jumlah_produk_keranjang = count($_SESSION['keranjang']);
                        }
                    ?>
                    <a class="nav-icon position-relative text-decoration-none" href="keranjang.php">
                        <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"><?php echo $jumlah_produk_keranjang; ?></span>
                    </a>
                    <a class="nav-icon position-relative text-decoration-none" href="profil.php">
                        <i class="fa fa-fw fa-user text-dark mr-3"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->

    <!-- Modal -->
    <div class="modal fade bg-white" id="templatemo_search" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="w-100 pt-1 mb-5 text-right">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="shop.php" method="get" class="modal-content modal-body border-0 p-0">
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="inputModalSearch" name="search" placeholder="Search ...">
                    <button type="submit" class="input-group-text bg-primary text-light">
                        <i class="fa fa-fw fa-search text-white"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>