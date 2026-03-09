<?php

include "koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM peserta WHERE id='$id'");

echo "ok";

?>