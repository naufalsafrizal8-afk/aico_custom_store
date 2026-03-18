<?php
session_start();
include 'config/db.php';

// Logika Filter Kategori
$cat = isset($_GET['category']) ? $_GET['category'] : '';
if($cat) {
    $query_produk = mysqli_query($conn, "SELECT * FROM products WHERE category = '$cat'");
} else {
    $query_produk = mysqli_query($conn, "SELECT * FROM products");
}

// Ambil data komentar pelanggan
$query_testi = mysqli_query($conn, "SELECT * FROM testimonials ORDER BY tanggal DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICO CUSTOM STORE | Spesialis Stripping & Decal</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>

    <style>
        :root { 
            --primary: #2563eb; 
            --accent: #f59e0b; 
            --slate: #1e293b; 
            --bg: #f8fafc; 
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg); 
            color: #334155; 
            overflow-x: hidden; 
            line-height: 1.6;
        }

        /* --- NAVBAR CINEMATIC --- */
        header { 
            background: white; 
            padding: 18px 8%; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 15px rgba(0,0,0,0.05); 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-brand { font-size: 26px; font-weight: 800; color: var(--primary); text-decoration: none; letter-spacing: -1px; }
        .nav-links { display: flex; list-style: none; gap: 30px; }
        .nav-links a { 
            text-decoration: none; 
            color: var(--slate); 
            font-weight: 600; 
            font-size: 15px; 
            position: relative;
            padding: 5px 0;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--primary);
            transition: 0.3s;
            transform: translateX(-50%);
        }
        .nav-links a:hover::after, .nav-links a.active::after { width: 100%; }
        .nav-links a.active { color: var(--primary); }

        .nav-btn { 
            background: var(--accent); 
            color: var(--slate); 
            padding: 10px 24px; 
            border-radius: 50px; 
            text-decoration: none; 
            font-weight: 700; 
            transition: 0.3s;
        }

        /* --- HERO --- */
        .hero { background: #1e293b; color: white; padding: 120px 8%; display: flex; align-items: center; justify-content: space-between; gap: 50px; min-height: 80vh; }
        .hero-text h1 { font-size: 56px; font-weight: 800; line-height: 1.1; margin-bottom: 20px; }
        .hero-text h1 span { color: var(--accent); }
        .hero-text p { font-size: 19px; color: #94a3b8; margin-bottom: 40px; max-width: 500px; }
        .btn-koleksi { background: var(--accent); color: var(--slate); padding: 18px 45px; border-radius: 50px; text-decoration: none; font-weight: 800; display: inline-block; transition: 0.4s; }
        .btn-koleksi:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(245, 158, 11, 0.3); }
        .hero-img img { width: 420px; border-radius: 40px; box-shadow: 0 30px 60px rgba(0,0,0,0.5); }

        /* --- KATEGORI (SIMPLE - SESUAI KODE AWAL) --- */
        .category-container { display: flex; justify-content: center; gap: 15px; padding: 40px 0; background: white; border-bottom: 1px solid #eee; }
        .cat-btn { padding: 12px 30px; background: #f1f5f9; color: #475569; border-radius: 50px; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .cat-btn.active, .cat-btn:hover { background: var(--primary); color: white; }

        /* --- PRODUK --- */
        .section-padding { padding: 80px 8%; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 35px; }
        .card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; transition: 0.4s; }
        .card:hover { transform: translateY(-10px); }
        .card img { width: 100%; height: 260px; object-fit: cover; }
        .card-body { padding: 25px; }

        /* --- TENTANG KAMI --- */
        .tentang-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .tentang-img img { width: 100%; border-radius: 25px; box-shadow: 0 20px 40px rgba(0,0,0,0.08); }
        .tentang-text h2 { font-size: 38px; margin-bottom: 20px; color: var(--slate); }

        /* --- GALERI --- */
        .galeri-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 40px; }
        .galeri-item { height: 300px; border-radius: 20px; overflow: hidden; }
        .galeri-item img { width: 100%; height: 100%; object-fit: cover; transition: 0.6s; }
        .galeri-item:hover img { transform: scale(1.1); }

        /* --- TESTIMONI (SIMPLE - TANPA MUTIR-MUTIR) --- */
        .testi-section { background: #f1f5f9; padding: 80px 8%; }
        .testi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px; }
        .testi-card { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-bottom: 4px solid var(--primary); }

        /* --- FOOTER & MAPS --- */
        footer { background: var(--slate); color: #94a3b8; padding: 80px 8% 30px 8%; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 50px; }
        .footer-col h3 { color: white; margin-bottom: 25px; position: relative; padding-bottom: 10px; }
        .footer-col h3::after { content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: var(--primary); }
        .maps-container { width: 100%; height: 220px; border-radius: 20px; overflow: hidden; margin-top: 20px; border: 2px solid #334155; }
        
        .social-icons { display: flex; gap: 15px; margin-top: 25px; }
        .social-icons a { width: 45px; height: 45px; background: rgba(255,255,255,0.05); display: flex; justify-content: center; align-items: center; border-radius: 12px; transition: 0.3s; text-decoration: none; color: white; }
        .social-icons a:hover { transform: translateY(-5px); background: var(--primary); }
        .social-icons img { width: 24px; height: 24px; }

        .wa-float { position: fixed; bottom: 35px; right: 35px; background: #22c55e; color: white; width: 65px; height: 65px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 35px; z-index: 1000; box-shadow: 0 10px 25px rgba(34,197,94,0.4); transition: 0.4s; }
    </style>
</head>
<body id="home">

<header id="main-nav">
    <a href="#home" class="nav-brand">AICO CUSTOM STORE</a>
    <ul class="nav-links">
        <li><a href="#home" class="nav-item">Beranda</a></li>
        <li><a href="#katalog" class="nav-item">Koleksi</a></li>
        <li><a href="#galeri" class="nav-item">Galeri</a></li>
        <li><a href="#tentang" class="nav-item">Tentang</a></li>
        <li><a href="#komentar" class="nav-item">Komentar</a></li>
        <li><a href="#kontak" class="nav-item">Kontak</a></li>
    </ul>
    <a href="https://wa.me/62895336868531" target="_blank" class="nav-btn">Pesan WA</a>
</header>

<section class="hero">
    <div class="hero-text" data-aos="fade-right">
        <h1>Bikin Motormu<br><span>Makin Ganteng!</span></h1>
        <p>Pusat Stripping, Decal, dan Stiker Custom Premium. Bahan berkualitas, warna tajam, dan tahan lama.</p>
        <a href="#katalog" class="btn-koleksi">LIHAT KOLEKSI</a>
    </div>
    <div class="hero-img" data-aos="fade-left">
        <img src="https://p16-images-common-sign-sg.tokopedia-static.net/tos-maliva-i-o3syd03w52-us/55e46a4846464471aa3c32204c657117~tplv-o3syd03w52-resize-jpeg:215:215.jpeg?lk3s=7a10017b&x-expires=1773848729&x-signature=zVb7lR45JXtRvj01R8lvNgmyVV8%3D&x-signature-webp=fqtNDPmtFgXJ%2Fm6RuApzoFUjgU4%3D" alt="Hero Image">
    </div>
</section>

<div class="category-container" id="katalog">
    <a href="index.php?#katalog" class="cat-btn <?php echo !$cat ? 'active':''; ?>">Semua</a>
    <a href="index.php?category=Motor#katalog" class="cat-btn <?php echo $cat=='Motor' ? 'active':''; ?>">Motor</a>
    <a href="index.php?category=Vape#katalog" class="cat-btn <?php echo $cat=='Vape' ? 'active':''; ?>">Vape</a>
    <a href="index.php?category=Custom#katalog" class="cat-btn <?php echo $cat=='Custom' ? 'active':''; ?>">Custom</a>
</div>

<div class="section-padding" style="padding-top: 40px;">
    <div class="product-grid">
        <?php while($p = mysqli_fetch_assoc($query_produk)): ?>
        <div class="card" data-aos="fade-up">
            <img src="assets/img/<?php echo $p['image']; ?>" onerror="this.src='https://via.placeholder.com/300x240'">
            <div class="card-body">
                <small style="color:var(--primary); font-weight:700; text-transform:uppercase;"><?php echo $p['category']; ?></small>
                <h3 style="margin:10px 0; font-size:20px;"><?php echo $p['name']; ?></h3>
                <span style="font-size:24px; font-weight:800; color:var(--primary); display:block; margin-bottom:15px;">Rp <?php echo number_format($p['price'], 0, ',', '.'); ?></span>
                <a href="https://wa.me/62895336868531?text=Halo%20Aico,%20saya%20mau%20order%20<?php echo urlencode($p['name']); ?>" class="nav-btn" style="display:block; text-align:center; padding:14px; border-radius:12px; background:#22c55e; color:white;">
                    <i class="fab fa-whatsapp"></i> PESAN SEKARANG
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<section id="galeri" class="section-padding" style="background: white;">
    <div style="text-align:center; margin-bottom:50px;" data-aos="fade-up">
        <h2>Galeri Produksi</h2>
        <p>Kondisi workshop dan proses pengerjaan tim AICO STORE.</p>
    </div>
    <div class="galeri-grid">
        <div class="galeri-item" data-aos="zoom-in"><img src="https://images.unsplash.com/photo-1611348586804-61bf6c080437?w=500&auto=format" alt="Workshop 1"></div>
        <div class="galeri-item" data-aos="zoom-in" data-aos-delay="100"><img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=500&auto=format" alt="Produksi 2"></div>
        <div class="galeri-item" data-aos="zoom-in" data-aos-delay="200"><img src="https://images.unsplash.com/photo-1530124560676-4fbc91848b61?w=500&auto=format" alt="Quality Control"></div>
    </div>
</section>

<section id="tentang" class="section-padding">
    <div class="tentang-grid">
        <div class="tentang-img" data-aos="fade-right">
            <img src="https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=800&auto=format" alt="About Aico">
        </div>
        <div class="tentang-text" data-aos="fade-left">
            <small style="color:var(--primary); font-weight:800; text-transform:uppercase;">Tentang AICO STORE</small>
            <h2>Kualitas Premium Untuk Kendaraan Kesayangan</h2>
            <p>Berawal dari hobi modifikasi, AICO STORE kini hadir secara profesional untuk melayani kebutuhan stripping dan decal motor. Kami hanya menggunakan bahan stiker grade otomotif yang tidak merusak cat asli.</p>
            <p style="margin-top:15px;">Kami percaya bahwa setiap kendaraan memiliki cerita, dan stiker kami membantu menceritakannya lebih keren!</p>
        </div>
    </div>
</section>

<section id="komentar" class="testi-section">
    <h2 style="text-align:center; margin-bottom:50px;" data-aos="fade-up">Komentar Pelanggan</h2>
    <div class="testi-grid">
        <?php while($t = mysqli_fetch_assoc($query_testi)): ?>
        <div class="testi-card" data-aos="fade-up">
            <div style="color: var(--accent); margin-bottom:10px;">★★★★★</div>
            <b style="display:block; margin-bottom:5px;"><?php echo $t['nama_pelanggan']; ?></b>
            <p style="color:#64748b; font-style:italic;">"<?php echo $t['isi_komentar']; ?>"</p>
        </div>
        <?php endwhile; ?>
    </div>
</section>

<footer id="kontak">
    <div class="footer-grid">
        <div class="footer-col" data-aos="fade-up">
            <h3 style="color:white;">AICO STORE</h3>
            <p>Spesialis stripping motor, decal custom, dan stiker vape dengan kualitas bahan terbaik sejak 2024.</p>
            <div class="social-icons">
                <a href="https://instagram.com/aicostore" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://tiktok.com/@aicostore" target="_blank"><i class="fab fa-tiktok"></i></a>
                <a href="https://tokopedia.com" target="_blank" style="background:white;"><img src="https://ecs7.tokopedia.net/assets-tokopedia-lite/v2/zeus/production/3f060f69.png" alt="Tokopedia"></a>
                <a href="https://shopee.co.id" target="_blank" style="background:white;"><img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/ca5a12f113027910.png" alt="Shopee"></a>
            </div>
        </div>
        
        <div class="footer-col" data-aos="fade-up" data-aos-delay="100">
            <h3>Kontak Kami</h3>
            <p><i class="fas fa-phone-alt"></i> +62 895-3368-68531</p>
            <p><i class="fas fa-envelope"></i> admin@aicostore.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Depok, Jawa Barat, Indonesia</p>
        </div>

        <div class="footer-col" data-aos="fade-up" data-aos-delay="200">
            <h3>Lokasi Workshop</h3>
            <div class="maps-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126893.30325985854!2d106.74614144302927!3d-6.353386000000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69ec069c9b68a3%3A0x6968749a08e16937!2sDepok%2C%20Kota%20Depok%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1710000000000" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        © 2026 <b>AICO STORE OFFICIAL</b>. Seluruh Hak Cipta Dilindungi.
    </div>
</footer>

<a href="https://wa.me/62895336868531" class="wa-float" target="_blank">
    <i class="fab fa-whatsapp"></i>
</a>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });

    // --- GSAP Cinematic Smooth Scroll (Hanya untuk Navbar) ---
    document.querySelectorAll('.nav-item, .btn-koleksi').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if(targetId.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(targetId);
                if(target) {
                    gsap.to(window, {
                        duration: 1.2,
                        scrollTo: { y: target, offsetY: 80 },
                        ease: "power4.inOut"
                    });
                }
            }
        });
    });

    // --- Navbar Update saat Scroll ---
    window.addEventListener('scroll', () => {
        const header = document.getElementById('main-nav');
        const navItems = document.querySelectorAll('.nav-item');
        const sections = document.querySelectorAll('section, header, div[id]');
        
        if (window.scrollY > 50) {
            header.style.padding = "12px 8%";
            header.style.backgroundColor = "rgba(255, 255, 255, 0.9)";
            header.style.backdropFilter = "blur(10px)";
        } else {
            header.style.padding = "18px 8%";
            header.style.backgroundColor = "white";
        }

        let current = "";
        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            if (pageYOffset >= sectionTop - 150) {
                current = section.getAttribute("id");
            }
        });
        navItems.forEach((item) => {
            item.classList.remove("active");
            if (item.getAttribute("href") === `#${current}`) {
                item.classList.add("active");
            }
        });
    });
</script>

</body>
</html>