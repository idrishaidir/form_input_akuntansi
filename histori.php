<?php
// Masukkan koneksi database
require_once 'config/koneksi.php';

// --- LOGIKA HAPUS DATA ---
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Hapus detail dulu (jika database tidak set ON DELETE CASCADE)
    mysqli_query($koneksi, "DELETE FROM jurnal_detail WHERE transaksi_id = $id");
    
    // Hapus header transaksi
    $delete = mysqli_query($koneksi, "DELETE FROM transaksi WHERE id = $id");
    
    if ($delete) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='histori.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// --- QUERY AMBIL DATA ---
// Mengambil data transaksi beserta total debitnya (untuk ringkasan)
$query = "SELECT t.*, SUM(d.debit) as total_nilai, COUNT(d.id) as jumlah_baris 
          FROM transaksi t 
          LEFT JOIN jurnal_detail d ON t.id = d.transaksi_id 
          GROUP BY t.id 
          ORDER BY t.tanggal DESC, t.id DESC";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - FinanceApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
    </style>
</head>
<body class="text-slate-800">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <a href="index.php" class="flex items-center text-slate-500 hover:text-blue-600 transition">
                        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <div class="h-6 w-px bg-slate-300"></div>
                    <span class="font-bold text-xl tracking-tight text-blue-600">Riwayat Transaksi</span>
                </div>
                <div class="flex items-center">
                    <a href="index.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        <i class="fa-solid fa-plus mr-1"></i> Input Baru
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider">
                    <i class="fa-solid fa-clock-rotate-left text-blue-500 mr-2"></i> Data Jurnal
                </h2>
                <div class="relative hidden sm:block">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" placeholder="Cari bukti/deskripsi..." class="pl-8 pr-4 py-1.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Bukti</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total (Rp)</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-slate-50 transition group">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                        <?php echo date('d/m/Y', strtotime($row['tanggal'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-medium text-slate-600">
                                        <?php echo htmlspecialchars($row['nomor_bukti']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate" title="<?php echo htmlspecialchars($row['deskripsi']); ?>">
                                        <?php echo htmlspecialchars($row['deskripsi']); ?>
                                        <div class="text-xs text-slate-400 mt-0.5"><?php echo $row['jumlah_baris']; ?> Baris Jurnal</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php
                                            $badges = [
                                                'buy' => 'bg-orange-100 text-orange-700',
                                                'sell' => 'bg-green-100 text-green-700',
                                                'expense' => 'bg-red-100 text-red-700',
                                                'income' => 'bg-teal-100 text-teal-700',
                                                'journal' => 'bg-slate-100 text-slate-700'
                                            ];
                                            $bg = $badges[$row['jenis_transaksi']] ?? 'bg-gray-100 text-gray-700';
                                            $label = ucfirst($row['jenis_transaksi']);
                                        ?>
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium <?php echo $bg; ?>">
                                            <?php echo $label; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-800 font-mono">
                                        <?php echo number_format($row['total_nilai'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="edit_transaksi.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition" title="Edit Data">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </a>
                                            <a href="histori.php?aksi=hapus&id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi <?php echo $row['nomor_bukti']; ?>? Data tidak bisa dikembalikan.')" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition" title="Hapus Data">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fa-solid fa-inbox text-4xl mb-3 block opacity-50"></i>
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                <span class="text-xs text-slate-500">Menampilkan semua data</span>
                </div>
        </div>
    </main>

</body>
</html>