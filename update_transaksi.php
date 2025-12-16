<?php
header('Content-Type: application/json');
// Matikan error HTML agar tidak merusak JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

include 'config/koneksi.php';

try {
    // 1. Ambil Data dari $_POST (bukan json input stream lagi)
    if (!isset($_POST['id'])) {
        throw new Exception("Data tidak valid (ID tidak ditemukan). Pastikan file ini sudah diperbarui.");
    }

    $id        = $_POST['id'];
    $tanggal   = $_POST['tanggal'];
    $jenis     = $_POST['jenis'];
    $bukti     = $_POST['bukti'];
    $deskripsi = $_POST['deskripsi'];
    
    // Decode detail jurnal yang dikirim sebagai string JSON di dalam FormData
    $details   = isset($_POST['details']) ? json_decode($_POST['details'], true) : [];

    if (empty($details)) throw new Exception("Detail jurnal kosong.");

    mysqli_begin_transaction($koneksi);

    // 2. LOGIKA UPLOAD FILE BARU (Jika ada)
    $sqlFileUpdate = ""; 
    $paramTypes = "ssssi"; // s=string, i=integer
    $params = [$tanggal, $jenis, $bukti, $deskripsi, $id];

    // Cek apakah user mengupload file baru
    if (isset($_FILES['file_bukti']) && $_FILES['file_bukti']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileTmp   = $_FILES['file_bukti']['tmp_name'];
        $fileName  = $_FILES['file_bukti']['name'];
        $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($fileExt, $allowed)) throw new Exception("Format file salah (Hanya JPG, PNG, PDF).");

        $newFileName = time() . '_update_' . uniqid() . '.' . $fileExt;
        
        if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
            // HAPUS FILE LAMA (Opsional, agar server tidak penuh)
            $qOld = mysqli_query($koneksi, "SELECT file_bukti FROM transaksi WHERE id = $id");
            $dOld = mysqli_fetch_assoc($qOld);
            if ($dOld && !empty($dOld['file_bukti'])) {
                $oldPath = $uploadDir . $dOld['file_bukti'];
                if (file_exists($oldPath)) unlink($oldPath);
            }

            // Tambahkan kolom file_bukti ke query update
            $sqlFileUpdate = ", file_bukti=?";
            
            // Susun ulang parameter karena ada tambahan file
            // Urutan: tanggal, jenis, bukti, deskripsi, file_baru, id
            $paramTypes = "sssssi"; 
            $params = [$tanggal, $jenis, $bukti, $deskripsi, $newFileName, $id];
        }
    }

    // 3. Update Header Transaksi
    $sql = "UPDATE transaksi SET tanggal=?, jenis_transaksi=?, nomor_bukti=?, deskripsi=? $sqlFileUpdate WHERE id=?";
    $stmt = $koneksi->prepare($sql);
    
    // Bind Params dengan teknik unpacking (...)
    $stmt->bind_param($paramTypes, ...$params);
    
    if (!$stmt->execute()) throw new Exception("Gagal update header: " . $stmt->error);

    // 4. Update Detail (Hapus Lama -> Insert Baru)
    $koneksi->query("DELETE FROM jurnal_detail WHERE transaksi_id = $id");

    $stmtDetail = $koneksi->prepare("INSERT INTO jurnal_detail (transaksi_id, akun_coa, debit, kredit) VALUES (?, ?, ?, ?)");
    foreach ($details as $row) {
        $stmtDetail->bind_param("isdd", $id, $row['akun'], $row['debit'], $row['kredit']);
        if (!$stmtDetail->execute()) throw new Exception("Gagal simpan detail.");
    }

    mysqli_commit($koneksi);
    echo json_encode(['status' => 'success']);

} catch (Throwable $e) {
    if (isset($koneksi)) mysqli_rollback($koneksi);
    // Tetap kirim JSON error
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>