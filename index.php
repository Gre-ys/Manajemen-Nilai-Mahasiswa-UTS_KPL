<?php
session_start();

// Import Function
require 'function.php';

// Id dosen untuk join table
$dosen_id = $_SESSION["id"];

// Var Global untuk menandai Update
$GLOBALS["isUpdate"] = false;

// Log Out
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



// Algoritma Trigger Delete Data
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

// Algoritma Trigger Update Data
if (isset($_GET["update_id"])) {

    $id = $_GET["update_id"];

    $mhsUpdateRaw = queryGetData("SELECT * FROM mahasiswa WHERE id=$id");
    $mhsUpdate = $mhsUpdateRaw[0];

    $GLOBALS["isUpdate"] = true;



    if (isset($_POST["submit_updateData"])) {
        // cek data berhasil diubah atau tidak
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
}

// Algoritma Trigger Insert Data
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


// Ambil data untuk ditampilkan
$mahasiswa = queryGetData("SELECT mahasiswa.id, mahasiswa.npm, mahasiswa.nama, mahasiswa.nama_mk, mahasiswa.total, mahasiswa.ip, mahasiswa.grade, mahasiswa.keterangan FROM mahasiswa INNER JOIN dosen ON mahasiswa.dosen_id = dosen.id WHERE mahasiswa.dosen_id = $dosen_id");

// Ambil data sesuai yang dicari
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
    <title>Laman Penilaian</title>
</head>

<body>
    <main class="container shadow p-3 mb-5 bg-body rounded m-auto mt-5">
        <div class="row mb-2 d-flex">
            <div class="col-4">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#insertDataModal">+ Tambah Data</button>
            </div>
            <div class="col-8 d-flex justify-content-end">
                <button type="button" class="btn btn-outline-danger"><a href="index.php?logout=<?= true ?>" class="text-reset text-decoration-none">Log Out</a></button>
            </div>
        </div>
        <form action="" method="POST">
            <div class="input-group mb-3 mt-4">
                <input type="text" class="form-control" placeholder="Cari Data..." name="keyword">
                <button type="submit" class="btn btn-outline-success col-4" type="button" name="submit_keyword">Cari</button>
            </div>
        </form>
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
                                <div class="d-flex justify-content-around">
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin Menghapus Data?')"><a href="index.php?delete_id=<?= $mhs["id"]; ?>" class="text-reset text-decoration-none">Delete</a></button>
                                    <button type="button" class="btn btn-success btn-sm justify-self-end" data-bs-toggle="<?php if (isset($GLOBALS["isUpdate"])) echo "modal";
                                                                                                                            else echo "" ?>" data-bs-target="#updateDataModal"><a href="index.php?update_id=<?= $mhs["id"]; ?>" class="text-reset text-decoration-none">Update</a></button>
                                </div>
                            </td>
                        </tr>
                        <?php $i++ ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal Insert Data-->
    <div class="modal fade" id="insertDataModal" tabindex="-1" aria-labelledby="insertDataModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Insert Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="dosen_id" value="<?= $dosen_id ?>">
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="npm" name="npm" placeholder="NPM Mahasiswa" required>
                            <label for="npm">NPM Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nama" name="nama" placeholder="Nama Mahasiswa" required>
                            <label for="Nama">Nama Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nama Mata Kuliah" name="nama_mk" placeholder="Nama Mata Kuliah Mahasiswa" required>
                            <label for="Nama Mata Kuliah">Nama Mata Kuliah</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai Kehadiran" name="nKehadiran" placeholder="Nilai Kehadiran Mahasiswa" required>
                            <label for="Nilai Kehadiran">Nilai Kehadiran&Keaktifan Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai Tugas" name="nTugas" placeholder="Nilai Tugas Mahasiswa" required>
                            <label for="Nilai Tugas">Nilai Tugas Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai Quiz" name="nQuiz" placeholder="Nilai Quiz Mahasiswa" required>
                            <label for="Nilai Quiz">Nilai Quiz Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai UTS" name="nUts" placeholder="Nilai UTS Mahasiswa" required>
                            <label for="Nilai UTS">Nilai UTS Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai UAS" name="nUas" placeholder="Nilai UAS Mahasiswa" required>
                            <label for="Nilai UAS">Nilai UAS Mahasiswa</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" name="submit_insertData" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Update Data-->
    <div class="modal fade" id="updateDataModal" tabindex="-1" aria-labelledby="updateDataModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="document.location.href='index.php'; <?php $GLOBALS['isUpdate'] = false ?>"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $mhsUpdate["id"] ?>">
                    <input type="hidden" name="dosen_id" value="<?= $dosen_id ?>">
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="npm" name="npm" placeholder="NPM Mahasiswa" required value="<?= $mhsUpdate["npm"] ?>">
                            <label for="npm">NPM Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nama" name="nama" placeholder="Nama Mahasiswa" required value="<?= $mhsUpdate["nama"] ?>">
                            <label for="Nama">Nama Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nama Mata Kuliah" name="nama_mk" placeholder="Nama Mata Kuliah Mahasiswa" required value="<?= $mhsUpdate["nama_mk"] ?>">
                            <label for="Nama Mata Kuliah">Nama Mata Kuliah</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai Kehadiran" name="nKehadiran" placeholder="Nilai Kehadiran Mahasiswa" required value="<?= $mhsUpdate["nKehadiran"] ?>">
                            <label for="Nilai Kehadiran">Nilai Kehadiran&Keaktifan Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai Tugas" name="nTugas" placeholder="Nilai Tugas Mahasiswa" required value="<?= $mhsUpdate["nTugas"] ?>">
                            <label for="Nilai Tugas">Nilai Tugas Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai Quiz" name="nQuiz" placeholder="Nilai Quiz Mahasiswa" required value="<?= $mhsUpdate["nQuiz"] ?>">
                            <label for="Nilai Quiz">Nilai Quiz Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai UTS" name="nUts" placeholder="Nilai UTS Mahasiswa" required value="<?= $mhsUpdate["nUts"] ?>">
                            <label for="Nilai UTS">Nilai UTS Mahasiswa</label>
                        </div>
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" id="Nilai UAS" name="nUas" placeholder="Nilai UAS Mahasiswa" required value="<?= $mhsUpdate["nUas"] ?>">
                            <label for="Nilai UAS">Nilai UAS Mahasiswa</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger" onclick="<?php $GLOBALS['isUpdate'] = false ?>">Reset</button>
                        <button type="submit" name="submit_updateData" class="btn btn-primary" onclick="<?php $GLOBALS['isUpdate'] = false ?>">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>