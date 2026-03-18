<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
include '../config/db.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
$p = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $category = mysqli_real_escape_string($conn, $_POST['category']); // Menangkap kategori baru
    
    $foto_nama = $_FILES['image']['name'];
    $foto_temp = $_FILES['image']['tmp_name'];

    if (!empty($foto_nama)) {
        $new_filename = time() . "_" . $foto_nama;
        if (file_exists("../assets/img/" . $p['image'])) { unlink("../assets/img/" . $p['image']); }
        move_uploaded_file($foto_temp, "../assets/img/" . $new_filename);
        
        // Update termasuk kolom category dan image baru
        $update = mysqli_query($conn, "UPDATE products SET name='$name', price='$price', category='$category', image='$new_filename' WHERE id='$id'");
    } else {
        // Update termasuk kolom category tanpa mengganti image
        $update = mysqli_query($conn, "UPDATE products SET name='$name', price='$price', category='$category' WHERE id='$id'");
    }

    if ($update) { 
        echo "<script>alert('Update Berhasil!'); window.location='produk.php';</script>"; 
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk | AICO ADMIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #2563eb; --sidebar-bg: #1e293b; --bg: #f1f5f9; }
        body { font-family: 'Inter', sans-serif; margin: 0; display: flex; background: var(--bg); min-height: 100vh; }
        
        .sidebar { width: 260px; background: var(--sidebar-bg); color: white; padding: 30px 20px; position: fixed; height: 100%; box-sizing: border-box; }
        .sidebar h2 { color: #38bdf8; font-size: 22px; margin-bottom: 40px; text-transform: uppercase; letter-spacing: 1px; text-align: center; }
        .sidebar-menu a { color: #cbd5e1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #334155; color: white; }

        .main-content { flex: 1; margin-left: 260px; padding: 40px; box-sizing: border-box; }
        .header-box h1 { margin: 0 0 30px 0; font-size: 28px; color: #1e293b; }

        .card-form { background: white; padding: 35px; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); max-width: 600px; border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #475569; }
        .form-group input, .form-group select { width: 100%; padding: 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; box-sizing: border-box; font-family: 'Inter'; font-size: 14px; }
        
        .img-old { width: 120px; height: 120px; object-fit: cover; border-radius: 10px; margin: 10px 0; border: 3px solid #f1f5f9; }
        .btn-save { background: var(--primary); color: white; border: none; padding: 14px 30px; border-radius: 10px; cursor: pointer; font-weight: 700; }
        .btn-back { color: #64748b; text-decoration: none; font-size: 14px; margin-left: 15px; }
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
            <h1>Edit Produk</h1>
        </div>

        <div class="card-form">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="name" value="<?php echo $p['name']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Kategori Produk</label>
                    <select name="category" required>
                        <option value="Motor" <?php echo ($p['category'] == 'Motor') ? 'selected' : ''; ?>>Motor</option>
                        <option value="Vape" <?php echo ($p['category'] == 'Vape') ? 'selected' : ''; ?>>Vape</option>
                        <option value="Custom" <?php echo ($p['category'] == 'Custom') ? 'selected' : ''; ?>>Custom</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" value="<?php echo $p['price']; ?>" required>
                </div>

                <div class="form-group">
                    <label>Foto Sekarang</label><br>
                    <img src="../assets/img/<?php echo $p['image']; ?>" class="img-old" onerror="this.src='https://via.placeholder.com/120'"><br>
                    <p style="font-size: 12px; color: #94a3b8; margin-bottom: 10px;">*Biarkan kosong jika tidak ingin mengganti foto</p>
                    <input type="file" name="image">
                </div>

                <div style="margin-top: 30px;">
                    <button type="submit" name="update" class="btn-save">UPDATE PRODUK</button>
                    <a href="produk.php" class="btn-back">Batal</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>