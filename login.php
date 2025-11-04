<?php
// File: login.php (FINAL DENGAN IKON MATA)
session_start();

// Detail Login Statik (Hardcoded)
$username_valid = "user"; 
$password_valid = "user123"; 
// ... (Logika POST dan Login tetap sama) ...
$login_error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username_input = $_POST['username'] ?? '';
    $password_input = $_POST['password'] ?? '';

    if ($username_input === $username_valid && $password_input === $password_valid) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username_input;
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Username atau Password salah. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tablo Dashboard</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> 
    
    <style>
        /* CSS Khusus Login */
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-container {
            width: 100%; max-width: 400px; padding: 30px; margin: 20px;
            background-color: #2c3e50; border-radius: 10px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        .login-container h2 { color: #007bff; margin-bottom: 25px; text-align: center; }
        .clue { font-size: 0.8rem; color: #ffc107; margin-top: 5px; }

        /* --- STYLE IKON MATA --- */
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #ced4da; /* Warna ikon agar terlihat */
            z-index: 10;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Tablo Dashboard Login</h2>
        
        <?php if ($login_error): ?>
            <div class="alert alert-danger" role="alert"><?php echo $login_error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-container">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
                <div class="clue">
                    Gunakan: **Username** `user` dan **Password** `user123`
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
        <footer class="app-footer text-center">
        <div class="footer-content">
            Created By. Mikhael Putra Wijaya | 23.01.53.0001
        </div>
    </footer>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Toggle tipe input antara 'password' dan 'text'
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Ganti ikon mata
            this.classList.toggle('fa-eye-slash');
            this.classList.toggle('fa-eye');
        });
    </script>
</body>
</html>