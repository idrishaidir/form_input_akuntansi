<?php
$host = "localhost";
$user = "root";       // Ganti jika username database Anda bukan 'root'
$pass = "root123";           // Ganti jika database Anda ada passwordnya
$db   = "akuntansi_db"; // Pastikan nama database ini SAMA PERSIS dengan yang di phpMyAdmin

// Membuat koneksi ke database
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Cek jika koneksi gagal (opsional, untuk debugging di dalam file ini sendiri)
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>