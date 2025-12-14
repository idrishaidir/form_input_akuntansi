<?php
header('Content-Type: application/json');
// Matikan error display agar tidak merusak JSON, tapi log error tetap jalan
ini_set('display_errors', 0);
error_reporting(E_ALL);

include 'config/koneksi.php';

try {
    // 1. Ambil Data
    $tanggal   = $_POST['tanggal'] ?? null;
    $jenis     = $_POST['jenis'] ?? null;
    $bukti     = $_POST['bukti'] ?? null;
    $deskripsi = $_POST['deskripsi'] ?? null;
    $details   = isset($_POST['details']) ? json_decode($_POST['details'], true) : [];

    if (empty($tanggal) || empty($jenis) || empty($bukti) || empty($details)) {
        throw new Exception("Data tidak lengkap. Pastikan detail jurnal terisi.");
    }

    // 2. Upload File (Opsional)
    $nama_file_db = null;
    if (isset($_FILES['file_bukti']) && $_FILES['file_bukti']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        // Coba buat folder, jika gagal berikan error jelas
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
             throw new Exception("Gagal membuat folder 'uploads/'. Cek permission.");
        }

        $fileTmp   = $_FILES['file_bukti']['tmp_name'];
        $fileName  = $_FILES['file_bukti']['name'];
        $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array($fileExt, $allowed)) {
            throw new Exception("Format file salah. Hanya JPG, PNG, PDF.");
        }

        $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
        if (!move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
            throw new Exception("Gagal memindahkan file upload.");
        }
        $nama_file_db = $newFileName;
    }

    // 3. Simpan ke Database
    mysqli_begin_transaction($koneksi);

    // Persiapkan Query Header
    $sqlHeader = "INSERT INTO transaksi (tanggal, jenis_transaksi, nomor_bukti, deskripsi, file_bukti) VALUES (?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sqlHeader);

    // Cek jika query gagal disiapkan (Biasanya karena nama kolom salah/kurang)
    if (!$stmt) {
        throw new Exception("Gagal menyiapkan query transaksi: " . $koneksi->error);
    }

    $stmt->bind_param("sssss", $tanggal, $jenis, $bukti, $deskripsi, $nama_file_db);
    
    if (!$stmt->execute()) {
        throw new Exception("Eksekusi query gagal: " . $stmt->error);
    }
    
    $transaksi_id = $koneksi->insert_id;

    // Persiapkan Query Detail
    $sqlDetail = "INSERT INTO jurnal_detail (transaksi_id, akun_coa, debit, kredit) VALUES (?, ?, ?, ?)";
    $stmtDetail = $koneksi->prepare($sqlDetail);

    if (!$stmtDetail) {
         throw new Exception("Gagal menyiapkan query detail: " . $koneksi->error);
    }

    foreach ($details as $row) {
        $stmtDetail->bind_param("isdd", $transaksi_id, $row['akun'], $row['debit'], $row['kredit']);
        if (!$stmtDetail->execute()) {
            throw new Exception("Gagal menyimpan detail akun: " . $row['akun']);
        }
    }

    mysqli_commit($koneksi);
    echo json_encode(['status' => 'success', 'message' => 'Transaksi berhasil disimpan.']);

} catch (Throwable $e) { 
    // Menggunakan 'Throwable' agar bisa menangkap Fatal Error (seperti error SQL bind)
    if (isset($koneksi)) mysqli_rollback($koneksi);
    // http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>