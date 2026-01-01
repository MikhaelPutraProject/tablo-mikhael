<?php
// File: data_tabel.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

include 'config.php';

/* ===============================
   Helper ambil data dari api.php
================================ */
function apiGet($endpoint)
{
    $url = "http://localhost/tablo/api.php" . $endpoint;
    $response = @file_get_contents($url);
    if ($response === false) return null;
    return json_decode($response, true);
}

/* ===============================
   Parameter
================================ */
$tableName = $_GET['table'] ?? 'produk';
$keyword   = $_GET['keyword'] ?? '';
$fields    = getTableFields($tableName);

$isError = false;
$status  = '';
$records = [];

/* ===============================
   Validasi tabel
================================ */
if (!$fields) {
    $status  = "Error: Tabel '$tableName' tidak terdaftar.";
    $isError = true;
    $fields  = ['Error'];
} else {

    $endpoint = "/records/$tableName";

    // SEARCH (OR filter semua kolom)
    if (!empty($keyword)) {
        foreach ($fields as $i => $field) {
            $endpoint .= ($i === 0 ? '?' : '&')
                . "filter[]=$field,cs," . urlencode($keyword);
        }
    }

    $response = apiGet($endpoint);

    if (!$response || !isset($response['records'])) {
        $status  = "Gagal mengambil data dari API.";
        $isError = true;
    } else {
        $records = $response['records'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <a href="tambah.php?table=<?php echo $tableName; ?>" class="btn btn-primary me-3">
                        ➕ Tambah Data Baru
                    </a>
                <?php endif; ?>

                <form method="GET" action="data_tabel.php" class="d-flex p-2 rounded w-50">
                    <input type="hidden" name="table" value="<?php echo $tableName; ?>">
                    <input type="text" name="keyword" class="form-control me-2"
                           placeholder="Cari data..."
                           value="<?php echo htmlspecialchars($keyword); ?>">
                    <button class="btn btn-info">Cari</button>
                </form>
            </div>

            <?php if ($status): ?>
                <div class="alert alert-danger"><?php echo $status; ?></div>
            <?php elseif (isset($_GET['status'])): ?>
                <div class="alert alert-success">Operasi berhasil!</div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <?php foreach ($fields as $field): ?>
                            <th><?php echo ucwords(str_replace('_', ' ', $field)); ?></th>
                        <?php endforeach; ?>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $colspan = count($fields) + 2;

                    if (!empty($records)) {
                        foreach ($records as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";

                            foreach ($fields as $field) {
                                echo "<td>" . htmlspecialchars($row[$field] ?? '-') . "</td>";
                            }

                            echo "<td>
                                <a href='edit.php?table=$tableName&id={$row['id']}' class='btn btn-sm btn-warning me-1'>Edit</a>
                                <a href='delete.php?table=$tableName&id={$row['id']}'
                                   class='btn btn-sm btn-danger'
                                   onclick=\"return confirm('Yakin menghapus ID {$row['id']}?');\">Hapus</a>
                              </td>";
                            echo "</tr>";
                        }

                        if ($keyword) {
                            echo "<tr>
                                <td colspan='$colspan' class='text-center text-info'>
                                    Ditemukan <b>" . count($records) . "</b> hasil untuk
                                    <b>" . htmlspecialchars($keyword) . "</b>
                                </td>
                              </tr>";
                        }
                    } else {
                        echo "<tr>
                            <td colspan='$colspan' class='text-center'>
                                Tidak ada data.
                            </td>
                          </tr>";
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
