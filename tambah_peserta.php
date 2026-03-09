<?php

include "koneksi.php";

$nama=$_POST['nama'];
$kategori=$_POST['kategori'];

mysqli_query($conn,"INSERT INTO peserta (nama,kategori)
VALUES ('$nama','$kategori')");

echo "ok";