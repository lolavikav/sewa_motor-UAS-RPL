<?php
include 'koneksi.php';

if (isset($_POST['sewa'])) {
    $id_p = $_POST['id_pelanggan'];
    $id_m = $_POST['id_motor'];
    $lama = $_POST['lama'];
    $tgl  = date('Y-m-d');

    // 1. Ambil harga motor untuk hitung total
    $res = mysqli_query($conn, "SELECT harga_per_hari FROM motor WHERE id_motor='$id_m'");
    $data = mysqli_fetch_assoc($res);
    
    // Pastikan data motor ditemukan sebelum menghitung
    if ($data) {
        $total = $data['harga_per_hari'] * $lama;

        // 2. Simpan Transaksi
        $simpan = mysqli_query($conn, "INSERT INTO transaksi_sewa (id_pelanggan, id_motor, tgl_sewa, lama_sewa, total_bayar) 
                                       VALUES ('$id_p', '$id_m', '$tgl', '$lama', '$total')");

        if ($simpan) {
            // 3. Update Stok Motor jadi 'Disewa' agar angka di Dashboard berubah jadi 1
            // Ini akan membuat query COUNT di index.php mendeteksi transaksi aktif
            mysqli_query($conn, "UPDATE motor SET status='Disewa' WHERE id_motor='$id_m'");
            
            echo "<script>alert('Sewa Berhasil! ✨'); window.location='transaksi.php';</script>";
        } else {
            echo "<script>alert('Gagal simpan transaksi!'); window.location='transaksi.php';</script>";
        }
    } else {
        echo "<script>alert('Motor tidak ditemukan!'); window.location='transaksi.php';</script>";
    }
}
?>