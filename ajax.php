<?php
// Import Function
require 'function.php';

// Get Data Mahasiswa untuk Diedit Lalu Kirim dalam Bentuk JSON untuk Ditampilkan
$id = $_POST['id'];

$mahasiswa =  queryGetData("SELECT * FROM mahasiswa WHERE id = $id");

echo json_encode($mahasiswa[0]);
