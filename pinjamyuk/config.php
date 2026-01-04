<?php
// Konfigurasi Database
$host = "localhost";
$user = "root";
$pass = ""; // Kosongkan jika pakai XAMPP default
$db   = "pinjamyuk"; // Pastikan nama database sama persis

// Melakukan Koneksi
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek Koneksi
if (!$koneksi) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
}
?>