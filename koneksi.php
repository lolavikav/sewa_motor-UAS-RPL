<?php
$conn = mysqli_connect("localhost", "root", "", "db_sewa_motor");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }
session_start();
?>