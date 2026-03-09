<?php

include "koneksi.php";

$nama=$_POST['nama'];

mysqli_query($conn,"DELETE FROM peserta WHERE nama='$nama' LIMIT 1");

echo "ok";