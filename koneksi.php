<?php
$host = "localhost";     // atau IP database server
$user = "root";          // username MySQL
$password = "";          // password MySQL
$database = "DPU";       // nama database

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>