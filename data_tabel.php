<?php
// File: data_tabel.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php'; 
include 'config.php'; 

$tableName = $_GET['table'] ?? 'produk'; 
// ... (Logika query dan pencarian sama) ...
$fields = getTableFields($tableName); $keyword = $_GET['keyword'] ?? ''; 
$result = false; $status = ''; $isError = false;

if (!$fields) { $status = "Error: Tabel '$tableName' tidak terdaftar."; $fields = ['Error']; $isError = true; } 
else {
    $field_list = implode(', ', $fields); $where_clause = ''; $bind_types = ''; $bind_values = [];
    if (!empty($keyword)) {
        $where_conditions = []; $search_term = "%{$keyword}%"; 
        foreach ($fields as $field) { $where_conditions[] = "$field LIKE ?"; $bind_types .= 's'; $bind_values[] = $search_term; }
        $where_clause = " WHERE " . implode(' OR ', $where_conditions);
    }
    $sql = "SELECT id, $field_list FROM $tableName" . $where_clause;
    
    if (!empty($keyword)) {
        $stmt = $koneksi->prepare($sql);
        if ($stmt === false) { $status = "Error prepared statement: " . $koneksi->error; $isError = true; } 
        else {
            $bind_params = array_merge([$bind_types], $bind_values);
            call_user_func_array([$stmt, 'bind_param'], array_by_ref($bind_params));
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        }
    } else {
        $result = $koneksi->query($sql);
        if (!$result) { $status = "Error saat mengambil data: " . $koneksi->error; $isError = true; }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablo Dashboard - Data <?php echo ucwords(str_replace('_', ' ', $tableName)); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="d-flex">
        <?php include 'menu.php'; ?> 
        <div class="content flex-grow-1 p-4" style="margin-left: 250px;">
            <div class="container mt-4">
                <h1 class="mb-4">Data <?php echo ucwords(str_replace('_', ' ', $tableName)); ?></h1>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <?php if (!$isError): ?>
                        <a href="tambah.php?table=<?php echo $tableName; ?>" class="btn btn-primary me-3">➕ Tambah Data Baru</a>
                    <?php endif; ?>
                    
                    <form method="GET" action="data_tabel.php" class="d-flex search-bar-highlight p-2 rounded w-50">
                        <input type="hidden" name="table" value="<?php echo $tableName; ?>">
                        <input type="text" name="keyword" class="form-control me-2" placeholder="Cari data spesifik di sini..." value="<?php echo htmlspecialchars($keyword); ?>">
                        <button class="btn btn-info" type="submit">Cari</button>
                    </form>
                </div>
                
                <?php if ($status): ?><div class="alert alert-danger" role="alert"><?php echo $status; ?></div>
                <?php elseif (isset($_GET['status'])): ?><div class="alert alert-success" role="alert">Operasi berhasil!</div><?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark"><tr><th>ID</th>
                                <?php foreach ($fields as $field): ?><th><?php echo ucwords(str_replace('_', ' ', $field)); ?></th><?php endforeach; ?>
                                <th>Aksi</th></tr></thead>
                        <tbody>
                            <?php
                            $colspan_count = count($fields) + 2;
                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td>";
                                    foreach ($fields as $field) { echo "<td>" . htmlspecialchars($row[$field] ?? '-') . "</td>"; }
                                    echo "<td>
                                        <a href='edit.php?table=$tableName&id=" . htmlspecialchars($row["id"]) . "' class='btn btn-sm btn-warning me-1'>Edit</a>
                                        <a href='delete.php?table=$tableName&id=" . htmlspecialchars($row["id"]) . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin menghapus data ID " . htmlspecialchars($row["id"]) . "?');\">Hapus</a>
                                    </td></tr>";
                                }
                                if (!empty($keyword)) { echo "<tr><td colspan='$colspan_count' class='text-center text-info'>Ditemukan **" . $result->num_rows . "** hasil pencarian untuk: **" . htmlspecialchars($keyword) . "**</td></tr>"; }
                            } else {
                                echo "<tr><td colspan='$colspan_count' class='text-center'>Tidak ditemukan data di tabel " . ucwords(str_replace('_', ' ', $tableName)) . ".</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
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