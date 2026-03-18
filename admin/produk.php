<?php
session_start();
include '../config/db.php';

// Proteksi Admin (Opsional, sesuaikan dengan sistem loginmu)
// if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

// Logika Hapus Produk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $ambil = mysqli_query($conn, "SELECT image FROM products WHERE id='$id'");
    $data = mysqli_fetch_assoc($ambil);
    $foto = $data['image'];

    if (file_exists("../assets/img/" . $foto)) {
        unlink("../assets/img/" . $foto);
    }

    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");
    header("Location: produk.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk | AICO ADMIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #2563eb; --sidebar-bg: #1e293b; --text-light: #f8fafc; }
        body { font-family: 'Inter', sans-serif; margin: 0; display: flex; background: #f1f5f9; min-height: 100vh; }
        
        /* Sidebar Styles */
        .sidebar { width: 260px; background: var(--sidebar-bg); color: white; padding: 30px 20px; position: fixed; height: 100%; }
        .sidebar h2 { color: #38bdf8; font-size: 22px; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px; }
        .sidebar-menu { list-style: none; padding: 0; }
        .sidebar-menu li { margin-bottom: 15px; }
        .sidebar-menu a { color: #cbd5e1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; transition: 0.3s; font-weight: 500; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #334155; color: white; }
        .sidebar-menu a.logout { margin-top: 50px; color: #f87171; }

        /* Main Content Styles */
        .main-content { flex: 1; margin-left: 260px; padding: 40px; }
        .header-content { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-content h1 { font-size: 28px; color: #0f172a; margin: 0; }
        
        .btn-tambah { background: var(--primary); color: white; padding: 12px 20px; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; }
        
        .card-table { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: #64748b; border-bottom: 2px solid #f1f5f9; font-size: 14px; }
        td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 15px; vertical-align: middle; }
        
        .img-prod { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; }
        .action-links a { text-decoration: none; font-weight: 600; font-size: 14px; margin-right: 10px; }
        .btn-edit { color: #2563eb; }
        .btn-hapus { color: #ef4444; }

        @media (max-width: 768px) {
            .sidebar { width: 70px; padding: 20px 10px; }
            .sidebar h2, .sidebar-menu span { display: none; }
            .main-content { margin-left: 70px; }
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AICO ADMIN</h2>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php">📊 <span>Dashboard</span></a></li>
            <li><a href="produk.php" class="active">📦 <span>Kelola Produk</span></a></li>
            <li><a href="../index.php">🌐 <span>Lihat Toko</span></a></li>
            <li><a href="logout.php" class="logout">🚪 <span>Keluar</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header-content">
            <h1>Kelola Produk</h1>
            <a href="tambah_produk.php" class="btn-tambah">+ Produk Baru</a>
        </div>

        <div class="card-table">
            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
                    while($p = mysqli_fetch_assoc($query)):
                    ?>
                    <tr>
                        <td>
                            <img src="../assets/img/<?php echo $p['image']; ?>" class="img-prod" onerror="this.src='https://via.placeholder.com/50'">
                        </td>
                        <td style="font-weight: 600;"><?php echo $p['name']; ?></td>
                        <td>Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></td>
                        <td class="action-links">
                            <a href="edit_produk.php?id=<?php echo $p['id']; ?>" class="btn-edit">Edit</a>
                            <a href="produk.php?hapus=<?php echo $p['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>