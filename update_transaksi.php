<?php
header('Content-Type: application/json');
include 'config/koneksi.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['id']) || !isset($data['details'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
    exit;
}

$id = $data['id'];
$tanggal = $data['tanggal'];
$jenis = $data['jenis'];
$bukti = $data['bukti'];
$deskripsi = $data['deskripsi'];

mysqli_begin_transaction($koneksi);

try {
    // 1. Update Header
    $stmt = $koneksi->prepare("UPDATE transaksi SET tanggal=?, jenis_transaksi=?, nomor_bukti=?, deskripsi=? WHERE id=?");
    $stmt->bind_param("ssssi", $tanggal, $jenis, $bukti, $deskripsi, $id);
    $stmt->execute();

    // 2. Hapus Detail Lama
    $koneksi->query("DELETE FROM jurnal_detail WHERE transaksi_id = $id");

    // 3. Insert Detail Baru
    $stmtDetail = $koneksi->prepare("INSERT INTO jurnal_detail (transaksi_id, akun_coa, debit, kredit) VALUES (?, ?, ?, ?)");
    foreach ($data['details'] as $row) {
        $stmtDetail->bind_param("isdd", $id, $row['akun'], $row['debit'], $row['kredit']);
        $stmtDetail->execute();
    }

    mysqli_commit($koneksi);
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>