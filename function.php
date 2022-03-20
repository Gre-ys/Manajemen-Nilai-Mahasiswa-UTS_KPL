<?php
// koneksi ke db
$conn = mysqli_connect("localhost", "root", "", "uts_kpl");

// fungsi read data
function queryGetData($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);

    $rows = [];
    while ($tmp = mysqli_fetch_assoc($result)) {
        $rows[] = $tmp;
    }
    return $rows;
}

// fungsi tambah data
function insertData($data)
{
    global $conn;

    // pass data ke var untuk dimasukan ke database
    $npm = htmlspecialchars($data["npm"]);
    $nama = htmlspecialchars($data["nama"]);
    $nama_mk = htmlspecialchars($data["nama_mk"]);
    $nKehadiran = (float) htmlspecialchars($data["nKehadiran"]);
    $nTugas = (float) htmlspecialchars($data["nTugas"]);
    $nQuiz = (float) htmlspecialchars($data["nQuiz"]);
    $nUts = (float) htmlspecialchars($data["nUts"]);
    $nUas = (float) htmlspecialchars($data["nUas"]);
    $dosen_id = (int) htmlspecialchars($data["dosen_id"]);

    $total = round((0.1 * $nKehadiran) + (0.15 *  $nQuiz) + (0.10 * $nTugas) + (0.3 * $nUts) + (0.35 * $nUas), 2);
    $ip = round($total / 25, 2);
    if ($total >= 92) {
        $grade = 'A';
        $keterangan = "Istimewa";
    } else if ($total >= 86 && $total <= 91) {
        $grade = 'A-';
        $keterangan = "Hampir Istimewa";
    } elseif ($total >= 81 && $total <= 85) {
        $grade = 'B+';
        $keterangan = "Baik Sekali";
    } elseif ($total >= 76 && $total <= 80) {
        $grade = 'B';
        $keterangan = "Baik";
    } elseif ($total >= 71 && $total <= 75) {
        $grade = 'B-';
        $keterangan = "Cukup Baik";
    } elseif ($total >= 66 && $total <= 70) {
        $grade = 'C+';
        $keterangan = "Lebih Dari Cukup";
    } elseif ($total >= 60 && $total <= 65) {
        $grade = 'C';
        $keterangan = "Cukup";
    } elseif ($total >= 55 && $total <= 59) {
        $grade = 'D';
        $keterangan = "Kurang";
    } else {
        $grade = 'E';
        $keterangan = "Gagal";
    }


    // query tambah
    $query = "INSERT INTO mahasiswa VALUES ('','$npm','$nama','$nama_mk', '$nKehadiran', '$nTugas','$nQuiz','$nUts','$nUas','$total', '$ip','$grade', '$keterangan','$dosen_id')";

    // masukan data ke db
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    // keterangan berhasil tidak
    return mysqli_affected_rows($conn);
}


// fungsi hapus data
function deleteData($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id") or die(mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

// fungsi ubah data
function updateData($data)
{
    global $conn;

    // pass data ke var untuk dimasukan ke database
    $id = (int) htmlspecialchars($data["id"]);
    $npm = htmlspecialchars($data["npm"]);
    $nama = htmlspecialchars($data["nama"]);
    $nama_mk = htmlspecialchars($data["nama_mk"]);
    $nKehadiran = (float) htmlspecialchars($data["nKehadiran"]);
    $nTugas = (float) htmlspecialchars($data["nTugas"]);
    $nQuiz = (float) htmlspecialchars($data["nQuiz"]);
    $nUts = (float) htmlspecialchars($data["nUts"]);
    $nUas = (float) htmlspecialchars($data["nUas"]);

    $total = round((0.1 * $nKehadiran) + (0.15 *  $nQuiz) + (0.10 * $nTugas) + (0.3 * $nUts) + (0.35 * $nUas), 2);
    $ip = round($total / 25, 2);
    if ($total >= 92) {
        $grade = 'A';
        $keterangan = "Istimewa";
    } else if ($total >= 86 && $total <= 91) {
        $grade = 'A-';
        $keterangan = "Hampir Istimewa";
    } elseif ($total >= 81 && $total <= 85) {
        $grade = 'B+';
        $keterangan = "Baik Sekali";
    } elseif ($total >= 76 && $total <= 80) {
        $grade = 'B';
        $keterangan = "Baik";
    } elseif ($total >= 71 && $total <= 75) {
        $grade = 'B-';
        $keterangan = "Cukup Baik";
    } elseif ($total >= 66 && $total <= 70) {
        $grade = 'C+';
        $keterangan = "Lebih Dari Cukup";
    } elseif ($total >= 60 && $total <= 65) {
        $grade = 'C';
        $keterangan = "Cukup";
    } elseif ($total >= 55 && $total <= 59) {
        $grade = 'D';
        $keterangan = "Kurang";
    } else {
        $grade = 'E';
        $keterangan = "Gagal";
    }



    // query ubah
    $query = "UPDATE mahasiswa SET npm = '$npm', nama = '$nama', nama_mk = '$nama_mk', nKehadiran='$nKehadiran' ,nTugas = '$nTugas', nQuiz = '$nQuiz', nUts = '$nUts', nUas = '$nUas', total = '$total', ip='$ip', grade = '$grade', keterangan = '$keterangan' WHERE id = $id";

    // masukan data ke db
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    // keterangan berhasil tidak
    return mysqli_affected_rows($conn);
}

// fungsi cari
function searchData($keyword, $dosen_id)
{
    // query pencarian

    $query = "SELECT mahasiswa.id, mahasiswa.npm, mahasiswa.nama, mahasiswa.nama_mk, mahasiswa.total, mahasiswa.ip, mahasiswa.grade, mahasiswa.keterangan FROM mahasiswa INNER JOIN dosen ON mahasiswa.dosen_id = dosen.id WHERE mahasiswa.dosen_id = $dosen_id AND (mahasiswa.npm LIKE '%$keyword%' OR mahasiswa.nama LIKE '%$keyword%' OR mahasiswa.nama_mk LIKE '%$keyword%' OR mahasiswa.total LIKE '%$keyword%' OR mahasiswa.grade LIKE '%$keyword%' OR mahasiswa.ip LIKE '%$keyword%' OR mahasiswa.keterangan LIKE '%$keyword%')";

    //$query = "SELECT * FROM mahasiswa WHERE npm LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR total LIKE '%$keyword%' OR grade LIKE '%$keyword%'";

    return queryGetData($query);
}

// fungsi registrasi
function register($data)
{
    global $conn;

    $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
    $password2 = htmlspecialchars(mysqli_real_escape_string($conn, $data["passwordCon"]));

    // cek username dan password diisi kosong atau tidak
    if (empty($username) || empty($password) || empty($password2)) {
        echo
        "
        <script>
        alert('Username atau password tidak boleh kosong!');
        </script>
        ";
        return false;
    }
    // cek username ada atau belum
    if (queryGetData("SELECT * FROM dosen WHERE username = '$username'")) {
        echo "<script>
        alert('Username sudah digunakan');
        </script>
        ";
        return false;
    }

    // cek apakah konfirmasi password benar
    if ($password !== $password2) {
        echo "<script>
        alert('Konfirmasi password salah');
        </script>
        ";
        return false;
    }

    // cek apakah password kurang dari 5 karakter atau tidak
    if (strlen($password) < 5) {
        echo "<script>
        alert('Password tidak boleh kurang dari 5 karakter');
        </script>
        ";
        return false;
    }

    // password di enkripsi
    $password = password_hash($password, PASSWORD_DEFAULT);

    // masukan ke database
    $query = "INSERT INTO dosen VALUES('','$username','$password') ";
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

// fungsi login
function login($data)
{
    $username = $data["username"];
    $password = $data["password"];

    // cek username
    if ($cek = queryGetData("SELECT * FROM dosen WHERE username = '$username'")) {
        // cek password
        if (password_verify($password, $cek[0]["password"])) {

            // set session, klo set gaush aktifin dulu fungsi session start
            $_SESSION["login"] = true;
            $_SESSION["id"] = $cek[0]["id"];

            header("Location: index.php");
            exit();
        }
    }

    return [
        "error" => true,
        "pesan" => "Username/Password salah!"
    ];
}
