<?php
// Paste kode ini di cek_koneksi.php dan buka di browser
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h3>Sedang mengetes koneksi...</h3>";

// Sesuaikan path ini jika perlu
if (file_exists('config/koneksi.php')) {
    include 'config/koneksi.php';
    echo "✅ File config/koneksi.php ditemukan.<br>";
} else {
    die("❌ File config/koneksi.php TIDAK ditemukan. Pastikan folder 'config' ada.");
}

if ($koneksi) {
    echo "✅ Koneksi ke database BERHASIL!<br>";
} else {
    echo "❌ Koneksi GAGAL: " . mysqli_connect_error();
}
?>