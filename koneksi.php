<?php

$host="103.196.154.16";
$user="doorprize";
$pass="doorprize123";
$db="doorprize";

$conn=mysqli_connect($host,$user,$pass,$db);

if(!$conn){
die("Koneksi gagal");
}