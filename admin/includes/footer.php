      <!-- Footer -->
      <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                </li>
                <li class="nav-item">
                </li>
                <li class="nav-item">
                </li>
              </ul>
            </nav>
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; PT Indo Kimia Abadi 2025</span>
            </div>
            <div>
            </div>
          </div>
        </footer>
      <!-- End Footer -->
      </div>
      <!-- End Div Wrapper -->
    </div>

    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 5,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        // SweetAlert
        $("#add-row").DataTable({
          pageLength: 5,
        });

        var action =
          '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function () {
          $("#add-row")
            .dataTable()
            .fnAddData([
              $("#addName").val(),
              $("#addPosition").val(),
              $("#addOffice").val(),
              action,
            ]);
          $("#addRowModal").modal("hide");
        });
      });
    </script>

    <!-- SweetAlert Konfirmasi Lagout-->
    <script>
      function confirmLogout() {
        swal({
          title: "Konfirmasi Logout",
          text: "Apakah Anda yakin ingin keluar?",
          icon: "warning",
          buttons: {
            cancel: {
              text: "Batal",
              visible: true,
              className: "btn btn-primary",
            },
            confirm: { 
              text: "Ya, Logout",
              className: "btn btn-danger",
            },
          },
        }).then((willLogout) => {
          if (willLogout) {
            window.location.href = "logout.php"; // Redirect ke logout
          }
        });
      }
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const blockedLinks = document.querySelectorAll("a.manajer-link.blocked");
        blockedLinks.forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                swal({
                    title: "Akses Ditolak",
                    text: "Hanya manajer yang dapat mengakses menu ini.",
                    icon: "warning",
                    button: {
                        text: "Oke",
                        className: "btn btn-success"
                    }
                });
            });
        });
    });
    </script>

    <!-- SweetAlert Data Berhasil Ditambahkan (Add Success)-->
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'add_success'): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
            swal("Berhasil!", "Data berhasil ditambahkan!", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: "btn btn-success",
                        text: "Oke"
                    },
                },
            }).then(() => {
                window.location.href = window.location.href.split('?')[0]+ "?halaman=<?php echo $_GET['halaman']; ?>";
            });
    });
    </script>
    <?php unset($_SESSION['status']); endif; ?> <!-- Hapus session setelah digunakan -->

    <!-- SweetAlert Data Gagal Ditambahkan (Add Failed)-->
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'add_failed'): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
            swal("Gagal!", "Data gagal ditambahkan!", {
                icon: "error",
                buttons: {
                    confirm: {
                        className: "btn btn-danger",
                        text: "Oke"
                    },
                },
            }).then(() => {
                window.location.href = window.location.href.split('?')[0]+ "?halaman=<?php echo $_GET['halaman']; ?>";
            });
    });
    </script>
    <?php unset($_SESSION['status']); endif; ?> <!-- Hapus session setelah digunakan -->

    <!-- SweetAlert Data Berhasil Diupdate-->
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'update_success'): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // SweetAlert Update Data
            swal("Berhasil!", "Data berhasil diperbarui!", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: "btn btn-success",
                        text: "Oke"
                    },
                },
            }).then(() => {
                window.location.href = window.location.href.split('?')[0] + "?halaman=<?php echo $_GET['halaman']; ?>"; // Refresh halaman
            });
    });
    </script>
    <?php unset($_SESSION['status']); endif; ?> <!-- Hapus session setelah digunakan -->

    <!-- SweetAlert Data Berhasil Diupdate-->
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'update_failed'): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // SweetAlert Update Data
            swal("Gagal!", "Data gagal diperbarui!", {
                icon: "error",
                buttons: {
                    confirm: {
                        className: "btn btn-danger",
                        text: "Oke"
                    },
                },
            }).then(() => {
                window.location.href = window.location.href.split('?')[0] + "?halaman=<?php echo $_GET['halaman']; ?>"; // Refresh halaman
            });
    });
    </script>
    <?php unset($_SESSION['status']); endif; ?> <!-- Hapus session setelah digunakan -->
    
    <!-- SweetAlert Data Berhasil Dihapus-->
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'delete_success'): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // SweetAlert Hapus Data
            swal("Dihapus!", "Data berhasil dihapus!", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: "btn btn-success",
                        text: "Oke"
                    },
                },
            }).then(() => {
                window.location.href = window.location.href.split('?')[0]+"?halaman=<?php echo $_GET['halaman']; ?>"; // Refresh halaman
            });
        });
    </script>
    <?php unset($_SESSION['status']); endif;?> <!-- Hapus session setelah digunakan -->

    <!-- SweetAlert Data ID Tidak Benar-->
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'id_invalid'): ?>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // SweetAlert Update Data
            swal("Peringatan!", "ID tidak ditemukan!", {
                icon: "warning",
                buttons: {
                    confirm: {
                        className: "btn btn-primary",
                        text: "Oke"
                    },
                },
            }).then(() => {
                window.location.href = window.location.href.split('?')[0] + "?halaman=<?php echo $_GET['halaman']; ?>"; // Refresh halaman
            });
    });
    </script>
    <?php unset($_SESSION['status']); endif;?> <!-- Hapus session setelah digunakan -->

    <script>
    // Swertalert Konfirmasi Hapus Data
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll(".btn-delete").forEach(function(button) {
          button.addEventListener("click", function() {
              var id = this.getAttribute("data-id");
              var halaman = "<?php echo $_GET['halaman']; ?>";

              swal({
                  title: "Apakah Anda yakin?",
                  text:  "Data ini akan dihapus secara permanen!",
                  icon:  "warning",
                  buttons: {
                    confirm:{
                      text: "Ya",
                      className: "btn btn-primary",
                      value: true,
                      visible: true,
                    },
                    cancel: {
                      text: "Batal",
                      className: "btn btn-danger",
                      visible: true,
                    },
                  },
              }).then((willDelete) => {
                  if (willDelete) {
                      window.location.href = "index.php?halaman=" + halaman + "&hapus=" + id;
                      }
                  });
              });
          });
    });
    </script>
  </body>
</html>

