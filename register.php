<?php
include 'config/db.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Cek apakah password sama
    if ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak sesuai!";
    } else {
        // Cek apakah username sudah ada
        $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek_user) > 0) {
            $error = "Username sudah terdaftar!";
        } else {
            // Simpan ke database
            $insert = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
            if ($insert) {
                echo "<script>alert('Registrasi Berhasil! Silahkan Login.'); window.location='login.php';</script>";
            } else {
                $error = "Gagal mendaftar, coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Admin | AICO STORE</title>
    <style>
        body { font-family: 'Inter', sans-serif; background: #1e293b; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .register-box { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 350px; text-align: center; }
        h2 { color: #2563eb; margin-bottom: 25px; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .error { color: #ef4444; margin-bottom: 15px; font-size: 14px; }
        .login-link { display: block; margin-top: 15px; font-size: 13px; color: #64748b; text-decoration: none; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>DAFTAR ADMIN</h2>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username Baru" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Ulangi Password" required>
            <button type="submit" name="register">DAFTAR SEKARANG</button>
        </form>
        <a href="login.php" class="login-link">Sudah punya akun? Login di sini</a>
    </div>
</body>
</html>