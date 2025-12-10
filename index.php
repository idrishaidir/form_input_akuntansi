<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Transaksi Akuntansi</title>
    
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
                <div class="flex items-center">
                    <div class="flex items-center gap-2 text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full">
                        <i class="fa-solid fa-user-circle"></i>
                        <span>Admin Keuangan</span>
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
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i> Mode Draft
            </span>
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
                            <select class="block w-full pl-3 pr-10 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm appearance-none shadow-sm cursor-pointer hover:bg-slate-50 transition">
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
                            <input type="text" placeholder="Cth: INV/2023/001" class="block w-full pl-3 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm font-mono">
                            <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-blue-600 hover:text-blue-800 font-medium cursor-pointer">
                                Auto
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi</label>
                        <textarea rows="3" placeholder="Jelaskan detail transaksi..." class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm resize-none"></textarea>
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
                        <!-- Baris 1 -->
                        <tr class="group hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option>101 - Kas Besar</option>
                                    <option>102 - Bank BCA</option>
                                    <option>401 - Pendapatan Jasa</option>
                                    <option>501 - Beban Sewa</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" oninput="calculateTotal()" class="input-debit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono placeholder-slate-400" placeholder="0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" oninput="calculateTotal()" class="input-credit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono placeholder-slate-400" placeholder="0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="deleteRow(this)" class="text-slate-400 hover:text-red-500 transition p-2 rounded-full hover:bg-red-50" title="Hapus Baris">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                         <!-- Baris 2 -->
                         <tr class="group hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select class="block w-full px-3 py-2 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option>101 - Kas Besar</option>
                                    <option selected>401 - Pendapatan Jasa</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" oninput="calculateTotal()" class="input-debit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono placeholder-slate-400" placeholder="0">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" oninput="calculateTotal()" class="input-credit block w-full text-right px-3 py-2 bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm font-mono placeholder-slate-400" placeholder="0">
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
</body>
</html>