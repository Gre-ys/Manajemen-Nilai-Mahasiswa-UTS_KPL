<?php
session_start();

if (isset($_SESSION["login"])) {
    header("Location: index.php");
    exit();
}

require "function.php";

if (isset($_POST["login"])) {
    $login = login($_POST);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Laman Login</title>
</head>

<body>
    <div class="container shadow p-3 mb-5 bg-body rounded m-auto mt-5">
        <form action="" method="POST">
            <h3 class="mb-2 d-flex justify-content-center">Login</h3>
            <div class=" form-floating">
                <input type="text" class="form-control form-control-sm" id="username" name="username" placeholder="username" required>
                <label for="username">Username</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="password" required>
                <label for="password">Password</label>
            </div>
            <button type="submit" name="login" class="btn btn-primary d-flex justify-content-center mt-2 m-auto">Login</button>
        </form>
        <hr>
        <div class="row d-flex justify-content-center ">
            <p class="mt-4 col-2">Belum Punya Akun?</p>
            <div class="col-1 align-self-center">
                <button class="btn btn-outline-primary btn-sm mt-2"><a href="register.php" class="text-reset text-decoration-none">Register</a></button>
            </div>
        </div>
    </div>
</body>

</html>