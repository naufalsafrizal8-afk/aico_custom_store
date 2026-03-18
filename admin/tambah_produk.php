<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
include '../config/db.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Ambil data kategori
    
    // Logika Upload Gambar
    $filename = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    
    // Beri nama unik agar tidak bentrok
    $new_filename = time() . "_" . $filename;

    if (move_uploaded_file($tmp_name, "../assets/img/" . $new_filename)) {
        // Update query untuk memasukkan category
        $insert = mysqli_query($conn, "INSERT INTO products (name, price, image, category) VALUES ('$name', '$price', '$new_filename', '$category')");
        if ($insert) {
            echo "<script>alert('Produk Berhasil Ditambah!'); window.location='produk.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk | AICO ADMIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #2563eb; --sidebar-bg: #1e293b; --bg: #f1f5f9; }
        body { font-family: 'Inter', sans-serif; margin: 0; display: flex; background: var(--bg); min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 260px; background: var(--sidebar-bg); color: white; padding: 30px 20px; position: fixed; height: 100%; box-sizing: border-box; }
        .sidebar h2 { color: #38bdf8; font-size: 22px; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px; text-align: center; }
        .sidebar-menu { list-style: none; padding: 0; }
        .sidebar-menu a { color: #cbd5e1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #334155; color: white; }

        /* Main Content */
        .main-content { flex: 1; margin-left: 260px; padding: 40px; box-sizing: border-box; }
        .header-box { margin-bottom: 30px; }
        .header-box h1 { margin: 0; font-size: 28px; color: #1e293b; }

        /* Form Card */
        .card-form { background: white; padding: 35px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); max-width: 600px; border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #475569; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; box-sizing: border-box; font-family: 'Inter'; font-size: 15px; }
        .form-group input:focus, .form-group select:focus { border-color: var(--primary); outline: none; }

        .btn-container { display: flex; align-items: center; gap: 15px; margin-top: 30px; }
        .btn-save { background: var(--primary); color: white; border: none; padding: 14px 30px; border-radius: 10px; cursor: pointer; font-weight: 700; transition: 0.3s; }
        .btn-save:hover { opacity: 0.9; transform: translateY(-2px); }
        .btn-back { color: #64748b; text-decoration: none; font-size: 14px; font-weight: 500; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>AICO ADMIN</h2>
        <div class="sidebar-menu">
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="produk.php" class="active">📦 Kelola Produk</a>
            <a href="../index.php">🌐 Lihat Toko</a>
            <a href="../logout.php" style="color:#f87171; margin-top:30px;">🚪 Keluar</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-box">
            <h1>Tambah Stripping Baru</h1>
        </div>

        <div class="card-form">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Produk / Stiker</label>
                    <input type="text" name="name" placeholder="Contoh: Stripping Vario 150 Gen 2" required>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" placeholder="Contoh: 50000" required>
                </div>

                <div class="form-group">
                    <label>Pilih Kategori</label>
                    <select name="category" required>
                        <option value="Motor">Motor</option>
                        <option value="Vape">Vape</option>
                        <option value="Custom">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Upload Foto Stiker</label>
                    <input type="file" name="image" required>
                </div>

                <div class="btn-container">
                    <button type="submit" name="submit" class="btn-save">SIMPAN PRODUK</button>
                    <a href="produk.php" class="btn-back">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>