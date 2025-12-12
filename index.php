<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance App</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
        
        /* Custom Scrollbar untuk tabel di mobile */
        .table-container::-webkit-scrollbar { height: 6px; }
        .table-container::-webkit-scrollbar-track { background: #f1f1f1; }
        .table-container::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .table-container::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        /* Hide number input arrows */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        
        /* Drag & Drop Active State */
        .drag-active { border-color: #2563EB !important; background-color: #EFF6FF !important; }
    </style>
</head>
<body class="text-slate-800 pb-24">

    <!-- Navbar Sederhana -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center text-blue-600">
                        <i class="fa-solid fa-calculator text-2xl mr-2"></i>
                        <span class="font-bold text-xl tracking-tight">FinanceApp</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Input Transaksi Baru</h1>
                <p class="text-sm text-slate-500 mt-1">Buat jurnal akuntansi manual untuk pembukuan.</p>
            </div>
            <!-- Status Badge (Optional) -->
            <a href="histori.php" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i> Histori Transaksi
            </a>
        </div>

        <!-- SECTION A: Header Form (Informasi Transaksi) -->
        <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-regular fa-file-lines text-blue-500"></i> Informasi Umum
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kiri -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Transaksi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-calendar text-slate-400"></i>
                            </div>
                            <input type="date" class="block w-full pl-10 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition shadow-sm" id="trxDate">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Transaksi</label>
                        <div class="relative">
                            <select id="trxType" name="jenis_transaksi" class="block w-full pl-3 pr-10 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm appearance-none shadow-sm cursor-pointer hover:bg-slate-50 transition">
                                <option value="" disabled selected>Pilih jenis...</option>
                                <option value="buy">Pembelian</option>
                                <option value="sell">Penjualan</option>
                                <option value="expense">Pengeluaran Biaya</option>
                                <option value="income">Pemasukan Lain</option>
                                <option value="journal">Jurnal Umum</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-slate-500">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanan -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Bukti</label>
                        <div class="relative">
                            <input type="text" id="trxNomorBukti" name="nomor_bukti" placeholder="Cth: INV/2023/001" class="block w-full pl-3 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <textarea rows="3" id="trxDeskripsi"name="deskripsi" placeholder="Jelaskan detail transaksi..." class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm resize-none"></textarea>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION B: Detail Jurnal -->
        <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-blue-500"></i> Detail Jurnal
                </h2>
                <button onclick="addRow()" class="text-xs font-medium text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                    <i class="fa-solid fa-plus"></i> Tambah Baris
                </button>
            </div>
            
            <!-- Table Container (Scrollable on Mobile) -->
            <div class="overflow-x-auto table-container">
                <table class="min-w-full divide-y divide-slate-200" id="journalTable">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-5/12">Akun (COA)</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-3/12">Debit (Rp)</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-3/12">Kredit (Rp)</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-1/12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200" id="journalBody">
                        </tbody>
                        <!-- Baris 1 -->
                        <tr class="group hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select name="akun[]" class="account-select block w-full px-3 py-2 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option>101 - Kas Besar</option>
                                    <option>102 - Bank BCA</option>
                                    <option>401 - Pendapatan Jasa</option>
                                    <option>501 - Beban Sewa</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input name="debit[]" type="number" oninput="calculateTotal()" class="input-debit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono placeholder-slate-400" placeholder="0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input name="kredit[]" type="number" oninput="calculateTotal()" class="input-credit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono placeholder-slate-400" placeholder="0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="deleteRow(this)" class="text-slate-400 hover:text-red-500 transition p-2 rounded-full hover:bg-red-50" title="Hapus Baris">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <!-- Footer Tabel (Total) -->
                    <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                        <tr>
                            <td class="px-6 py-4 text-right text-sm font-bold text-slate-600">Total</td>
                            <td class="px-6 py-4 text-right">
                                <div id="totalDebitDisplay" class="text-sm font-bold text-slate-800 font-mono">0</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div id="totalCreditDisplay" class="text-sm font-bold text-slate-800 font-mono">0</div>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Indikator Keseimbangan (Balance Indicator) -->
            <div id="balanceIndicator" class="bg-red-50 border-t border-red-100 p-4 flex flex-col sm:flex-row items-center justify-between gap-4 transition-colors duration-300">
                <div class="flex items-center gap-3">
                    <div id="balanceIconBox" class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 transition-colors duration-300">
                        <i id="balanceIcon" class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <p id="balanceTitle" class="text-sm font-bold text-red-800 transition-colors duration-300">TIDAK BALANCE</p>
                        <p id="balanceSubtitle" class="text-xs text-red-600 mt-0.5 transition-colors duration-300">Selisih: <span id="diffDisplay" class="font-mono font-medium">0</span></p>
                    </div>
                </div>
                <div class="text-xs text-slate-500 italic hidden sm:block">
                    Pastikan Total Debit = Total Kredit
                </div>
            </div>
        </section>

        <!-- SECTION C: Upload Bukti -->
        <section class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-semibold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-paperclip text-blue-500"></i> Bukti Transaksi
                </h2>
            </div>
            <div class="p-6">
                <div id="dropZone" class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors cursor-pointer group">
                    <input type="file" id="fileInput" class="hidden" multiple>
                    <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-700">
                        <span class="text-blue-600 hover:underline">Klik untuk upload</span> atau drag & drop file
                    </p>
                    <p class="text-xs text-slate-400 mt-2">PNG, JPG, atau PDF (Maks. 5MB)</p>
                </div>

                <!-- Preview Area (Hidden by default) -->
                <div id="filePreview" class="mt-4 hidden space-y-2">
                    <!-- Javascript will populate this -->
                </div>
            </div>
        </section>

    </main>

    <!-- SECTION D: Sticky Footer Action -->
    <footer class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-40">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <button onclick="resetForm()" class="px-5 py-2.5 rounded-lg text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 transition border border-transparent hover:border-slate-300">
                Reset
            </button>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-slate-500">Status Data</p>
                    <p class="text-sm font-bold text-slate-700">Belum Disimpan</p>
                </div>
                <button id="btnSave" class="px-8 py-2.5 rounded-lg text-sm font-bold text-white bg-slate-400 cursor-not-allowed shadow-sm transition-all" disabled>
                    <i class="fa-solid fa-check mr-2"></i> Simpan Transaksi
                </button>
            </div>
        </div>
    </footer>
    <script>
    // Variabel Global untuk menyimpan file
    let uploadedFile = null;

    document.addEventListener('DOMContentLoaded', calculateTotal);

    // --- LOGIKA DRAG & DROP & UPLOAD ---
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const filePreview = document.getElementById('filePreview');

    // Klik area -> Buka file explorer
    dropZone.addEventListener('click', () => fileInput.click());

    // Saat file dipilih dari explorer
    fileInput.addEventListener('change', function() {
        handleFile(this.files[0]);
    });

    // Efek saat file di-drag masuk
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-active');
    });

    // Efek saat file di-drag keluar
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-active');
    });

    // Saat file dilepas (dropped)
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-active');
        handleFile(e.dataTransfer.files[0]);
    });

    // Fungsi proses file
    function handleFile(file) {
        if (!file) return;

        // Validasi Ukuran (Max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert("Ukuran file terlalu besar! Maksimal 5MB.");
            return;
        }

        // Simpan ke variabel global
        uploadedFile = file;

        // Tampilkan Preview
        filePreview.classList.remove('hidden');
        filePreview.innerHTML = `
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded flex items-center justify-center">
                        <i class="fa-solid fa-file"></i>
                    </div>
                    <div class="text-sm">
                        <p class="font-medium text-slate-700 truncate max-w-[200px]">${file.name}</p>
                        <p class="text-xs text-slate-500">${(file.size/1024).toFixed(1)} KB</p>
                    </div>
                </div>
                <button type="button" onclick="removeFile(event)" class="text-slate-400 hover:text-red-500 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        `;
    }

    // Hapus file dari preview
    function removeFile(e) {
        e.stopPropagation(); // Biar tidak memicu klik dropZone
        uploadedFile = null;
        fileInput.value = ''; // Reset input asli
        filePreview.innerHTML = '';
        filePreview.classList.add('hidden');
    }

    // --- LOGIKA TABEL & SIMPAN ---

    function addRow() {
        const tbody = document.getElementById('journalBody');
        const row = document.createElement('tr');
        row.className = "group hover:bg-slate-50 transition";
        row.innerHTML = `
            <td class="px-6 py-4">
                <select class="account-select block w-full px-3 py-2 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="101 - Kas Besar">101 - Kas Besar</option>
                    <option value="102 - Bank BCA">102 - Bank BCA</option>
                    <option value="401 - Pendapatan Jasa">401 - Pendapatan Jasa</option>
                    <option value="501 - Beban Sewa">501 - Beban Sewa</option>
                </select>
            </td>
            <td class="px-6 py-4"><input type="number" oninput="calculateTotal()" class="input-debit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white text-sm font-mono" placeholder="0"></td>
            <td class="px-6 py-4"><input type="number" oninput="calculateTotal()" class="input-credit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white text-sm font-mono" placeholder="0"></td>
            <td class="px-6 py-4 text-center"><button onclick="deleteRow(this)" class="text-slate-400 hover:text-red-500"><i class="fa-regular fa-trash-can"></i></button></td>
        `;
        tbody.appendChild(row);
    }

    function deleteRow(btn) {
        if(document.querySelectorAll('#journalBody tr').length > 1) {
            btn.closest('tr').remove();
            calculateTotal();
        } else {
            alert("Minimal satu baris jurnal harus ada.");
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
        const isBalanced = diff === 0 && debit > 0;
        
        document.getElementById('diffDisplay').innerText = fmt.format(diff);
        
        const indicator = document.getElementById('balanceIndicator');
        const iconBox = document.getElementById('balanceIconBox');
        const title = document.getElementById('balanceTitle');
        const btn = document.getElementById('btnSave');

        if(isBalanced) {
            indicator.className = "bg-green-50 border-t border-green-100 p-4 flex flex-col sm:flex-row items-center justify-between gap-4";
            iconBox.className = "w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600";
            iconBox.innerHTML = '<i class="fa-solid fa-check"></i>';
            title.innerText = "BALANCE";
            title.className = "text-sm font-bold text-green-800";
            btn.disabled = false;
            btn.className = "px-8 py-2.5 rounded-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 cursor-pointer transition shadow-md";
        } else {
            indicator.className = "bg-red-50 border-t border-red-100 p-4 flex flex-col sm:flex-row items-center justify-between gap-4";
            iconBox.className = "w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600";
            iconBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
            title.innerText = "TIDAK BALANCE";
            title.className = "text-sm font-bold text-red-800";
            btn.disabled = true;
            btn.className = "px-8 py-2.5 rounded-lg text-sm font-bold text-white bg-slate-400 cursor-not-allowed transition";
        }
    }

    function resetForm() {
        document.getElementById('trxDate').value = '';
        document.getElementById('trxType').value = '';
        document.getElementById('trxNomorBukti').value = '';
        if(document.getElementById('trxDeskripsi')) document.getElementById('trxDeskripsi').value = '';
        
        const tbody = document.getElementById('journalBody');
        tbody.innerHTML = '';
        addRow();

        // Reset File
        removeFile({ stopPropagation: () => {} });

        calculateTotal();
    }

    // --- PROSES SIMPAN (DENGAN FORMDATA) ---
    document.getElementById('btnSave').addEventListener('click', function() {
        if(this.disabled) return;

        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Menyimpan...';
        this.disabled = true;

        // 1. Siapkan FormData (Bukan JSON biasa)
        const formData = new FormData();
        formData.append('tanggal', document.getElementById('trxDate').value);
        formData.append('jenis', document.getElementById('trxType').value);
        formData.append('bukti', document.getElementById('trxNomorBukti').value);
        
        if(document.getElementById('trxDeskripsi')) {
             formData.append('deskripsi', document.getElementById('trxDeskripsi').value);
        }

        // 2. Masukkan File (Jika ada)
        if (uploadedFile) {
            formData.append('file_bukti', uploadedFile);
        }

        // 3. Masukkan Detail Jurnal (Sebagai string JSON di dalam FormData)
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
        formData.append('details', JSON.stringify(details));

        // 4. Kirim Fetch
        fetch('simpan_transaksi.php', {
            method: 'POST',
            body: formData // Header Content-Type otomatis diatur oleh browser
        })
        .then(response => {
            if (!response.ok) throw new Error("HTTP Error " + response.status);
            return response.json();
        })
        .then(result => {
            if(result.status === 'success') {
                alert('✅ Transaksi Berhasil Disimpan!');
                resetForm(); // Panggil reset
            } else {
                throw new Error(result.message || "Gagal menyimpan.");
            }
        })
        .catch(error => {
            alert('❌ ERROR: ' + error.message);
        })
        .finally(() => {
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });
    </script>
</body>
</html>