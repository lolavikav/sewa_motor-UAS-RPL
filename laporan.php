<?php 
include 'koneksi.php';
if(!isset($_SESSION['admin'])) header("Location: login.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pinky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">🌸 Sewa Motor</h4>
        <hr>
        <a href="index.php">Dashboard</a>
        <a href="admin.php">Data Admin</a>
        <a href="motor.php">Data Motor</a>
        <a href="pelanggan.php">Data Pelanggan</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php" style="background: rgba(255,255,255,0.3);">Laporan</a>
        <a href="logout.php" class="btn-logout" style="background-color: #d63344; color: white !important; padding: 12px; border-radius: 12px; text-align: center; font-weight: bold; display: block; margin: 40px 10px; text-decoration: none;">Logout</a> 
    </div>

    <div class="main-content">
        <h3 style="color: #ff6a95;" class="mb-4">📊 Laporan Riwayat Sewa 📊</h3>
        <div class="card p-4 shadow-sm border-0" style="border-radius: 20px;">
            <table class="table table-hover">
                <thead>
                    <tr style="color: #ff6a95;">
                        <th>Pelanggan</th> <th>Motor</th> <th>Tgl Sewa</th> <th>Denda</th> <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $res = mysqli_query($conn, "SELECT * FROM transaksi_sewa 
                            JOIN pelanggan ON transaksi_sewa.id_pelanggan = pelanggan.id_pelanggan 
                            JOIN motor ON transaksi_sewa.id_motor = motor.id_motor 
                            ORDER BY id_transaksi DESC");
                    while($row = mysqli_fetch_array($res)){
                        $status = ($row['status'] == 'Disewa') ? '🔴 Aktif' : '✅ Selesai';
                        echo "<tr>
                                <td>".$row['nama']."</td>
                                <td>".$row['merk']."</td>
                                <td>".$row['tgl_sewa']."</td>
                                <td class='text-danger'>Rp ".number_format($row['denda'])."</td>
                                <td>$status</td>
                              </tr>";
                    } ?>
                </tbody>
            </table>
            <button onclick="window.print()" class="btn btn-pink mt-3">🖨️ Cetak Laporan</button>
        </div>
    </div>
</body>
</html>