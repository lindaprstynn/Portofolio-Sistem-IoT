<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "sistem_iot";

$conn = mysqli_connect($servername, $username, $password, $database);

// Periksa Koneksi
if(!$conn){
    die("Koneksi gagal: " . mysqli_connect_error());
}
// echo "Koneksi berhasil";

?>