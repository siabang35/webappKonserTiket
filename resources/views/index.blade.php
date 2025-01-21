<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan Tiket Konser</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="main-header">
        <h1>Sistem Penjualan Tiket Konser</h1>
        <nav aria-label="Main Navigation">
            <ul class="nav-list">
                <li><a href="index.php" class="nav-link">Beranda</a></li>
                <li><a href="login.php" class="nav-link">Login</a></li>
                <li><a href="register.php" class="nav-link">Register</a></li>
            </ul>
        </nav>
    </header>
    <main class="container">
        <h2>Daftar Konser</h2>

        <!-- Daftar konser -->
        <div class="card">
            <h3>Konser Band A</h3>
            <p><strong>Tanggal:</strong> 25 Desember 2024</p>
            <p><strong>Lokasi:</strong> Stadion Utama</p>
            <a href="detail_konser.php?id=1" class="btn">Detail</a>
        </div>
        <div class="card">
            <h3>Konser Band B</h3>
            <p><strong>Tanggal:</strong> 30 Desember 2024</p>
            <p><strong>Lokasi:</strong> Gedung Kesenian</p>
            <a href="detail_konser.php?id=2" class="btn">Detail</a>
        </div>
    </main>
    <footer class="main-footer">
        <p>&copy; 2024 Sistem Penjualan Tiket Konser. Semua Hak Dilindungi.</p>
    </footer>
</body>
</html>
