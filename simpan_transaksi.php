<?php
// Set header agar browser tahu responsnya berupa JSON
header('Content-Type: application/json');

// Pastikan file koneksi sudah dimasukkan
include 'config/koneksi.php'; //

// Ambil data JSON dari body request
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Cek apakah data valid
if (json_last_error() !== JSON_ERROR_NONE || !isset($data['details'])) {
    echo json_encode(['status' => 'error', 'message' => 'Format data tidak valid.']);
    exit;
}

// Ambil data Header
$tanggal   = $data['tanggal'] ?? null;
$jenis     = $data['jenis'] ?? null;
$bukti     = $data['bukti'] ?? null;
$deskripsi = $data['deskripsi'] ?? null;
$details   = $data['details'] ?? [];

// Validasi minimal input
if (empty($tanggal) || empty($jenis) || empty($bukti) || empty($details)) {
    echo json_encode(['status' => 'error', 'message' => 'Data header dan detail jurnal tidak lengkap.']);
    exit;
}

// Mulai Transaksi Database (agar atomik)
mysqli_begin_transaction($koneksi);

try {
    // 1. Simpan Header Transaksi
    $stmt_header = $koneksi->prepare("INSERT INTO transaksi (tanggal, jenis_transaksi, nomor_bukti, deskripsi) VALUES (?, ?, ?, ?)");
    
    // "s" = string (untuk tanggal, jenis, bukti, deskripsi)
    $stmt_header->bind_param("ssss", $tanggal, $jenis, $bukti, $deskripsi);
    
    if (!$stmt_header->execute()) {
        throw new Exception("Gagal menyimpan header transaksi: " . $stmt_header->error);
    }
    
    $transaksi_id = $koneksi->insert_id; // Ambil ID terakhir dari header

    // 2. Simpan Detail Jurnal
    $stmt_detail = $koneksi->prepare("INSERT INTO jurnal_detail (transaksi_id, akun_coa, debit, kredit) VALUES (?, ?, ?, ?)");
    
    foreach ($details as $row) {
        $akun  = $row['akun'];
        $debit = $row['debit'];
        $kredit = $row['kredit'];

        // "isdd" = integer, string, double, double
        $stmt_detail->bind_param("isdd", $transaksi_id, $akun, $debit, $kredit);
        
        if (!$stmt_detail->execute()) {
            throw new Exception("Gagal menyimpan detail jurnal: " . $stmt_detail->error);
        }
    }

    // Commit jika semua proses di atas sukses
    mysqli_commit($koneksi);
    echo json_encode(['status' => 'success', 'message' => 'Transaksi berhasil disimpan.']);

} catch (Exception $e) {
    // Rollback jika terjadi error
    mysqli_rollback($koneksi);
    
    // Tambahkan log atau detail error yang lebih spesifik jika perlu
    echo json_encode(['status' => 'error', 'message' => "Kesalahan Sistem: " . $e->getMessage()]);
}

// Tutup koneksi
mysqli_close($koneksi);
?>