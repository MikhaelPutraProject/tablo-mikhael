<?php
// File: index.php (FINAL - Dashboard Home dengan Info Boxes)
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

/* ===============================
   Ambil statistik (COUNT)
   php-crud-api menyediakan "results"
================================ */
function getTotal($table)
{
    $res = apiGet("/records/$table?page=1,1");
    return $res['results'] ?? 0;
}

/* ===============================
   Statistik Dashboard
================================ */
$stats = [
    'Produk'    => getTotal('produk'),
    'Pelanggan' => getTotal('pelanggan'),
    'Pesanan'   => getTotal('pesanan'),
    'Pegawai'   => getTotal('pegawai'),
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablo Dashboard - Home</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <style>
        .digital-clock {
            font-size: 5rem;
            font-weight: 700;
            letter-spacing: 5px;
            text-align: center;
            color: #17a2b8;
            padding: 30px 0 50px 0;
            margin-top: 50px;
        }
        .welcome-text { font-size: 1.5rem; color: #ced4da; text-align: center; margin-bottom: 50px; }
        .app-tagline { color: #798aa6; font-size: 1rem; text-align: center; margin-top: -10px; margin-bottom: 30px; }

        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            margin-bottom: 1rem;
            position: relative;
            display: block;
            padding: 10px;
        }
        .small-box > .inner { padding: 10px; color: #fff; }
        .small-box h3 { font-size: 2.2rem; font-weight: 700; margin: 0 0 10px 0; }
        .small-box p { font-size: 1rem; margin: 0; }
        .small-box-footer {
            background-color: rgba(0,0,0,.1);
            color: #fff;
            display: block;
            padding: 3px 0;
            text-align: center;
            text-decoration: none;
        }
        .small-box-footer:hover { background-color: rgba(0,0,0,.2); }

        .bg-info { background-color: #17a2b8 !important; }
        .bg-success { background-color: #28a745 !important; }
        .bg-warning { background-color: #ffc107 !important; }
        .bg-danger { background-color: #dc3545 !important; }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include 'menu.php'; ?>
    <div class="content flex-grow-1 p-4" style="margin-left: 250px;">
        <div class="container mt-4">

            <div class="welcome-text">Selamat Datang di</div>
            <h1 class="text-center" style="color: #007bff; font-size: 3rem;">Tablo Dashboard</h1>
            <div class="app-tagline">Aplikasi Manajemen Data, yang Flexible, Efisien, Dan Cepat</div>

            <div id="digitalClock" class="digital-clock">00:00:00</div>

            <hr class="text-white-50">
            <h2 class="text-center text-info mb-4">Statistik Inti Aplikasi</h2>

            <div class="row mb-5">

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo number_format($stats['Produk']); ?></h3>
                            <p>Jenis Produk</p>
                        </div>
                        <a href="data_tabel.php?table=produk" class="small-box-footer">
                            Kelola Produk
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo number_format($stats['Pelanggan']); ?></h3>
                            <p>Pelanggan Terdaftar</p>
                        </div>
                        <a href="data_tabel.php?table=pelanggan" class="small-box-footer">
                            Kelola Pelanggan
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo number_format($stats['Pesanan']); ?></h3>
                            <p>Total Pesanan</p>
                        </div>
                        <a href="data_tabel.php?table=pesanan" class="small-box-footer">
                            Kelola Pesanan
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo number_format($stats['Pegawai']); ?></h3>
                            <p>Pegawai Aktif</p>
                        </div>
                        <a href="data_tabel.php?table=pegawai" class="small-box-footer">
                            Kelola Pegawai
                        </a>
                    </div>
                </div>

            </div>

            <div class="welcome-text mt-5">
                Tablo Dashboard By. Mikhael Putra Wijaya | 23.01.53.0001
            </div>

        </div>
    </div>
</div>

<footer class="app-footer text-center">
    <div class="footer-content">
        Created By. Mikhael Putra Wijaya | 23.01.53.0001
    </div>
</footer>

<script>
function updateClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('digitalClock').textContent = `${h}:${m}:${s}`;
}
setInterval(updateClock, 1000);
updateClock();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
