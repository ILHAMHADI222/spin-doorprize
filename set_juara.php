<?php

include "koneksi.php";

$id = $_POST['id'];
$juara = $_POST['juara'];

mysqli_query($conn,"UPDATE peserta SET juara='$juara' WHERE id=$id");

?>