<?php
include '../functions/functions.php';
// Proses Tambah Kategori
if (isset($_POST['tambah'])) {
    $nama_kategori = trim($_POST['nama']);
    $status = 'Aktif';

    if (validasiFoto('gambar')) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $gambar_kategori = uniqid('kategori_', true) . '.' . $ext;
        $lokasi = $_FILES['gambar']['tmp_name'];

        move_uploaded_file($lokasi, "../images/foto_kategori/" . $gambar_kategori);
    } else {
        $_SESSION['status'] = 'add_failed';
        header("Location: index.php?halaman=daftarkategori");
        exit;
    }

    if (
        empty($nama_kategori)
    ) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarkategori");
        exit();
    }

    $cek = $con->prepare("SELECT * FROM tb_kategori WHERE nama_kategori = ?");
    $cek->bind_param("s", $nama_kategori);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarkategori");
        exit();
    }

    $query = $con->prepare("INSERT INTO tb_kategori (nama_kategori, gambar_kategori, status_kategori) VALUES (?, ?, ?)");
    $query->bind_param("sss", $nama_kategori, $gambar_kategori, $status);
    if($query->execute()){
        $_SESSION['status'] = "add_success";
        header("Location: index.php?halaman=daftarkategori");
        $query->close();
        exit();
    } else {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarkategori");
        $query->close();
        exit();
    }
}

// Proses Edit Kategori
if (isset($_POST['edit'])) {
    $id_kategori = trim($_POST['id_kategori']);
    $nama_kategori = trim($_POST['nama_kategori']);
    $status_kategori = trim($_POST['status_kategori']);

    if (validasiFoto('gambar_kategori')) {
        $ext = strtolower(pathinfo($_FILES['gambar_kategori']['name'], PATHINFO_EXTENSION));
        $gambar_kategori = uniqid('kategori_', true) . '.' . $ext;
        $lokasi = $_FILES['gambar_kategori']['tmp_name'];

        move_uploaded_file($lokasi, "../images/foto_kategori/" . $gambar_kategori);
    }

    if (
        empty($nama_kategori) || empty($status_kategori)
    ) {
        $_SESSION['status'] = "add_failed";
        header("Location: index.php?halaman=daftarkategori");
        exit();
    }

    $cek = $con->prepare("SELECT * FROM tb_kategori WHERE nama_kategori = ? AND id_kategori != ?");
    $cek->bind_param("si", $nama_kategori, $id_kategori);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $_SESSION['status'] = "update_failed";
        header("Location: index.php?halaman=daftarkategori");
        exit();
    }

    if (!empty($gambar_kategori)) {
        $query = "UPDATE tb_kategori SET nama_kategori = ?, gambar_kategori = ?, status_kategori = ? WHERE id_kategori = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssi", $nama_kategori, $gambar_kategori, $status_kategori, $id_kategori);
    } else {
        $query = "UPDATE tb_kategori SET nama_kategori = ?, status_kategori = ? WHERE id_kategori = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssi", $nama_kategori, $status_kategori, $id_kategori);
    }

    if ($stmt->execute()) {
        $_SESSION['status'] = "update_success";
    } else {
        $_SESSION['status'] = "update_failed";
    }

    $stmt->close();
    header("Location: index.php?halaman=daftarkategori");
    exit();
}

// Proses Hapus Kategori
if (isset($_GET['hapus'])) {
    $id_kategori = $_GET['hapus'];
    $con->query("UPDATE tb_kategori SET status_kategori = 'Tidak Aktif' WHERE id_kategori = '$id_kategori'");
    $_SESSION['status'] = "delete_success";
}
?>

<!-- 
// Proses Hapus Kategori
if (isset($_GET['hapus'])) {
    $id_kategori = $_GET['hapus'];
    $con->query("DELETE FROM tb_kategori WHERE id_kategori='$id_kategori'");
    $_SESSION['status'] = "delete_success";
}
-->


<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Daftar Kategori</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?halaman=daftarkategori">Daftar Kategori</a></li>
            </ul>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Data Kategori</h4>
                        <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                            <i class="fa fa-plus"></i> Tambah Kategori
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Modal -->
                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title"><span class="fw-mediumbold">Tambah Kategori</span></h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="small">Masukkan Kategori Data</p>
                                    <form method="POST" action="" id="formtambahkategori" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group form-group-default">
                                                    <label>Nama Kategori</label>
                                                    <input type="text" id="nama_kategori" name="nama" class="form-control" placeholder="Masukkan Nama Kategori"/>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group form-group-default">
                                                    <div class="form-group">
                                                        <img id="displayImage" src="" width="100%" height="250" />
                                                    </div>
                                                    <label>Gambar Kategori</label>
                                                    <input type="file" id="gambar_kategori" name="gambar" class="form-control"  onchange="openFileKategori(event)" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel -->
                    <div class="table-responsive">
                        <table id="add-row" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Gambar Kategori</th>
                                    <th>Status Kategori</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $ambil = $con->query("SELECT * FROM tb_kategori");
                                while ($kategori = $ambil->fetch_assoc()) { 
                                    $id_kategori = $kategori['id_kategori'];
                                    $status_kategori = $kategori['status_kategori'] ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($kategori['nama_kategori']); ?></td>
                                        <td class="text-center"><img src="../images/foto_kategori/<?php echo $kategori['gambar_kategori']; ?>" width="100"></td>
                                        <td><?= htmlspecialchars($kategori['status_kategori']); ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" class="btn btn-link btn-primary btn-lg btn-edit" 
                                                    data-id="<?= $id_kategori; ?>" 
                                                    data-name="<?= htmlspecialchars($kategori['nama_kategori']); ?>"
                                                    data-gambar="<?= htmlspecialchars($kategori['gambar_kategori']); ?>"
                                                    data-status="<?= htmlspecialchars($kategori['status_kategori']); ?>">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <!-- <button type="button" class="btn btn-link btn-danger btn-delete"data-id="<?= $id_kategori; ?>">
                                                    <i class="fa fa-times"></i>
                                                </button> -->
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div> <!-- End Card Body -->
            </div> <!-- End Card -->
        </div> <!-- End col-md-12 -->
    </div> <!-- End page-inner -->
</div> <!-- End container -->

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title"><span class="fw-mediumbold">Edit Kategori</span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="small">Update Data Kategori</p>
                <form method="POST" action="" id="formeditkategori" enctype="multipart/form-data">
                    <input type="hidden" id="editId" name="id_kategori">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Nama Kategori</label>
                                <input type="text" id="editName" name="nama_kategori" class="form-control"/>
                            </div>
                            <div class="form-group form-group-default">
                                <img id="editGambar" src="" width="100%" height="250" />
                            </div>            
                            <div class="form-group form-group-default">
                                <label>Gambar Kategori</label>
                                <input type="file" name="gambar_kategori" class="form-control" onchange="openFile(event)">
                            </div>
                            <div class="form-group form-group-default">
                                <label>Status</label>
                                <select name="status_kategori" id="editStatus"  class="form-control">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Tidak Aktif">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk Menampilkan Data di Modal Edit -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-edit").forEach(function(button) {
            button.addEventListener("click", function() {
                var id = this.getAttribute("data-id");
                var name = this.getAttribute("data-name");
                var gambar = this.getAttribute("data-gambar");
                var status = this.getAttribute("data-status");
                document.getElementById("editId").value = id;
                document.getElementById("editName").value = name;
                document.getElementById("editStatus").value = status;
                document.getElementById("editGambar").src="../images/foto_kategori/" + gambar;
                var editModal = new bootstrap.Modal(document.getElementById('editRowModal'));
                editModal.show();
            });
        });
    });
</script>

<script>
    // Fungsi untuk menampilkan gambar sebelum upload
    function openFileKategori(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var imgElement = document.getElementById("displayImage");
            imgElement.src = reader.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

<script>
    // Fungsi untuk menampilkan gambar sebelum upload
    function openFile(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function() {
            var dataURL = reader.result;
            var output = document.getElementById("editGambar");
            output.src = dataURL;
        };
        reader.readAsDataURL(input.files[0]);
    }
</script>

<script>
    function showNotif(message, type = "danger", icon = "fa fa-times") {
        $.notify(
            {
                icon: icon,
                title: "Terjadi Kesalahan",
                message: message
            },
            {
                type: type,
                placement: {
                    from: "top",
                    align: "right",
                },
                time: 1000,
                delay: 3000,
            }
        );
    }

    document.getElementById("formtambahkategori").addEventListener("submit", function (e) {
        let nama = document.getElementById("nama_kategori").value.trim();
        let gambar = document.getElementById("gambar_kategori").value.trim();

        if (nama === ""){
            e.preventDefault();
            showNotif("Nama tidak boleh kosong");
            return false;
        }

        if (gambar === ""){
            e.preventDefault();
            showNotif("Gambar tidak boleh kosong");
            return false;
        }
    });

    document.getElementById("formeditkategori").addEventListener("submit", function (e) {
        var nama = document.querySelector("#formeditkategori input[name='nama_kategori']").value.trim();

        if (nama === ""){
            e.preventDefault();
            showNotif("Nama tidak boleh kosong");
            return false;
        }
    });
</script>