<?php
// File: delete.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'config.php';

/* ===============================
   Helper DELETE via api.php
================================ */
function apiDelete($endpoint)
{
    $url = "http://localhost/tablo/api.php" . $endpoint;

    $context = stream_context_create([
        "http" => [
            "method" => "DELETE"
        ]
    ]);

    return @file_get_contents($url, false, $context);
}

/* ===============================
   Ambil parameter
================================ */
$id        = $_GET['id'] ?? null;
$tableName = $_GET['table'] ?? null;

/* ===============================
   Validasi
================================ */
if (!$id || !$tableName) {
    header("Location: data_tabel.php?table=produk&status=gagal_hapus_id_kosong");
    exit;
}

if (!getTableFields($tableName)) {
    header("Location: data_tabel.php?table=produk&status=gagal_hapus_config_error");
    exit;
}

/* ===============================
   DELETE ke API
================================ */
$result = apiDelete("/records/$tableName/$id");

if ($result === false) {
    header("Location: data_tabel.php?table=$tableName&status=gagal_hapus");
    exit;
}

/* ===============================
   Sukses
================================ */
header("Location: data_tabel.php?table=$tableName&status=sukses_hapus");
exit;
