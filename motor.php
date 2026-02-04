<?php 
include 'koneksi.php'; 
if(!isset($_SESSION['admin'])) header("Location: login.php");

// 1. TAMBAH MOTOR
if (isset($_POST['tambah'])) {
    $merk = $_POST['merk']; 
    $plat = $_POST['plat']; 
    $harga = $_POST['harga'];
    // Status default 'Tersedia' agar muncul di dropdown transaksi
    mysqli_query($conn, "INSERT INTO motor (merk, plat_nomor, harga_per_hari, status) VALUES ('$merk', '$plat', '$harga', 'Tersedia')");
    header("Location: motor.php");
}

// 2. HAPUS MOTOR (DENGAN PERBAIKAN FOREIGN KEY)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Hapus dulu data di tabel transaksi agar tidak error "Cannot delete parent row"
    mysqli_query($conn, "DELETE FROM transaksi_sewa WHERE id_motor='$id'");
    
    // Baru hapus data motornya
    mysqli_query($conn, "DELETE FROM motor WHERE id_motor='$id'");
    
    echo "<script>alert('Motor dan riwayat transaksinya berhasil dihapus ✨'); window.location='motor.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Master Motor Pinky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff5f8; font-family: 'Poppins', sans-serif; }
        .sidebar { height: 100vh; background: linear-gradient(to bottom, #ffc3a0, #ffafbd); color: white; padding-top: 20px; position: fixed; width: 16.666667%; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; margin: 5px; border-radius: 10px; }
        .sidebar a:hover { background: rgba(255,255,255,0.3); }
        .btn-pink { background: #ff9a9e; color: white; border: none; }
        .btn-pink:hover { background: #fbc2eb; }
        .main-content { margin-left: 17%; padding: 40px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(255,175,189,0.2); }
        /* Style Logout Merah Kotak */
        .btn-logout { background-color: #d63344 !important; color: white !important; font-weight: bold; margin-top: 50px !important; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar text-center">
                <h4>🌸 Sewa Motor</h4>
                <hr>
                <a href="index.php">Dashboard</a>
                <a href="admin.php">Data Admin</a>
                <a href="motor.php" style="background: rgba(255,255,255,0.2);">Data Motor</a>
                <a href="pelanggan.php">Data Pelanggan</a>
                <a href="transaksi.php">Transaksi</a>
                <a href="laporan.php">Laporan</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
            
            <div class="main-content col-md-10">
                <h3 style="color: #ff6a95;" class="mb-4">✨ Kelola Data Motor ✨</h3>
                
                <div class="card p-4 mb-4">
                    <form method="POST" class="row g-3">
                        <div class="col-md-4"><input type="text" name="merk" class="form-control" placeholder="Merk Motor (Vario, Scoopy, dll)" required></div>
                        <div class="col-md-3"><input type="text" name="plat" class="form-control" placeholder="Plat Nomor" required></div>
                        <div class="col-md-3"><input type="number" name="harga" class="form-control" placeholder="Harga/Hari" required></div>
                        <div class="col-md-2"><button type="submit" name="tambah" class="btn btn-pink w-100">Tambah ✨</button></div>
                    </form>
                </div>

                <div class="card p-0 overflow-hidden">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #ffafbd; color: white;">
                            <tr>
                                <th>Merk</th> <th>Plat</th> <th>Harga/Hari</th> <th>Status</th> <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM motor");
                            if(mysqli_num_rows($res) == 0) echo "<tr><td colspan='5' class='text-center p-4 text-muted'>Belum ada data motor 🌸</td></tr>";
                            
                            while($row = mysqli_fetch_array($res)){
                                $badge = ($row['status'] == 'Tersedia') ? 'bg-success' : 'bg-warning text-dark';
                                echo "<tr>
                                        <td class='align-middle'>".$row['merk']."</td> 
                                        <td class='align-middle'>".$row['plat_nomor']."</td>
                                        <td class='align-middle'>Rp ".number_format($row['harga_per_hari'])."</td>
                                        <td class='align-middle'><span class='badge $badge'>".$row['status']."</span></td>
                                        <td class='align-middle'>
                                            <a href='motor.php?hapus=".$row['id_motor']."' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Hapus motor ini? Semua riwayat sewa motor ini juga akan terhapus.\")'>Hapus</a>
                                        </td>
                                      </tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>