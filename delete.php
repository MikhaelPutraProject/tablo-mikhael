<?php
// File: delete.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 
include 'config.php'; 

$id = $_GET['id'] ?? null;
$tableName = $_GET['table'] ?? null; 

if ($id && $tableName) {
    if (!getTableFields($tableName)) { header("Location: data_tabel.php?table=produk&status=gagal_hapus_config_error"); exit(); }
    
    $sql = "DELETE FROM $tableName WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    
    if ($stmt === false) { die("Gagal menyiapkan query DELETE: " . $koneksi->error); }
    
    $stmt->bind_param("i", $id); 

    if ($stmt->execute()) {
        header("Location: data_tabel.php?table=$tableName&status=sukses_hapus");
        exit();
    } else {
        die("Gagal menghapus data dari tabel $tableName: " . $stmt->error);
    }
    $stmt->close();
} else {
    header("Location: data_tabel.php?table=produk&status=gagal_hapus_id_kosong");
    exit();
}
$koneksi->close();
?>