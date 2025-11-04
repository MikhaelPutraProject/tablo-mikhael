<?php
// File: edit.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 
include 'config.php'; 

$id = $_GET['id'] ?? null;
// ... (Logika CRUD Tetap Sama, pastikan REDIRECT ke data_tabel.php) ...
$tableName = $_GET['table'] ?? null;
$fields = getTableFields($tableName); 
$data = false; $status_message = ''; $isError = false;

if (!$fields) { die("Error: Tabel '$tableName' tidak terdaftar."); }
// ... (Logika SELECT) ...
if ($id) {
    $field_list = implode(', ', $fields); $sql_select = "SELECT id, $field_list FROM $tableName WHERE id = ?";
    $stmt_select = $koneksi->prepare($sql_select); if ($stmt_select === false) { die("Error SELECT: " . $koneksi->error); }
    $stmt_select->bind_param("i", $id); $stmt_select->execute(); $result = $stmt_select->get_result();
    $data = $result->fetch_assoc(); $stmt_select->close();
    if (!$data) { die("Data ID $id di tabel $tableName tidak ditemukan."); }
} else { die("ID dan Tabel harus disertakan untuk melakukan edit."); }

// ... (Logika UPDATE) ...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $set_clauses = []; $param_values = []; $types = '';
    foreach ($fields as $field) {
        $set_clauses[] = "$field = ?"; $param_values[] = $_POST[$field] ?? '';
        if (in_array($field, ['harga', 'total_harga'])) { $types .= 'd'; } 
        elseif (in_array($field, ['stok', 'jumlah', 'kapasitas', 'id_pelanggan', 'id_pesanan'])) { $types .= 'i'; } 
        else { $types .= 's'; }
    }
    $set_string = implode(', ', $set_clauses); $sql_update = "UPDATE $tableName SET $set_string WHERE id = ?";
    $stmt_update = $koneksi->prepare($sql_update);
    if ($stmt_update === false) { $status_message = "Error UPDATE: " . $koneksi->error; $isError = true; } 
    else {
        $types .= 'i'; $param_values[] = $id; 
        $bind_params = array_merge([$types], $param_values);
        call_user_func_array([$stmt_update, 'bind_param'], array_by_ref($bind_params));
        if ($stmt_update->execute()) { 
            header("Location: data_tabel.php?table=$tableName&status=sukses_update"); 
            exit(); 
        } 
        else { $status_message = "Gagal memperbarui data. Error: " . $stmt_update->error; $isError = true; }
        $stmt_update->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <h1 class="mb-4">Edit Data <?php echo ucwords(str_replace('_', ' ', $tableName)); ?> (ID: <?php echo $id; ?>)</h1>
                <?php if ($status_message): ?><div class="alert alert-<?php echo $isError ? 'danger' : 'info'; ?>" role="alert"><?php echo $status_message; ?></div><?php endif; ?>
                <form method="POST" action="edit.php?table=<?php echo $tableName; ?>&id=<?php echo $id; ?>">
                    <?php 
                    foreach ($fields as $field): 
                        $label = ucwords(str_replace('_', ' ', $field)); $input_type = 'text'; $step = 'any';
                        if (strpos($field, 'tgl') !== false || strpos($field, 'tanggal') !== false || strpos($field, 'masuk') !== false) { $input_type = 'date'; } 
                        elseif (strpos($field, 'update') !== false) { $input_type = 'datetime-local'; } 
                        elseif (strpos($field, 'email') !== false) { $input_type = 'email'; } 
                        elseif (strpos($field, 'harga') !== false || strpos($field, 'total') !== false) { $input_type = 'number'; $step = '0.01'; } 
                        elseif (strpos($field, 'stok') !== false || strpos($field, 'jumlah') !== false || strpos($field, 'kapasitas') !== false || strpos($field, 'id_') !== false) { $input_type = 'number'; $step = '1'; } 
                        $value = htmlspecialchars($data[$field] ?? '');
                        if ($input_type == 'date' && $value) { $value = date('Y-m-d', strtotime($value)); } 
                        elseif ($input_type == 'datetime-local' && $value) { $value = date('Y-m-d\TH:i', strtotime($value)); }
                    ?>
                    <div class="mb-3">
                        <label for="<?php echo $field; ?>" class="form-label"><?php echo $label; ?>:</label>
                        <input type="<?php echo $input_type; ?>" class="form-control" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo $value; ?>" <?php if ($input_type == 'number') echo 'step="' . $step . '"'; ?> required>
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
<?php $koneksi->close(); ?>