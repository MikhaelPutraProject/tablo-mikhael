<?php
// File: tambah.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 
include 'config.php'; 

$tableName = $_GET['table'] ?? 'produk'; 
// ... (Logika CRUD Tetap Sama, pastikan REDIRECT ke data_tabel.php) ...
$fields = getTableFields($tableName); 
$status_message = ''; $isError = false;

if (!$fields) { $status_message = "Error: Tabel '$tableName' tidak terdaftar."; $isError = true; }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$isError) {
    $column_list = implode(', ', $fields);
    $placeholder_list = rtrim(str_repeat('?, ', count($fields)), ', '); 
    $types = ''; $param_values = [];
    
    foreach ($fields as $field) {
        $param_values[] = $_POST[$field] ?? ''; 
        if (in_array($field, ['harga', 'total_harga'])) { $types .= 'd'; } 
        elseif (in_array($field, ['stok', 'jumlah', 'kapasitas', 'id_pelanggan', 'id_pesanan'])) { $types .= 'i'; } 
        else { $types .= 's'; }
    }
    
    $sql = "INSERT INTO $tableName ($column_list) VALUES ($placeholder_list)";
    $stmt = $koneksi->prepare($sql);
    
    if ($stmt === false) { $status_message = "Error saat menyiapkan query: " . $koneksi->error; $isError = true; } 
    else {
        $bind_params = array_merge([$types], $param_values);
        call_user_func_array([$stmt, 'bind_param'], array_by_ref($bind_params));
        
        if ($stmt->execute()) { 
            header("Location: data_tabel.php?table=$tableName&status=sukses_tambah"); 
            exit(); 
        } 
        else { $status_message = "Gagal menyimpan data. Error: " . $stmt->error; $isError = true; }
        $stmt->close();
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
                <?php if ($status_message): ?><div class="alert alert-<?php echo $isError ? 'danger' : 'success'; ?>" role="alert"><?php echo $status_message; ?></div><?php endif; ?>
                <?php if (!$isError): ?>
                <form method="POST" action="tambah.php?table=<?php echo $tableName; ?>">
                    <?php 
                    foreach ($fields as $field): 
                        $label = ucwords(str_replace('_', ' ', $field)); $input_type = 'text'; $step = 'any';
                        if (strpos($field, 'tgl') !== false || strpos($field, 'tanggal') !== false || strpos($field, 'masuk') !== false) { $input_type = 'date'; } 
                        elseif (strpos($field, 'update') !== false) { $input_type = 'datetime-local'; } 
                        elseif (strpos($field, 'email') !== false) { $input_type = 'email'; } 
                        elseif (strpos($field, 'harga') !== false || strpos($field, 'total') !== false) { $input_type = 'number'; $step = '0.01'; } 
                        elseif (strpos($field, 'stok') !== false || strpos($field, 'jumlah') !== false || strpos($field, 'kapasitas') !== false || strpos($field, 'id_') !== false) { $input_type = 'number'; $step = '1'; } 
                    ?>
                    <div class="mb-3">
                        <label for="<?php echo $field; ?>" class="form-label"><?php echo $label; ?>:</label>
                        <input type="<?php echo $input_type; ?>" class="form-control" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="" <?php if ($input_type == 'number') echo 'step="' . $step . '"'; ?> required>
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
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
<?php $koneksi->close(); ?>