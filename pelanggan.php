<?php 
include 'koneksi.php'; 
if(!isset($_SESSION['admin'])) header("Location: login.php");

// 1. TAMBAH PELANGGAN (Create)
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama']; 
    $hp = $_POST['hp']; 
    $alamat = $_POST['alamat'];
    
    // Menggunakan no_telp sesuai struktur database kamu
    $query = "INSERT INTO pelanggan (nama, no_telp, alamat) VALUES ('$nama', '$hp', '$alamat')";
    $hasil = mysqli_query($conn, $query);
    
    if($hasil){
        echo "<script>alert('Pelanggan Baru Berhasil Ditambah! ✨'); window.location='pelanggan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// 2. HAPUS PELANGGAN (Delete)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Hapus relasi agar tidak Error Foreign Key
    mysqli_query($conn, "DELETE FROM pengembalian WHERE id_transaksi IN (SELECT id_transaksi FROM transaksi_sewa WHERE id_pelanggan='$id')");
    mysqli_query($conn, "DELETE FROM transaksi_sewa WHERE id_pelanggan='$id'");
    mysqli_query($conn, "DELETE FROM pelanggan WHERE id_pelanggan='$id'");
    
    echo "<script>alert('Data Pelanggan Berhasil Dihapus!'); window.location='pelanggan.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Master Pelanggan Pinky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff5f8; font-family: 'Segoe UI', sans-serif; }
        .sidebar { height: 100vh; background: linear-gradient(to bottom, #ffc3a0, #ffafbd); color: white; padding-top: 20px; position: fixed; width: 16.666667%; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; border-radius: 10px; margin: 5px; }
        .sidebar a:hover { background: rgba(255,255,255,0.3); }
        .main-content { margin-left: 17%; padding: 40px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(255,175,189,0.2); }
        .btn-pink { background: #ff9a9e; color: white; border: none; border-radius: 10px; padding: 10px; }
        .btn-logout { background-color: #d63344 !important; color: white !important; font-weight: bold; margin-top: 50px !important; text-align: center; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar text-center">
                <h4>🌸 Sewa Motor</h4>
                <p>Hello, admin ✨</p>
                <hr>
                <a href="index.php">Dashboard</a>
                <a href="admin.php">Data Admin</a>
                <a href="motor.php">Data Motor</a>
                <a href="pelanggan.php" style="background: rgba(255,255,255,0.2);">Data Pelanggan</a>
                <a href="transaksi.php">Transaksi</a>
                <a href="laporan.php">Laporan</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>

            <div class="col-md-10 main-content">
                <h3 style="color: #ff6a95;" class="mb-4">👥 Master Pelanggan 👥</h3>
                
                <div class="card p-4 mb-4">
                    <form method="POST" class="row g-3">
                        <div class="col-md-4"><input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required></div>
                        <div class="col-md-3"><input type="text" name="hp" class="form-control" placeholder="No. Telp (WhatsApp)" required></div>
                        <div class="col-md-3"><input type="text" name="alamat" class="form-control" placeholder="Alamat" required></div>
                        <div class="col-md-2"><button type="submit" name="tambah" class="btn btn-pink w-100">Simpan 💖</button></div>
                    </form>
                </div>

                <div class="card p-0 overflow-hidden">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #ffafbd; color: white;">
                            <tr>
                                <th>Nama Pelanggan</th> <th>No. Telp</th> <th>Alamat</th> <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <?php
                            $res = mysqli_query($conn, "SELECT * FROM pelanggan");
                            if(mysqli_num_rows($res) == 0) echo "<tr><td colspan='4' class='text-center p-4 text-muted'>Belum ada data pelanggan 🌸</td></tr>";
                            
                            while($row = mysqli_fetch_array($res)){
                                echo "<tr>
                                        <td class='align-middle'>".$row['nama']."</td>
                                        <td class='align-middle'>".$row['no_telp']."</td>
                                        <td class='align-middle'>".$row['alamat']."</td>
                                        <td>
                                            <a href='pelanggan.php?hapus=".$row['id_pelanggan']."' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Hapus pelanggan ini?\")'>Hapus</a>
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