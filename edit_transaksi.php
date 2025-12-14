<?php
require_once 'config/koneksi.php';


if (!isset($_GET['id'])) {
    header("Location: histori.php");
    exit;
}

$id = intval($_GET['id']);


$qHeader = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = $id");
$data = mysqli_fetch_assoc($qHeader);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}


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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8FAFC; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .drag-active { border-color: #3B82F6 !important; background-color: #EFF6FF !important; }
    </style>
</head>
<body class="text-slate-800 pb-32">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center text-slate-800 font-bold text-lg">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                Edit Transaksi
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8 space-y-6">
        
        <input type="hidden" id="trxId" value="<?php echo $data['id']; ?>">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <section class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-regular fa-id-card"></i> Informasi Utama
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-slate-700">Tanggal Transaksi</label>
                            <input type="date" id="trxDate" value="<?php echo $data['tanggal']; ?>" class="block w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-slate-700">Jenis Transaksi</label>
                            <div class="relative">
                                <select id="trxType" class="block w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 text-sm appearance-none cursor-pointer">
                                    <option value="buy" <?php echo $data['jenis_transaksi'] == 'buy' ? 'selected' : ''; ?>>Pembelian</option>
                                    <option value="sell" <?php echo $data['jenis_transaksi'] == 'sell' ? 'selected' : ''; ?>>Penjualan</option>
                                    <option value="expense" <?php echo $data['jenis_transaksi'] == 'expense' ? 'selected' : ''; ?>>Pengeluaran Biaya</option>
                                    <option value="income" <?php echo $data['jenis_transaksi'] == 'income' ? 'selected' : ''; ?>>Pemasukan Lain</option>
                                    <option value="journal" <?php echo $data['jenis_transaksi'] == 'journal' ? 'selected' : ''; ?>>Jurnal Umum</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700">Nomor Bukti</label>
                            <input type="text" id="trxNomorBukti" value="<?php echo $data['nomor_bukti']; ?>" class="block w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 text-sm font-mono tracking-wide">
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                            <textarea id="trxDeskripsi" rows="3" class="block w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 text-sm resize-none" placeholder="Keterangan transaksi..."><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                        </div>
                    </div>
                </section>
            </div>

            <div class="lg:col-span-1">
                <section class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-full flex flex-col">
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-paperclip"></i> Bukti Transaksi
                    </h3>
                    
                    <?php if (!empty($data['file_bukti'])): ?>
                        <div id="oldFileSection" class="mb-4">
                            <p class="text-xs font-semibold text-slate-500 mb-2">File Tersimpan Saat Ini:</p>
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100 group hover:border-blue-300 transition">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded flex flex-shrink-0 items-center justify-center">
                                        <?php 
                                            $ext = strtolower(pathinfo($data['file_bukti'], PATHINFO_EXTENSION));
                                            echo ($ext == 'pdf') ? '<i class="fa-regular fa-file-pdf"></i>' : '<i class="fa-regular fa-image"></i>';
                                        ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-slate-700 truncate"><?php echo $data['file_bukti']; ?></p>
                                        <a href="uploads/<?php echo $data['file_bukti']; ?>" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat File Asli</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="flex-1">
                        <p class="text-xs font-semibold text-slate-500 mb-2">
                            <?php echo !empty($data['file_bukti']) ? 'Ganti dengan File Baru (Opsional):' : 'Upload File Bukti:'; ?>
                        </p>
                        
                        <div id="dropZone" class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-slate-50 transition-all cursor-pointer h-40 flex flex-col items-center justify-center relative">
                            <input type="file" id="fileInput" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                            
                            <div id="dropZoneContent">
                                <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                </div>
                                <p class="text-sm font-medium text-slate-600">Klik atau Drag File</p>
                                <p class="text-xs text-slate-400 mt-1">PDF/JPG (Max 5MB)</p>
                            </div>

                            <div id="newFilePreview" class="hidden absolute inset-0 bg-white bg-opacity-95 flex-col items-center justify-center w-full h-full rounded-xl z-10">
                                <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-2">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <p id="newFileName" class="text-sm font-bold text-slate-700 px-4 truncate w-full text-center">nama_file.jpg</p>
                                <p class="text-xs text-slate-500 mb-3">Siap diupload</p>
                                <button type="button" onclick="removeNewFile(event)" class="text-xs bg-red-50 text-red-600 px-3 py-1 rounded-full hover:bg-red-100 font-medium transition">
                                    Batal / Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
        </div>

        <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-20">
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-blue-500"></i> Detail Jurnal
                </h2>
                <button onclick="addRow()" class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition border border-blue-200 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Baris
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-5/12">Akun (COA)</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-3/12">Debit (Rp)</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-500 uppercase tracking-wider w-3/12">Kredit (Rp)</th>
                            <th class="px-6 py-3 text-center w-1/12"></th>
                        </tr>
                    </thead>
                    <tbody id="journalBody" class="bg-white divide-y divide-slate-200">
                        </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td class="px-6 py-3 text-right text-sm font-bold text-slate-600">Total</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-slate-800 font-mono" id="totalDebitDisplay">0</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-slate-800 font-mono" id="totalCreditDisplay">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>

        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-[0_-5px_15px_-5px_rgba(0,0,0,0.1)] z-40">
            <div class="max-w-6xl mx-auto px-4 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div id="balanceIconBox" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 transition-colors duration-300">
                        <i id="balanceIcon" class="fa-solid fa-scale-unbalanced"></i>
                    </div>
                    <div>
                        <div id="balanceLabel" class="text-sm font-bold text-slate-500">Mengecek...</div>
                        <div id="balanceSub" class="text-xs text-slate-400">Selisih: <span class="font-mono" id="diffDisplay">0</span></div>
                    </div>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="histori.php" class="text-sm font-medium text-slate-500 hover:text-red-600 transition flex items-center gap-2 bg-slate-50 hover:bg-red-50 px-3 py-1.5 rounded-lg border border-transparent hover:border-red-200">
                        <i class="fa-solid fa-times"></i> <span class="hidden sm:inline">Batal</span>
                    </a>
                    <button id="btnUpdate" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-sm font-bold shadow-md hover:shadow-lg transition-all disabled:bg-slate-300 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2" disabled>
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

    </main>

    <script>
        
        const initialDetails = <?php echo json_encode($details); ?>;
        let uploadedFile = null;

        document.addEventListener('DOMContentLoaded', () => {
            
            if(initialDetails.length > 0) {
                initialDetails.forEach(d => addRow(d.akun_coa, d.debit, d.kredit));
            } else {
                addRow(); addRow(); 
            }
            calculateTotal();
        });

        
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const newFilePreview = document.getElementById('newFilePreview');
        const newFileName = document.getElementById('newFileName');

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            handleFile(this.files[0]);
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-active');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('drag-active');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-active');
            handleFile(e.dataTransfer.files[0]);
        });

        function handleFile(file) {
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) {
                alert("File terlalu besar (Max 5MB).");
                return;
            }
            // Validasi tipe file sederhana
            const validTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                alert("Hanya file PDF, JPG, atau PNG yang diperbolehkan.");
                return;
            }

            uploadedFile = file;
            
            // Update UI
            newFilePreview.classList.remove('hidden');
            newFilePreview.classList.add('flex');
            newFileName.innerText = file.name;
        }

        function removeNewFile(e) {
            e.stopPropagation();
            uploadedFile = null;
            fileInput.value = '';
            newFilePreview.classList.add('hidden');
            newFilePreview.classList.remove('flex');
        }

        
        function addRow(akun = '', debit = 0, kredit = 0) {
            const tbody = document.getElementById('journalBody');
            const row = document.createElement('tr');
            row.className = "group hover:bg-slate-50 transition";
            
            const options = `
                <option value="101 - Kas Besar">101 - Kas Besar</option>
                <option value="102 - Bank BCA">102 - Bank BCA</option>
                <option value="401 - Pendapatan Jasa">401 - Pendapatan Jasa</option>
                <option value="501 - Beban Sewa">501 - Beban Sewa</option>
            `;

            row.innerHTML = `
                <td class="px-6 py-3">
                    <select class="account-select w-full px-3 py-2 bg-white border border-slate-300 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm transition">
                        ${options}
                    </select>
                </td>
                <td class="px-6 py-3">
                    <input type="number" value="${debit}" oninput="calculateTotal()" class="input-debit w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:ring-1 focus:ring-blue-500 text-sm font-mono placeholder-slate-400 transition" placeholder="0">
                </td>
                <td class="px-6 py-3">
                    <input type="number" value="${kredit}" oninput="calculateTotal()" class="input-credit w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:ring-1 focus:ring-blue-500 text-sm font-mono placeholder-slate-400 transition" placeholder="0">
                </td>
                <td class="px-6 py-3 text-center">
                    <button onclick="deleteRow(this)" class="text-slate-400 hover:text-red-500 p-2 rounded-full hover:bg-red-50 transition" title="Hapus Baris">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);

            if(akun) row.querySelector('.account-select').value = akun;
        }

        function deleteRow(btn) {
            const row = btn.closest('tr');
            if(document.querySelectorAll('#journalBody tr').length > 1) {
                row.remove();
                calculateTotal();
            } else {
                alert("Minimal sisakan satu baris.");
            }
        }

        function calculateTotal() {
            let debit = 0, kredit = 0;
            document.querySelectorAll('.input-debit').forEach(i => debit += parseFloat(i.value) || 0);
            document.querySelectorAll('.input-credit').forEach(i => kredit += parseFloat(i.value) || 0);

            const fmt = new Intl.NumberFormat('id-ID');
            document.getElementById('totalDebitDisplay').innerText = fmt.format(debit);
            document.getElementById('totalCreditDisplay').innerText = fmt.format(kredit);

            const diff = Math.abs(debit - kredit);
            const isBalanced = diff < 1 && debit > 0;
            const isDiisi = diff == 0 && debit == 0;
            
            document.getElementById('diffDisplay').innerText = fmt.format(diff);
            
            const statusBox = document.getElementById('balanceIconBox');
            const icon = document.getElementById('balanceIcon');
            const label = document.getElementById('balanceLabel');
            const sub = document.getElementById('balanceSub');
            const btn = document.getElementById('btnSave');

            const balanceRowTable = document.getElementById('balanceRowTable');
            const balanceStatusTable = document.getElementById('balanceStatusTable');

            if(isBalanced) {
                statusBox.className = "w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 transition-colors duration-300 shadow-sm";
                icon.className = "fa-solid fa-check";
                label.innerText = "BALANCE (SEIMBANG)";
                label.className = "text-sm font-bold text-green-700";
                sub.className = "text-xs text-green-600";
                btn.disabled = false;

                
                balanceRowTable.className = "bg-green-50 transition-colors duration-300";
                balanceStatusTable.className = "inline-flex items-center justify-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 transition-all shadow-sm border border-green-200";
                balanceStatusTable.innerHTML = '<i class="fa-solid fa-check-circle"></i> SEIMBANG';

            } else if(isDiisi) {
                
                statusBox.className = "w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 transition-colors duration-300";
                icon.className = "fa-solid fa-circle-question";
                label.innerText = "BELUM DIISI";
                label.className = "text-sm font-bold text-slate-500";
                sub.className = "text-xs text-slate-400";
                btn.disabled = true;

                
                balanceRowTable.className = "bg-slate-100 transition-colors duration-300";
                balanceStatusTable.className = "inline-flex items-center justify-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-500 transition-all";
                balanceStatusTable.innerHTML = `<i class="fa-solid fa-circle-question"></i> Belum DIisi`;

            }
            else {
                
                statusBox.className = "w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 transition-colors duration-300 animate-pulse";
                icon.className = "fa-solid fa-scale-unbalanced";
                label.innerText = "TIDAK SEIMBANG";
                label.className = "text-sm font-bold text-red-700";
                sub.className = "text-xs text-red-600 font-semibold";
                btn.disabled = true;

                
                balanceRowTable.className = "bg-red-50 transition-colors duration-300";
                balanceStatusTable.className = "inline-flex items-center justify-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 transition-all border border-red-200";
                balanceStatusTable.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> SELISIH: ${fmt.format(diff)}`;
            }
        }

        document.getElementById('btnUpdate').addEventListener('click', function() {
            if(this.disabled) return;
            
            const btn = this;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            const details = [];
            document.querySelectorAll('#journalBody tr').forEach(row => {
                const d = parseFloat(row.querySelector('.input-debit').value) || 0;
                const k = parseFloat(row.querySelector('.input-credit').value) || 0;
                if(d > 0 || k > 0) {
                    details.push({
                        akun: row.querySelector('.account-select').value,
                        debit: d,
                        kredit: k
                    });
                }
            });

            const formData = new FormData();
            formData.append('id', document.getElementById('trxId').value);
            formData.append('tanggal', document.getElementById('trxDate').value);
            formData.append('jenis', document.getElementById('trxType').value);
            formData.append('bukti', document.getElementById('trxNomorBukti').value);
            formData.append('deskripsi', document.getElementById('trxDeskripsi').value);
            formData.append('details', JSON.stringify(details));

            // Jika ada file baru di dropzone, masukkan
            if(uploadedFile) {
                formData.append('file_bukti', uploadedFile);
            }

            fetch('update_transaksi.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    // Beri feedback sukses sebelum redirect
                    btn.className = "w-full sm:w-auto bg-green-600 text-white px-8 py-3 rounded-lg text-sm font-bold shadow-md flex items-center justify-center gap-2";
                    btn.innerHTML = '<i class="fa-solid fa-check-circle"></i> Berhasil Disimpan!';
                    setTimeout(() => {
                        window.location.href = 'histori.php';
                    }, 1000);
                } else {
                    throw new Error(res.message);
                }
            })
            .catch(err => {
                alert('Gagal: ' + err.message);
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        });
    </script>
</body>
</html>