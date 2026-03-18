<?php
session_start();
// Proteksi: Kalau belum login, tendang balik ke login.php
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
include '../config/db.php';

// Ambil jumlah produk buat laporan singkat
$query_produk = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$data_produk = mysqli_fetch_assoc($query_produk);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | AICO STORE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #2563eb; --sidebar-bg: #1e293b; --bg: #f1f5f9; }
        body { font-family: 'Inter', sans-serif; margin: 0; display: flex; background: var(--bg); min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: var(--sidebar-bg); color: white; padding: 30px 20px; position: fixed; height: 100%; }
        .sidebar h2 { color: #38bdf8; font-size: 22px; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-menu { list-style: none; padding: 0; }
        .sidebar-menu li { margin-bottom: 15px; }
        .sidebar-menu a { color: #cbd5e1; text-decoration: none; display: flex; align-items: center; padding: 12px 15px; border-radius: 8px; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #334155; color: white; }
        
        /* Main Content */
        .main-content { flex: 1; margin-left: 260px; padding: 40px; }
        .welcome-box { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        .welcome-box h1 { margin: 0; color: #1e293b; font-size: 24px; }
        .welcome-box p { color: #64748b; margin-top: 5px; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .stat-card { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 6px solid var(--primary); }
        .stat-card h3 { margin: 0; color: #64748b; font-size: 14px; text-transform: uppercase; }
        .stat-card .number { font-size: 32px; font-weight: 800; color: #1e293b; margin-top: 10px; display: block; }
        
        .btn-action { margin-top: 15px; display: inline-block; color: var(--primary); text-decoration: none; font-weight: 600; font-size: 14px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AICO ADMIN</h2>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
            <li><a href="produk.php">📦 Kelola Produk</a></li>
            <li><a href="../index.php">🌐 Lihat Toko</a></li>
            <li><a href="../logout.php" style="color: #f87171; margin-top: 40px;">🚪 Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="welcome-box">
            <h1>Halo, Admin <?php echo $_SESSION['admin']; ?>! 👋</h1>
            <p>Selamat datang kembali. Hari ini mau update stok stiker apa?</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Stripping Ready</h3>
                <span class="number"><?php echo $data_produk['total']; ?> Produk</span>
                <a href="produk.php" class="btn-action">Lihat Semua &rarr;</a>
            </div>
            
            <div class="stat-card" style="border-left-color: #22c55e;">
                <h3>Status Server</h3>
                <span class="number" style="color: #22c55e;">Online</span>
                <p style="font-size: 12px; color: #64748b; margin: 5px 0 0 0;">Database Terkoneksi</p>
            </div>
        </div>
    </div>

</body>
</html>