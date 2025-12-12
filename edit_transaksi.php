<?php
require_once 'config/koneksi.php';

// Cek ID
if (!isset($_GET['id'])) {
    header("Location: histori.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil Header
$qHeader = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = $id");
$data = mysqli_fetch_assoc($qHeader);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

// Ambil Detail
$qDetail = mysqli_query($koneksi, "SELECT * FROM jurnal_detail WHERE transaksi_id = $id");
$details = [];
while ($d = mysqli_fetch_assoc($qDetail)) {
    $details[] = $d;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - <?php echo $data['nomor_bukti']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Gaya sama seperti index.php */
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 pb-24">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center text-blue-600 font-bold text-xl">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Transaksi
            </div>
            <a href="histori.php" class="text-sm text-slate-500 hover:text-slate-800">
                <i class="fa-solid fa-times mr-1"></i> Batal
            </a>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-8 space-y-6">
        
        <input type="hidden" id="trxId" value="<?php echo $data['id']; ?>">

        <section class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                    <input type="date" id="trxDate" value="<?php echo $data['tanggal']; ?>" class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis</label>
                    <select id="trxType" class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="buy" <?php echo $data['jenis_transaksi'] == 'buy' ? 'selected' : ''; ?>>Pembelian</option>
                        <option value="sell" <?php echo $data['jenis_transaksi'] == 'sell' ? 'selected' : ''; ?>>Penjualan</option>
                        <option value="expense" <?php echo $data['jenis_transaksi'] == 'expense' ? 'selected' : ''; ?>>Pengeluaran</option>
                        <option value="income" <?php echo $data['jenis_transaksi'] == 'income' ? 'selected' : ''; ?>>Pemasukan</option>
                        <option value="journal" <?php echo $data['jenis_transaksi'] == 'journal' ? 'selected' : ''; ?>>Jurnal Umum</option>
                    </select>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Bukti</label>
                    <input type="text" id="trxNomorBukti" value="<?php echo $data['nomor_bukti']; ?>" class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                    <textarea id="trxDeskripsi" rows="3" class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-sm resize-none"><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-sm font-bold text-slate-700">Detail Jurnal</h2>
                <button onclick="addRow()" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded hover:bg-blue-100 font-medium">+ Tambah Baris</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-500 w-5/12">Akun</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-slate-500 w-3/12">Debit</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-slate-500 w-3/12">Kredit</th>
                            <th class="px-4 py-2 w-1/12"></th>
                        </tr>
                    </thead>
                    <tbody id="journalBody" class="bg-white divide-y divide-slate-200">
                        </tbody>
                </table>
            </div>
        </section>

        <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 z-40 flex justify-end gap-3 max-w-5xl mx-auto">
             <div class="mr-auto text-sm font-bold flex items-center gap-4">
                <span class="text-slate-500">Balance:</span>
                <span id="balanceStatus" class="text-red-600">TIDAK BALANCE</span>
            </div>
            <button id="btnUpdate" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-sm transition disabled:bg-slate-400 disabled:cursor-not-allowed">
                Simpan Perubahan
            </button>
        </div>

    </main>

    <script>
        // Data Awal dari PHP
        const initialDetails = <?php echo json_encode($details); ?>;

        document.addEventListener('DOMContentLoaded', () => {
            if(initialDetails.length > 0) {
                initialDetails.forEach(d => addRow(d.akun_coa, d.debit, d.kredit));
            } else {
                addRow();
            }
            calculateTotal();
        });

        function addRow(akun = '', debit = 0, kredit = 0) {
            const tbody = document.getElementById('journalBody');
            const row = document.createElement('tr');
            
            // Opsi Akun (Bisa disesuaikan dengan database)
            const options = `
                <option value="101 - Kas Besar">101 - Kas Besar</option>
                <option value="102 - Bank BCA">102 - Bank BCA</option>
                <option value="401 - Pendapatan Jasa">401 - Pendapatan Jasa</option>
                <option value="501 - Beban Sewa">501 - Beban Sewa</option>
            `;

            row.innerHTML = `
                <td class="px-4 py-2"><select class="account-select w-full border rounded px-2 py-1.5 text-sm">${options}</select></td>
                <td class="px-4 py-2"><input type="number" value="${debit}" oninput="calculateTotal()" class="input-debit w-full text-right border rounded px-2 py-1.5 text-sm font-mono"></td>
                <td class="px-4 py-2"><input type="number" value="${kredit}" oninput="calculateTotal()" class="input-credit w-full text-right border rounded px-2 py-1.5 text-sm font-mono"></td>
                <td class="px-4 py-2 text-center"><button onclick="this.closest('tr').remove(); calculateTotal();" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button></td>
            `;
            tbody.appendChild(row);

            // Set Selected Value
            if(akun) {
                row.querySelector('.account-select').value = akun;
            }
        }

        function calculateTotal() {
            let debit = 0, kredit = 0;
            document.querySelectorAll('.input-debit').forEach(i => debit += parseFloat(i.value) || 0);
            document.querySelectorAll('.input-credit').forEach(i => kredit += parseFloat(i.value) || 0);

            const isBalanced = Math.abs(debit - kredit) < 1; // Toleransi koma
            const status = document.getElementById('balanceStatus');
            const btn = document.getElementById('btnUpdate');

            if(isBalanced && debit > 0) {
                status.innerText = "BALANCE";
                status.className = "text-green-600";
                btn.disabled = false;
            } else {
                status.innerText = `SELISIH: ${Math.abs(debit - kredit)}`;
                status.className = "text-red-600";
                btn.disabled = true;
            }
        }

        document.getElementById('btnUpdate').addEventListener('click', function() {
            const data = {
                id: document.getElementById('trxId').value, // ID untuk update
                tanggal: document.getElementById('trxDate').value,
                jenis: document.getElementById('trxType').value,
                bukti: document.getElementById('trxNomorBukti').value,
                deskripsi: document.getElementById('trxDeskripsi').value,
                details: []
            };

            document.querySelectorAll('#journalBody tr').forEach(row => {
                const d = parseFloat(row.querySelector('.input-debit').value) || 0;
                const k = parseFloat(row.querySelector('.input-credit').value) || 0;
                if(d > 0 || k > 0) {
                    data.details.push({
                        akun: row.querySelector('.account-select').value,
                        debit: d,
                        kredit: k
                    });
                }
            });

            // Kirim ke update_transaksi.php (Perlu dibuat file backendnya atau gabung logika)
            // Untuk simpelnya, kita pakai fetch ke file baru 'update_transaksi.php'
            fetch('update_transaksi.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    alert('Data berhasil diperbarui!');
                    window.location.href = 'histori.php';
                } else {
                    alert('Gagal: ' + res.message);
                }
            })
            .catch(err => alert('Error sistem: ' + err));
        });
    </script>
</body>
</html>