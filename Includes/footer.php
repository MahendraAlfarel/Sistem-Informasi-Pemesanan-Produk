    <!-- Start Footer -->
    <footer class="bg-black" id="tempaltemo_footer">
        <div class="container">
            <div class="row">

                <div class="col-md-3 pt-5">
                    <div class="col-md-4">
                        <img src="assets/img/logo.svg"  height="200" width="200" alt="Logo Indo Kimia Abadi">
                    </div>
                </div>

                <div class="col-md-3 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Informasi</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li>
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                            Jalan Millennium 11B Blok F6 no 5, Peusar, Kec. Panongan, Kabupaten Tangerang, Banten 15710
                        </li>
                        <li>
                            <i class="fa fa-phone fa-fw"></i>
                            <a class="text-decoration-none" href="tel:010-020-0340">021-29007072</a>
                        </li>
                        <li>
                            <i class="fa fa-envelope fa-fw"></i>
                            <a class="text-decoration-none" href="mailto:info@company.com">office@indokimiaabadi.com</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Kategori</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <?php $kategori = getKategori(); 
                        
                        foreach($kategori as $kategori): ?>
                        <li><a class="text-decoration-none" href="shop.php?kategori=<?= $kategori['id_kategori'] ?>"> <?= htmlspecialchars($kategori['nama_kategori']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-md-3 pt-5">
                    <h2 class="h2 text-light border-bottom pb-3 border-light">Menu</h2>
                    <ul class="list-unstyled text-light footer-link-list">
                        <li><a class="text-decoration-none" href="index.php">Beranda</a></li>
                        <li><a class="text-decoration-none" href="about.php">Profil Kami</a></li>
                        <li><a class="text-decoration-none" href="shop.php">Produk</a></li>
                        <li><a class="text-decoration-none" href="contact.php">Kontak Kami</a></li>
                    </ul>
                </div>
            </div>

            <div class="row text-light">
                <div class="col-12">
                    <div class="w-100 my-3 border-top border-light"></div>
                </div>
                <div class="col-auto me-auto">
                </div>
            </div>
        </div>

        <div class="w-100 bg-black py-3">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-12">
                        <p class="text-center text-light">
                            Copyright &copy; 2025 PT INDO KIMIA ABADI
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- <script src="assets/js/sweetalert.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script> -->
    <!-- End Script -->

    <!-- SweetAlert Konfirmasi Lagout-->
    <script>
    document.querySelectorAll('.btn-logout').forEach(function(button){
        button.addEventListener('click', function(e) {
        e.preventDefault();

            Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                cancelButton: 'btn btn-danger mx-2',
                confirmButton: 'btn btn-primary'
            },
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php'; // Redirect ke logout
            }
            });
        });
    });
    </script>

    <script>
        function loginRequired(e) {
            e.preventDefault();

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Mohon untuk login untuk akses halaman ini',
                confirmButtonText: 'Oke',
                allowOutsideClick: true
            }).then((result) => {
                if(result.isConfirmed) {
                    window.location.href = 'login.php';
                }
            });
        }
    </script>