<?php
// Import Function
require "function.php";

// Mengajukan Registrasi
if (isset($_POST["registrasi"])) {

    // Cek Berhasil Registrasi atau Tidak
    if (register($_POST) > 0) {
        echo
        "
        <script>
        alert('Dosen Berhasil ditambahkan');
        </script>
        ";
    } else {
        echo
        "
        <script>
        alert('Dosen Gagal ditambahkan');
        </script>
        ";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Laman Registrasi</title>
</head>

<body>
    <div class="container shadow p-3 mb-5 bg-body rounded m-auto mt-5">
        <form action="" method="POST">
            <h3 class="mb-2 d-flex justify-content-center">Registrasi</h3>
            <div class=" form-floating">
                <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="password" required>
                <label for="password">Password</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control form-control-sm" id="passwordCon" name="passwordCon" placeholder="passwordCon" required>
                <label for="passwordCon">Konfirmasi Password</label>
            </div>
            <button type="submit" name="registrasi" class="btn btn-primary d-flex justify-content-center mt-2 m-auto">Registrasi</button>
        </form>
        <hr>
        <!-- Link ke Page login -->
        <div class="row d-flex justify-content-center ">
            <p class="mt-4 col-2">Sudah Punya Akun?</p>
            <div class="col-1 align-self-center">
                <button class="btn btn-outline-primary btn-sm mt-2"><a href="login.php" class="text-reset text-decoration-none">login</a></button>
            </div>
        </div>
    </div>
</body>

</html>