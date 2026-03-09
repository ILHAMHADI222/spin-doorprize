<?php

include "koneksi.php";

$data=mysqli_query($conn,"SELECT id,nama,kategori FROM peserta");

$list=[];

while($d=mysqli_fetch_assoc($data)){
$list[]=$d;
}

echo json_encode($list);