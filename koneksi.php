<?php
// File: koneksi.php
$host = "localhost";
$user = "root";     // Sesuaikan user Anda
$pass = "";         // Sesuaikan password database Anda
$dbname = "crud_db"; // Ganti dengan nama database Anda

$koneksi = new mysqli($host, $user, $pass, $dbname);

if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8");
?>