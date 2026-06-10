<?php 
$servername = 'localhost';
$user = 'root';
$pass = '';
$database = 'fasilitas_kampus';

$conn = mysqli_connect($servername,$user,$pass,$database);

if (!$conn) { 
    die ("koneksi gagal!". mysqli_connect_error());
} else {
    echo "";
}

?>