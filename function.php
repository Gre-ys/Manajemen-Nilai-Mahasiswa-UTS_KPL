<?php
// Koneksi ke DB
$conn = mysqli_connect("localhost", "root", "", "uts_kpl");

// Fungsi Read/Get Data
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

// Fungsi Tambah Data
function insertData($data)
{
    global $conn;

    // Pass Data ke Var untuk Diproses 
    $npm = htmlspecialchars($data["npm"]);
    $nama = htmlspecialchars($data["nama"]);
    $nama_mk = htmlspecialchars($data["nama_mk"]);
    $nKehadiran = (float) htmlspecialchars($data["nKehadiran"]);
    $nTugas = (float) htmlspecialchars($data["nTugas"]);
    $nQuiz = (float) htmlspecialchars($data["nQuiz"]);
    $nUts = (float) htmlspecialchars($data["nUts"]);
    $nUas = (float) htmlspecialchars($data["nUas"]);
    $dosen_id = (int) htmlspecialchars($data["dosen_id"]);

    // Perhitungan untuk Total dan IP
    $total = round((0.1 * $nKehadiran) + (0.15 *  $nQuiz) + (0.10 * $nTugas) + (0.3 * $nUts) + (0.35 * $nUas), 2);
    $ip = round($total / 25, 2);

    // Pemberian Grade Berdasarkan Nilai
    if ($total >= 92) {
        $grade = 'A';
        $keterangan = "Istimewa";
    } else if ($total >= 86 && $total < 92) {
        $grade = 'A-';
        $keterangan = "Hampir Istimewa";
    } elseif ($total >= 81 && $total < 86) {
        $grade = 'B+';
        $keterangan = "Baik Sekali";
    } elseif ($total >= 76 && $total < 81) {
        $grade = 'B';
        $keterangan = "Baik";
    } elseif ($total >= 71 && $total < 76) {
        $grade = 'B-';
        $keterangan = "Cukup Baik";
    } elseif ($total >= 66 && $total < 71) {
        $grade = 'C+';
        $keterangan = "Lebih Dari Cukup";
    } elseif ($total >= 60 && $total < 66) {
        $grade = 'C';
        $keterangan = "Cukup";
    } elseif ($total >= 55 && $total < 60) {
        $grade = 'D';
        $keterangan = "Kurang";
    } else {
        $grade = 'E';
        $keterangan = "Gagal";
    }


    // Query Insert Data ke DB
    $query = "INSERT INTO mahasiswa VALUES ('','$npm','$nama','$nama_mk', '$nKehadiran', '$nTugas','$nQuiz','$nUts','$nUas','$total', '$ip','$grade', '$keterangan','$dosen_id')";

    // Eksekusi Query
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    // Cek Berhasil atau Tidak
    return mysqli_affected_rows($conn);
}


// Fungsi Hapus Data
function deleteData($id)
{
    global $conn;

    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id") or die(mysqli_error($conn));

    return mysqli_affected_rows($conn);
}

// Fungsi Update Data
function updateData($data)
{
    global $conn;

    // Pass Data ke Var untuk Diproses 
    $id = (int) htmlspecialchars($data["id"]);
    $npm = htmlspecialchars($data["npm"]);
    $nama = htmlspecialchars($data["nama"]);
    $nama_mk = htmlspecialchars($data["nama_mk"]);
    $nKehadiran = (float) htmlspecialchars($data["nKehadiran"]);
    $nTugas = (float) htmlspecialchars($data["nTugas"]);
    $nQuiz = (float) htmlspecialchars($data["nQuiz"]);
    $nUts = (float) htmlspecialchars($data["nUts"]);
    $nUas = (float) htmlspecialchars($data["nUas"]);

    // Perhitungan untuk Total dan IP
    $total = round((0.1 * $nKehadiran) + (0.15 *  $nQuiz) + (0.10 * $nTugas) + (0.3 * $nUts) + (0.35 * $nUas), 2);
    $ip = round($total / 25, 2);

    // Pemberian Grade Berdasarkan Nilai
    if ($total >= 92) {
        $grade = 'A';
        $keterangan = "Istimewa";
    } else if ($total >= 86 && $total < 92) {
        $grade = 'A-';
        $keterangan = "Hampir Istimewa";
    } elseif ($total >= 81 && $total < 86) {
        $grade = 'B+';
        $keterangan = "Baik Sekali";
    } elseif ($total >= 76 && $total < 81) {
        $grade = 'B';
        $keterangan = "Baik";
    } elseif ($total >= 71 && $total < 76) {
        $grade = 'B-';
        $keterangan = "Cukup Baik";
    } elseif ($total >= 66 && $total < 71) {
        $grade = 'C+';
        $keterangan = "Lebih Dari Cukup";
    } elseif ($total >= 60 && $total < 66) {
        $grade = 'C';
        $keterangan = "Cukup";
    } elseif ($total >= 55 && $total < 60) {
        $grade = 'D';
        $keterangan = "Kurang";
    } else {
        $grade = 'E';
        $keterangan = "Gagal";
    }



    // Query Update
    $query = "UPDATE mahasiswa SET npm = '$npm', nama = '$nama', nama_mk = '$nama_mk', nKehadiran='$nKehadiran' ,nTugas = '$nTugas', nQuiz = '$nQuiz', nUts = '$nUts', nUas = '$nUas', total = '$total', ip='$ip', grade = '$grade', keterangan = '$keterangan' WHERE id = $id";

    // Eksekusi Query
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    // Cek Berhasil atau Tidak
    return mysqli_affected_rows($conn);
}

// Fungsi Cari Data berdasarkan Keyword
function searchData($keyword, $dosen_id)
{
    // Query Cari Data

    $query = "SELECT mahasiswa.id, mahasiswa.npm, mahasiswa.nama, mahasiswa.nama_mk, mahasiswa.total, mahasiswa.ip, mahasiswa.grade, mahasiswa.keterangan FROM mahasiswa INNER JOIN dosen ON mahasiswa.dosen_id = dosen.id WHERE mahasiswa.dosen_id = $dosen_id AND (mahasiswa.npm LIKE '%$keyword%' OR mahasiswa.nama LIKE '%$keyword%' OR mahasiswa.nama_mk LIKE '%$keyword%' OR mahasiswa.total LIKE '%$keyword%' OR mahasiswa.grade LIKE '%$keyword%' OR mahasiswa.ip LIKE '%$keyword%' OR mahasiswa.keterangan LIKE '%$keyword%')";

    // Eksekusi Query untuk Menggunakan Fungsi Get Data
    return queryGetData($query);
}

// Fungsi Registrasi
function register($data)
{
    global $conn;

    $username = htmlspecialchars(strtolower(stripslashes($data["username"])));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $data["password"]));
    $password2 = htmlspecialchars(mysqli_real_escape_string($conn, $data["passwordCon"]));

    // Cek Username dan Password Diisi atau Tidak
    if (empty($username) || empty($password) || empty($password2)) {
        echo
        "
        <script>
        alert('Username atau password tidak boleh kosong!');
        </script>
        ";
        return false;
    }

    // Cek Username Digunakan atau Belum
    if (queryGetData("SELECT * FROM dosen WHERE username = '$username'")) {
        echo "<script>
        alert('Username sudah digunakan');
        </script>
        ";
        return false;
    }

    // Cek Apakah Konfirmasi Password Benar
    if ($password !== $password2) {
        echo "<script>
        alert('Konfirmasi password salah');
        </script>
        ";
        return false;
    }

    // Cek Apakah Password Kurang dari 5 Karakter atau Tidak
    if (strlen($password) < 5) {
        echo "<script>
        alert('Password tidak boleh kurang dari 5 karakter');
        </script>
        ";
        return false;
    }

    // Enkripsi Password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Query Insert Data
    $query = "INSERT INTO dosen VALUES('','$username','$password') ";

    // Eksekusi Query
    mysqli_query($conn, $query) or die(mysqli_error($conn));

    // Cek Berhasil atau Tidak
    return mysqli_affected_rows($conn);
}

// Fungsi Login
function login($data)
{
    $username = $data["username"];
    $password = $data["password"];

    // Cek Username
    if ($cek = queryGetData("SELECT * FROM dosen WHERE username = '$username'")) {

        // Cek Password
        if (password_verify($password, $cek[0]["password"])) {

            // Set Session
            $_SESSION["login"] = true;
            $_SESSION["id"] = $cek[0]["id"];

            header("Location: index.php");
            exit();
        }
    }
}
