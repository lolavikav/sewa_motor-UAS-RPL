<?php 
include 'koneksi.php'; 

// Proteksi halaman: Balikkan ke login jika belum masuk
if(!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Logika Tambah Admin
if (isset($_POST['tambah'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    
    // Query simpan ke database
    mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('$user', '$pass')");
    header("Location: admin.php");
}

// Logika Hapus Admin
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM admin WHERE id_admin=$id");
    header("Location: admin.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Admin Pinky</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Tambahan style agar tabel mirip gambar kamu */
        .table thead th {
            border-bottom: 2px solid #ff6a95;
            color: #4a4a4a;
        }
        .main-title {
            color: #ff6a95;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-center">🌸 Sewa Motor</h4>
        <p class="text-center">Hello, admin ✨</p>
        <hr>
        <a href="index.php">Dashboard</a>
        <a href="admin.php" class="active" style="background: rgba(255,255,255,0.4);">Data Admin</a>
        <a href="motor.php">Data Motor</a>
        <a href="pelanggan.php">Data Pelanggan</a>
        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan</a>
        <a href="logout.php" class="mt-5 btn-logout">Logout</a>
    </div>

    <div class="main-content">
        <h3 class="main-title mb-4">🎀 Manajemen Admin 🎀</h3>
        
        <div class="card p-4 mb-4 shadow-sm border-0" style="border-radius: 20px;">
            <form method="POST" class="row g-3 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="user" class="form-control" placeholder="Username" required style="border-radius: 10px;">
                </div>
                <div class="col-md-5">
                    <input type="password" name="pass" class="form-control" placeholder="Password" required style="border-radius: 10px;">
                </div>
                <div class="col-md-2">
                    <button type="submit" name="tambah" class="btn btn-pink w-100" style="background-color: #ff9a9e; color: white; border-radius: 10px; border: none; padding: 10px;">Tambah</button>
                </div>
            </form>
        </div>

        <div class="card p-4 shadow-sm border-0" style="border-radius: 20px;">
            <table class="table">
                <thead>
                    <tr>
                        <th width="70%">Username</th>
                        <th width="30%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data dari tabel admin
                    $query = mysqli_query($conn, "SELECT * FROM admin");
                    while($row = mysqli_fetch_array($query)) {
                        echo "<tr>
                                <td class='align-middle'>".$row['username']."</td>
                                <td class='text-center'>
                                    <a href='admin.php?hapus=".$row['id_admin']."' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Hapus admin ini?\")' style='border-radius: 8px;'>Hapus</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>