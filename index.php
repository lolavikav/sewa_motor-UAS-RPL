<?php 
include 'koneksi.php'; // Pastikan session_start() ada di dalam koneksi.php
if(!isset($_SESSION['admin'])) header("Location: login.php");

// 1. Hitung data untuk kartu statistik (Pengelolaan Stok & Laporan)
$m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM motor"))['t'];
$p = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM pelanggan"))['t'];
$a = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM motor WHERE status='Disewa'"))['t'];
// Menghitung motor yang bisa disewa
$tersedia = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM motor WHERE status='Tersedia'"))['t'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sewa Motor Pinky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff5f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { height: 100vh; background: linear-gradient(to bottom, #ffc3a0, #ffafbd); color: white; padding-top: 20px; position: fixed; width: 16.666667%; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; border-radius: 10px; margin: 5px; }
        .sidebar a:hover { background: rgba(255,255,255,0.3); }
        .card-pink { background: white; border-left: 5px solid #ffafbd; border-radius: 15px; box-shadow: 0 5px 15px rgba(255,175,189,0.2); transition: 0.3s; }
        .card-pink:hover { transform: translateY(-5px); }
        .main-content { margin-left: 16.666667%; }
        /* Tombol Logout Merah Kotak Sesuai Request */
        .btn-logout { background-color: #d63344 !important; color: white !important; font-weight: bold; margin-top: 50px !important; text-align: center; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar text-center">
                <h4>🌸 Sewa Motor</h4>
                <p>Hello, <?php echo $_SESSION['admin']; ?> ✨</p>
                <hr>
                <a href="index.php" style="background: rgba(255,255,255,0.2);">Dashboard</a>
                <a href="admin.php">Data Admin</a>
                <a href="motor.php">Data Motor</a>
                <a href="pelanggan.php">Data Pelanggan</a>
                <a href="transaksi.php">Transaksi</a>
                <a href="laporan.php">Laporan</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>

            <div class="col-md-10 p-5 main-content">
                <h2 style="color: #ff85a1;">Dashboard Overview</h2>
                <p class="text-muted">Pantau status penyewaan motor kamu di sini.</p>
                
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card card-pink p-4">
                            <h6 class="text-muted">Total Motor</h6>
                            <h2 style="color: #ff85a1;"><?php echo $m; ?> 🛵</h2>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-pink p-4" style="border-left-color: #28a745;">
                            <h6 class="text-muted">Motor Tersedia</h6>
                            <h2 style="color: #28a745;"><?php echo $tersedia; ?> ✅</h2>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-pink p-4" style="border-left-color: #ffc107;">
                            <h6 class="text-muted">Transaksi Aktif</h6>
                            <h2 style="color: #ffc107;"><?php echo $a; ?> 📋</h2>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-pink p-4" style="border-left-color: #6f42c1;">
                            <h6 class="text-muted">Pelanggan</h6>
                            <h2 style="color: #6f42c1;"><?php echo $p; ?> 👥</h2>
                        </div>
                    </div>
                </div>

                <div class="mt-5 p-5 text-center bg-white shadow-sm" style="border-radius: 20px;">
                    <h4 style="color: #ffafbd;">Selamat Datang di Sistem Sewa Motor Pinky </h4>
                    <p class="text-muted">Gunakan menu di samping untuk mulai mengelola data kendaraan dan pelanggan.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>