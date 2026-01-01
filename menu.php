<?php
// File: menu.php (FINAL DAN DIPERBAIKI)

// CATATAN: session_start() HARUS sudah dipanggil di file yang memanggil menu.php
// (misalnya di index.php, data_tabel.php, dll., di baris paling atas).

if (!isset($tables)) {
    // Memuat config.php jika belum dimuat (walaupun seharusnya sudah dimuat oleh file utama)
    include 'config.php'; 
}

// Cek apakah user sudah login untuk menampilkan username
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

$is_home_page = (basename($_SERVER['PHP_SELF']) == 'index.php');
$current_data_file = basename($_SERVER['PHP_SELF']); 
$current_table = $_GET['table'] ?? ''; 
?>

<div class="sidebar bg-dark p-3 vh-100" style="width: 250px; position: fixed;">
    <h4 class="text-white mb-4">Tablo Dashboard</h4> 
    
    <ul class="nav flex-column">
        
        <li class="nav-item mb-2">
            <a class="nav-link text-white rounded <?php echo $is_home_page ? 'active bg-primary' : ''; ?>" 
               href="index.php">
                🏠 Home
            </a>
        </li>
        
        <?php foreach (array_keys($tables) as $tName): ?>
            <?php 
            $display_name = ucwords(str_replace('_', ' ', $tName));
            $is_active_table = ($current_data_file == 'data_tabel.php' && $current_table == $tName);
            $active_class = $is_active_table ? 'active bg-primary' : '';
            ?>
            <li class="nav-item mb-2">
                <a class="nav-link text-white rounded <?php echo $active_class; ?>" 
                   href="data_tabel.php?table=<?php echo $tName; ?>">
                    <?php echo $display_name; ?>
                </a>
            </li>
        <?php endforeach; ?>
        
        <hr class="text-white-50 mt-4 mb-2">
        <li class="nav-item">
            <?php $username_display = $is_logged_in ? ' (' . htmlspecialchars($_SESSION['username']) . ')' : ''; ?>
            <a class="nav-link text-danger rounded" href="logout.php">
                🚪 Logout <?php echo $username_display; ?>
            </a>
        </li>
    </ul>
</div>