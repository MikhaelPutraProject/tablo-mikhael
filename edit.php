<?php
// File: edit.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'config.php';

/* ===============================
   Helper API
================================ */
function apiGet($endpoint)
{
    $url = "http://localhost/tablo/api.php" . $endpoint;
    $res = @file_get_contents($url);
    if ($res === false) return null;
    return json_decode($res, true);
}

function apiPut($endpoint, $data)
{
    $url = "http://localhost/tablo/api.php" . $endpoint;

    $context = stream_context_create([
        "http" => [
            "method"  => "PUT",
            "header"  => "Content-Type: application/json\r\n",
            "content" => json_encode($data)
        ]
    ]);

    return @file_get_contents($url, false, $context);
}

/* ===============================
   Parameter
================================ */
$id        = $_GET['id'] ?? null;
$tableName = $_GET['table'] ?? null;

$status_message = '';
$isError = false;

/* ===============================
   Validasi
================================ */
if (!$id || !$tableName) {
    die("ID dan tabel harus disertakan.");
}

$fields = getTableFields($tableName);
if (!$fields) {
    die("Error: Tabel '$tableName' tidak terdaftar.");
}

/* ===============================
   AMBIL DATA (READ)
================================ */
$data = apiGet("/records/$tableName/$id");

if (!$data || !isset($data['id'])) {
    die("Data ID $id di tabel $tableName tidak ditemukan.");
}

/* ===============================
   UPDATE (PUT)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $payload = [];

    foreach ($fields as $field) {
        $payload[$field] = $_POST[$field] ?? null;
    }

    $result = apiPut("/records/$tableName/$id", $payload);

    if ($result === false) {
        $status_message = "Gagal memperbarui data.";
        $isError = true;
    } else {
        header("Location: data_tabel.php?table=$tableName&status=sukses_update");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data <?php echo ucwords(str_replace('_', ' ', $tableName)); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="d-flex">
    <?php include 'menu.php'; ?>
    <div class="content flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container mt-4">

            <h1 class="mb-4">
                Edit Data <?php echo ucwords(str_replace('_', ' ', $tableName)); ?>
                (ID: <?php echo $id; ?>)
            </h1>

            <?php if ($status_message): ?>
                <div class="alert alert-<?php echo $isError ? 'danger' : 'info'; ?>">
                    <?php echo $status_message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="edit.php?table=<?php echo $tableName; ?>&id=<?php echo $id; ?>">
                <?php
                foreach ($fields as $field):

                    $label = ucwords(str_replace('_', ' ', $field));
                    $input_type = 'text';
                    $step = 'any';

                    if (preg_match('/tgl|tanggal|masuk/i', $field)) {
                        $input_type = 'date';
                    } elseif (preg_match('/update/i', $field)) {
                        $input_type = 'datetime-local';
                    } elseif (preg_match('/email/i', $field)) {
                        $input_type = 'email';
                    } elseif (preg_match('/harga|total/i', $field)) {
                        $input_type = 'number';
                        $step = '0.01';
                    } elseif (preg_match('/stok|jumlah|kapasitas|id_/i', $field)) {
                        $input_type = 'number';
                        $step = '1';
                    }

                    $value = htmlspecialchars($data[$field] ?? '');

                    if ($input_type === 'date' && $value) {
                        $value = date('Y-m-d', strtotime($value));
                    } elseif ($input_type === 'datetime-local' && $value) {
                        $value = date('Y-m-d\TH:i', strtotime($value));
                    }
                ?>
                    <div class="mb-3">
                        <label class="form-label"><?php echo $label; ?></label>
                        <input
                            type="<?php echo $input_type; ?>"
                            name="<?php echo $field; ?>"
                            class="form-control"
                            value="<?php echo $value; ?>"
                            <?php if ($input_type === 'number') echo 'step="'.$step.'"'; ?>
                            required
                        >
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="data_tabel.php?table=<?php echo $tableName; ?>" class="btn btn-secondary">Batal</a>
            </form>

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
