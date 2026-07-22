<?php
session_start();
include 'koneksi.php';
include './functions/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact - PT Indo Kimia Abadi</title>
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
<!--
    
TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

-->
</head>

<body>
    <!-- Start Header -->
    <?php include 'includes/header.php';?>
    <!-- End Header -->

    <!-- Start Content -->
    <div class="container-fluid bg-light py-5">
        <div class="col-md-6 m-auto text-center">
            <h1 class="h1">Kontak Kami</h1>
        </div>
    </div>

    <!-- Start Contact -->
    <div class="container py-5">
        <div class="row py-5">
            <div class="col-md-9 m-auto">
                <h2>Hubungi Kami</h2>
                <p>
                    Jika terdapat pertanyaan, kami siap membantu. Hubungi layanan pelanggan 
                    Indo Kimia Abadi melalui kontak dibawah ini
                </p>
                <div class="separator"></div>
                <div class="contact-details">
                    <div class="detail-contact">
                        <div class="icon-contact">
                            <i class="fa fa-phone-alt"></i>
                        </div>
                        <div class="text mt-2">
                            <h5 class="text-black">Nomer Telepon</h5>
                            <p class="text-dark">021-29007072</p>
                        </div>
                    </div>
    
                    <div class="detail-contact">
                        <div class="contact">
                            <i class="fa fa-map-marker-alt"></i>
                        </div>
                        <div class="text mt-2">
                            <h5 class="text-black">Alamat Kantor</h5>
                            <p class="text-dark">Jalan Millennium 11B Blok F6 no 5, Peusar, Kec. Panongan, Kabupaten Tangerang, Banten 15710</p>
                        </div>
                    </div>
    
                    <div class="detail-contact">
                        <div class="contact">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="text mt-2">
                            <h5 class="text-black">Alamat Email</h3>
                            <p class="text-dark">office@indokimiaabadi.com</p>
                        </div>
                    </div>
                </div>
                <div class="separator"></div>
            </div>
        </div>
    </div>
    <!-- End Contact-->

    <!-- End Content -->

    <!-- Start Footer -->
    <?php include 'includes/footer.php';?>
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <!-- End Script -->
</body>

</html>