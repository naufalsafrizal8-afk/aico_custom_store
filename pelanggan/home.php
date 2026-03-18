<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'pelanggan') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>AICO CUSTOM STORE</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        nav { background: white; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .container { padding: 50px; text-align: center; }
        .welcome-card { background: white; padding: 40px; border-radius: 15px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <nav>
        <h2 style="margin:0; color:#2563eb;">AICO STORE</h2>
        <div>
            <span>Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
            <a href="../logout.php" style="margin-left:20px; color:red; text-decoration:none; font-weight:bold;">Keluar</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h1>Selamat Datang Pelanggan! 👋</h1>
            <p>Terima kasih telah login. Silakan jelajahi koleksi custom store kami.</p>
        </div>
    </div>
</body>
</html>