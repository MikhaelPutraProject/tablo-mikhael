<?php
// File: tambah.php (php-crud-api version)
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'config.php';

$tableName = $_GET['table'] ?? 'produk';
$fields = getTableFields($tableName);

$status_message = '';
$isError = false;

if (!$fields) {
    $status_message = "Error: Tabel '$tableName' tidak terdaftar.";
    $isError = true;
}

/* ===============================
   PROSES SIMPAN DATA (POST)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isError) {

    $payload = [];

    foreach ($fields as $field) {
        $payload[$field] = $_POST[$field] ?? null;
    }

    $apiUrl = "http://localhost/tablo/api.php/records/$tableName";

    $ch = curl_init($apiUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 201 || $httpCode === 200) {
        header("Location: data_tabel.php?table=$tableName&status=sukses_tambah");
        exit;
    } else {
        $status_message = "Gagal menyimpan data (API Error)";
        $isError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data <?php echo ucwords(str_replace('_', ' ', $tableName)); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="d-flex">
    <?php include 'menu.php'; ?>
    <div class="content flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container mt-4">
            <h1 class="mb-4">Tambah Data <?php echo ucwords(str_replace('_', ' ', $tableName)); ?></h1>

            <?php if ($status_message): ?>
                <div class="alert alert-<?php echo $isError ? 'danger' : 'success'; ?>">
                    <?php echo $status_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!$isError): ?>
            <form method="POST" action="tambah.php?table=<?php echo $tableName; ?>">
                <?php foreach ($fields as $field):
                    $label = ucwords(str_replace('_', ' ', $field));
                    $input_type = 'text';
                    $step = 'any';

                    if (strpos($field, 'tgl') !== false || strpos($field, 'tanggal') !== false) {
                        $input_type = 'date';
                    } elseif (strpos($field, 'update') !== false) {
                        $input_type = 'datetime-local';
                    } elseif (strpos($field, 'email') !== false) {
                        $input_type = 'email';
                    } elseif (strpos($field, 'harga') !== false || strpos($field, 'total') !== false) {
                        $input_type = 'number';
                        $step = '0.01';
                    } elseif (strpos($field, 'stok') !== false || strpos($field, 'jumlah') !== false || strpos($field, 'id_') !== false) {
                        $input_type = 'number';
                        $step = '1';
                    }
                ?>
                <div class="mb-3">
                    <label class="form-label"><?php echo $label; ?></label>
                    <input
                        type="<?php echo $input_type; ?>"
                        name="<?php echo $field; ?>"
                        class="form-control"
                        step="<?php echo $step; ?>"
                        required
                    >
                </div>
                <?php endforeach; ?>

                <button class="btn btn-primary">Simpan Data</button>
                <a href="data_tabel.php?table=<?php echo $tableName; ?>" class="btn btn-secondary">Batal</a>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<footer class="app-footer text-center">
    <div class="footer-content">
        Created By. Mikhael Putra Wijaya | 23.01.53.0001
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
