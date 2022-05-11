<?php
session_start();

// Import Function
require 'function.php';

// Id Dosen yang Login untuk Query Join Table
$dosen_id = $_SESSION["id"];

// Handler Tombol Log Out
if (isset($_GET["logout"])) {
    $_SESSION["login"] = "";
    session_unset();
    session_destroy();

    header("Location: login.php");
}

// Cek Login atau Belum
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
}


// Handler Tombol Delete Data
if (isset($_GET["delete_id"])) {
    if (deleteData($_GET["delete_id"]) > 0) {
        echo
        "
            <script>
            alert('Data Berhasil dihapus');
            document.location.href = 'index.php';
            </script>
            ";
    } else {
        echo
        "
            <script>
            alert('Data Gagal dihapus');
            document.location.href = 'index.php';
            </script>
            ";
    }
}

// Handler Tombol Update Data
if (isset($_POST["submit_updateData"])) {

    // Cek Data Berhasil Diubah atau Tidak
    if (updateData($_POST) > 0) {
        echo
        "
        <script>
        alert('Data Berhasil diubah');
        document.location.href = 'index.php';
        </script>
        ";
    } else {
        echo
        "
        <script>
        alert('Data Gagal diubah');
        document.location.href = 'index.php';
        </script>
        ";
    }
}

// Handler Tombol Insert Data
if (isset($_POST["submit_insertData"])) {

    //cek data berhasil ditambah atau tidak
    if (insertData($_POST) > 0) {
        echo
        "
        <script>
        alert('Data Berhasil ditambahkan');
        document.location.href = 'index.php';
        </script>
        ";
    } else {
        echo
        "
        <script>
        alert('Data Gagal ditambahkan');
        document.location.href = 'index.php';
        </script>
        ";
    }
}


// Ambil Data untuk Ditampilan
$mahasiswa = queryGetData("SELECT mahasiswa.id, mahasiswa.npm, mahasiswa.nama, mahasiswa.nama_mk, mahasiswa.total, mahasiswa.ip, mahasiswa.grade, mahasiswa.keterangan FROM mahasiswa WHERE dosen_id = $dosen_id");

// Ambil Data Sesuai Keyword Pencarian
if (isset($_POST["submit_keyword"])) {
    $mahasiswa = searchData($_POST["keyword"], $dosen_id);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <title>Laman Penilaian</title>
</head>

<body>
    <main class="container shadow p-3 mb-5 bg-body rounded m-auto mt-5">
        <div class="row mb-2 d-flex">
            <!-- Tombol Trigger Modal untuk Insert/Tambah Data -->
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal_form" id="trigger_insert">+ Tambah Data</button>
            </div>
            <!-- Tombol Logout -->
            <div class="col-8 d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger"><a href="index.php?logout=<?= true ?>" class="text-reset text-decoration-none">Log Out</a></button>
            </div>
        </div>
        <!-- Form dan Tombol Cari -->
        <form action="" method="POST">
            <div class="input-group mb-3 mt-4 row">
                <div class="col-4">
                    <input type="text" class="form-control" placeholder="Cari Data..." name="keyword">
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-outline-success col-4" type="button" name="submit_keyword">Cari</button>
                </div>
            </div>
        </form>
        <!-- Tabel yang Menampilkan Data -->
        <div class="table-responsive-md">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>NPM</th>
                        <th>Nama</th>
                        <th>Mata Kuliah</th>
                        <th>Nilai</th>
                        <th>IP</th>
                        <th>Grade</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1 ?>
                    <?php foreach ($mahasiswa as $mhs) : ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $mhs["npm"]; ?></td>
                            <td><?= $mhs["nama"]; ?></td>
                            <td><?= $mhs["nama_mk"]; ?></td>
                            <td><?= $mhs["total"]; ?></td>
                            <td><?= $mhs["ip"]; ?></td>
                            <td><?= $mhs["grade"]; ?></td>
                            <td><?= $mhs["keterangan"]; ?></td>
                            <td>
                                <div class="row">
                                    <!-- Tombol Delete Data -->
                                    <button class="btn btn-danger btn-sm col-4" onclick="return confirm('Yakin Menghapus Data?')"><a href="index.php?delete_id=<?= $mhs["id"]; ?>" class="text-reset text-decoration-none">Delete</a></button>
                                    <div class='col-1'></div>
                                    <!-- Tombol Trigger Modal Update Data -->
                                    <button type="button" class="btn btn-success btn-sm justify-self-end col-4 trigger_update" data-bs-toggle="modal" data-bs-target="#modal_form" data-id="<?= $mhs['id']; ?>">Update</button>
                                </div>
                            </td>
                        </tr>
                        <?php $i++ ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal untuk Insert/Tambah dan Update Data-->
    <div class="modal fade" id="modal_form" tabindex="-1" aria-labelledby="modal_form" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_label">Insert Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name='id'>
                    <input type="hidden" name="dosen_id" value="<?= $dosen_id ?>">
                    <div class="modal-body row">
                        <div class="col-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="npm" name="npm" placeholder="NPM Mahasiswa" required>
                                <label for="npm">NPM Mahasiswa</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="nama" name="nama" placeholder="Nama Mahasiswa" required>
                                <label for="Nama">Nama Mahasiswa</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="nama_mk" name="nama_mk" placeholder="Nama Mata Kuliah Mahasiswa" required>
                                <label for="Nama Mata Kuliah">Nama Mata Kuliah</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="nKehadiran" name="nKehadiran" placeholder="Nilai Kehadiran Mahasiswa" required>
                                <label for="Nilai Kehadiran">Nilai Keaktifan&Kehadiran Mahasiswa</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="nTugas" name="nTugas" placeholder="Nilai Tugas Mahasiswa" required>
                                <label for="Nilai Tugas">Nilai Tugas Mahasiswa</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="nQuiz" name="nQuiz" placeholder="Nilai Quiz Mahasiswa" required>
                                <label for="Nilai Quiz">Nilai Quiz Mahasiswa</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="nUts" name="nUts" placeholder="Nilai UTS Mahasiswa" required>
                                <label for="Nilai UTS">Nilai UTS Mahasiswa</label>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="text" class="form-control form-control-sm" id="nUas" name="nUas" placeholder="Nilai UAS Mahasiswa" required>
                                <label for="Nilai UAS">Nilai UAS Mahasiswa</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" name="submit_insertData" class="btn btn-primary" id="modal_button">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>