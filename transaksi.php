<?php 
include 'koneksi.php'; 

// Pengaman agar tidak muncul Notice: Session sudah aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Satpam: Cek login admin
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// 1. LOGIKA TAMBAH SEWA (INSERT DATA)
if (isset($_POST['sewa'])) {
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_motor = $_POST['id_motor'];
    $lama = $_POST['lama'];
    $tgl_sewa = date('Y-m-d');

    // Masukkan data ke tabel transaksi_sewa
    $query_sewa = "INSERT INTO transaksi_sewa (id_pelanggan, id_motor, tgl_sewa, lama_sewa, denda) 
                   VALUES ('$id_pelanggan', '$id_motor', '$tgl_sewa', '$lama', '0')";
    
    if (mysqli_query($conn, $query_sewa)) {
        // Otomatis ubah status motor jadi 'Disewa' agar tidak muncul lagi di pilihan
        mysqli_query($conn, "UPDATE motor SET status='Disewa' WHERE id_motor='$id_motor'");
        echo "<script>alert('Motor Berhasil Disewa! ✨'); window.location='transaksi.php';</script>";
    }
}

// 2. LOGIKA PENGEMBALIAN & HITUNG DENDA OTOMATIS
if (isset($_GET['kembali'])) {
    $id_t = $_GET['kembali'];
    $id_m = $_GET['id_m'];
    $tgl_kembali_skrg = date('Y-m-d');
    
    // Ambil data sewa untuk hitung keterlambatan
    $cek = mysqli_query($conn, "SELECT tgl_sewa, lama_sewa FROM transaksi_sewa WHERE id_transaksi='$id_t'");
    $d = mysqli_fetch_assoc($cek);
    
    $tgl_sewa = $d['tgl_sewa'];
    $lama = $d['lama_sewa'];
    // Hitung tanggal seharusnya motor kembali
    $tgl_harusnya = date('Y-m-d', strtotime($tgl_sewa . " + $lama days"));
    
    $denda = 0;
    // Jika hari ini lebih besar dari tanggal seharusnya kembali, hitung denda per hari
    if ($tgl_kembali_skrg > $tgl_harusnya) {
        $selisih_detik = strtotime($tgl_kembali_skrg) - strtotime($tgl_harusnya);
        $telat = floor($selisih_detik / 86400); // Menggunakan floor agar hitungan hari bulat
        $denda = $telat * 50000; // Denda Rp 50.000 per hari keterlambatan
    }

    // Update denda dan tanggal kembali asli di tabel transaksi, lalu ubah motor jadi 'Tersedia' lagi
    mysqli_query($conn, "UPDATE transaksi_sewa SET denda='$denda', tgl_kembali_asli='$tgl_kembali_skrg' WHERE id_transaksi='$id_t'");
    mysqli_query($conn, "UPDATE motor SET status='Tersedia' WHERE id_motor='$id_m'"); 
    
    // Format Rupiah untuk alert
    $format_denda = "Rp " . number_format($denda, 0, ',', '.');
    echo "<script>alert('Motor Berhasil Dikembalikan! ✨ Denda: $format_denda'); window.location='transaksi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Motor Pinky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff5f8; font-family: 'Poppins', sans-serif; }
        .sidebar { height: 100vh; background: linear-gradient(to bottom, #ffc3a0, #ffafbd); color: white; padding-top: 20px; position: fixed; width: 220px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; margin: 5px; border-radius: 10px; }
        .sidebar a:hover { background: rgba(255,255,255,0.3); }
        .main-content { margin-left: 240px; padding: 40px; }
        .card { border-radius: 20px; border: none; box-shadow: 0 5px 15px rgba(255,175,189,0.2); }
        .btn-pink { background: #ff9a9e; color: white; border: none; border-radius: 10px; padding: 10px 20px; }
        .btn-pink:hover { background: #fbc2eb; color: white; }
        .btn-logout { background-color: #d63344 !important; color: white !important; margin-top: 50px !important; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <div class="sidebar text-center">
        <h4>🌸 Sewa Motor</h4>
        <hr>
        <a href="index.php">Dashboard</a>
        <a href="admin.php">Data Admin</a>
        <a href="motor.php">Data Motor</a>
        <a href="pelanggan.php">Data Pelanggan</a>
        <a href="transaksi.php" style="background: rgba(255,255,255,0.3);">Transaksi</a>
        <a href="laporan.php">Laporan</a>
        <a href="logout.php" class="btn-logout">Logout</a> 
    </div>

    <div class="main-content">
        <h3 style="color: #ff6a95;" class="mb-4">💖 Transaksi Penyewaan 💖</h3>
        
        <div class="card p-4 mb-4">
            <h5 class="mb-3" style="color: #ff6a95;">✨ Input Sewa Baru</h5>
            <form method="POST" action="" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pelanggan</label>
                    <select name="id_pelanggan" class="form-select" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php
                        $p = mysqli_query($conn, "SELECT * FROM pelanggan");
                        while($rp = mysqli_fetch_array($p)) echo "<option value='".$rp['id_pelanggan']."'>".$rp['nama']."</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Motor (Tersedia)</label>
                    <select name="id_motor" class="form-select" required>
                        <option value="">-- Pilih Motor --</option>
                        <?php
                        $m = mysqli_query($conn, "SELECT * FROM motor WHERE status='Tersedia'");
                        while($rm = mysqli_fetch_array($m)) echo "<option value='".$rm['id_motor']."'>".$rm['merk']." (".$rm['plat_nomor'].")</option>";
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Lama (Hari)</label>
                    <input type="number" name="lama" class="form-control" placeholder="0" min="1" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="sewa" class="btn btn-pink w-100">Sewa ✨</button>
                </div>
            </form>
        </div>

        <div class="card p-0 overflow-hidden">
            <div class="p-3 bg-white border-bottom">
                <h5 class="mb-0" style="color: #ff6a95;">📋 Daftar Kendaraan Sedang Disewa</h5>
            </div>
            <table class="table table-hover mb-0">
                <thead style="background-color: #ffafbd; color: white;">
                    <tr>
                        <th>Pelanggan</th> <th>Motor</th> <th>Tgl Sewa</th> <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php
                    $query = "SELECT t.id_transaksi, t.tgl_sewa, t.id_motor, p.nama, m.merk 
                              FROM transaksi_sewa t
                              JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan 
                              JOIN motor m ON t.id_motor = m.id_motor 
                              WHERE m.status = 'Disewa'"; 

                    $res = mysqli_query($conn, $query);
                    if(mysqli_num_rows($res) == 0) {
                        echo "<tr><td colspan='4' class='text-center p-4 text-muted'>Tidak ada transaksi aktif 🌸</td></tr>";
                    }

                    while($row = mysqli_fetch_array($res)){
                        echo "<tr>
                                <td class='align-middle px-3'>".$row['nama']."</td>
                                <td class='align-middle'>".$row['merk']."</td>
                                <td class='align-middle'>".date('d/m/Y', strtotime($row['tgl_sewa']))."</td>
                                <td class='px-3'>
                                    <a href='transaksi.php?kembali=".$row['id_transaksi']."&id_m=".$row['id_motor']."' 
                                       class='btn btn-sm btn-success py-2 px-3' 
                                       onclick='return confirm(\"Konfirmasi: Motor sudah kembali?\")'
                                       style='border-radius: 8px; font-weight: bold;'>Kembalikan</a>
                                </td>
                              </tr>";
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>