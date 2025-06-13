{{-- resources/views/penjualan.blade.php --}}

@extends('layouts.app')

@section('title', 'Tambah Data Penjualan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/penjualan.css') }}">
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 text-gray-200">
    <header class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white tracking-tight">Tambah Data Penjualan</h1>
        <p class="text-lg text-gray-400 mt-3 max-w-2xl mx-auto">Input data penjualan secara manual atau unggah melalui file CSV untuk efisiensi.</p>
    </header>

    <div id="loadingOverlay" class="loading-overlay" style="display: none;">
        <div class="loader"></div>
    </div>

    <div id="styledSnackBar" class="snackbar">Pesan Notifikasi</div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
        <section id="manualEntrySection" class="bg-gray-800 shadow-2xl rounded-xl p-6 md:p-10">
            <h2 class="text-2xl xl:text-3xl font-semibold text-white mb-8 border-b border-gray-700 pb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Input Manual
            </h2>
            <form id="manualSaleForm" class="space-y-6">
                <div>
                    <label for="saleDate" class="block mb-2 text-sm font-medium text-gray-300">Tanggal Penjualan</label>
                    <input type="date" id="saleDate" name="saleDate"
                           class="block w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-gray-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-500"
                           required>
                </div>

                <div>
                    <label for="productName" class="block mb-2 text-sm font-medium text-gray-300">Nama Produk</label>
                    <input type="text" id="productName" name="productName"
                           class="block w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-gray-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-500"
                           placeholder="Masukkan produk anda" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="quantity" class="block mb-2 text-sm font-medium text-gray-300">Kuantitas</label>
                        <input type="number" id="quantity" name="quantity"
                               class="block w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-gray-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-500"
                               placeholder="0" min="1" required>
                    </div>
                    <div>
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-300">Harga per Item (Rp)</label>
                        <input type="number" id="price" name="price"
                               class="block w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 text-gray-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-500"
                               placeholder="0" min="0" step="any" required>
                    </div>
                </div>

                <div class="pt-3">
                    <label class="block mb-2 text-sm font-medium text-gray-300">Total Jumlah Penjualan:</label>
                    <div id="totalAmountDisplay"
                         class="text-3xl font-bold text-green-400 p-4 bg-gray-700/50 rounded-lg text-center tracking-tight">
                        Rp 0
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6">
                    <button type="button" id="clearFormButton"
                            class="w-full sm:w-auto px-6 py-3 rounded-lg font-semibold text-gray-200 bg-gray-600 hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50 transition-colors duration-150 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Bersihkan
                    </button>
                    <button type="submit" id="saveSaleButton"
                            class="w-full sm:w-auto px-8 py-3 rounded-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-colors duration-150 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </section>

        <section id="csvUploadSection" class="bg-gray-800 shadow-2xl rounded-xl p-6 md:p-10">
            <h2 class="text-2xl xl:text-3xl font-semibold text-white mb-8 border-b border-gray-700 pb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import dari CSV
            </h2>
            <form id="csvUploadForm" class="space-y-6">
                <div>
                    <label for="csvFile" class="block mb-2 text-sm font-medium text-gray-300">Pilih File CSV</label>
                    <input type="file" id="csvFile" name="csvFile"
                           class="block w-full text-sm text-gray-400 border border-gray-600 rounded-lg cursor-pointer bg-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500
                                  file:mr-4 file:py-3 file:px-5 file:rounded-l-lg file:border-0
                                  file:text-sm file:font-semibold file:bg-blue-600 file:text-white
                                  hover:file:bg-blue-700"
                           accept=".csv" required>
                </div>

                <div class="pt-3">
                    <button type="submit" id="uploadCsvButton"
                            class="w-full px-8 py-3 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-colors duration-150 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload dan Proses CSV
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-6 border-t border-gray-700 bg-gray-700/30 p-5 rounded-lg">
                <h3 class="text-lg font-semibold text-white mb-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Panduan Format CSV:
                </h3>
                <ul class="list-disc list-inside text-gray-400 space-y-1.5 text-sm pl-2">
                    <li>Baris pertama file CSV dianggap sebagai <strong>header</strong> dan akan dilewati.</li>
                    <li>Urutan kolom: <code>item_id</code>, <code>tanggal (YYYY-MM-DD)</code>, <code>jumlah_terjual</code>, <code>harga_item</code>.</li>
                    <li><code>item_id</code>: Nama atau ID produk (teks).</li>
                    <li><code>tanggal</code>: Format tanggal harus <strong>YYYY-MM-DD</strong> (contoh: 2024-12-31).</li>
                    <li><code>jumlah_terjual</code>: Angka bulat positif.</li>
                    <li><code>harga_item</code>: Angka desimal positif (gunakan titik `.` sebagai pemisah desimal jika ada).</li>
                </ul>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/papaparse@5.3.0/papaparse.min.js"></script>

<script>

    // Fungsi-fungsi akan didefinisikan di sini agar bisa diakses oleh DOMContentLoaded
    function showLoading(show) {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = show ? 'flex' : 'none';
        } else {
            console.error("Element #loadingOverlay tidak ditemukan.");
        }
    }

    function showSnackBar(message, isError = false) {
        const styledSnackBar = document.getElementById('styledSnackBar');
        if (!styledSnackBar) {
            console.error("Element #styledSnackBar tidak ditemukan.");
            return;
        }
        styledSnackBar.textContent = message;
        styledSnackBar.className = 'snackbar show'; // Reset kelas
        if (isError) {
            styledSnackBar.classList.add('error');
        } else {
            styledSnackBar.classList.add('success');
        }
        if(styledSnackBar.timeoutId) clearTimeout(styledSnackBar.timeoutId);
        styledSnackBar.timeoutId = setTimeout(() => {
            styledSnackBar.className = 'snackbar';
        }, 4000);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(amount);
    }

    // Variabel elemen akan diinisialisasi di dalam DOMContentLoaded
    let saleDateInput, productNameInput, quantityInput, priceInput, totalAmountDisplay, manualSaleForm, clearFormButton, csvUploadForm, csvFileInput;

    function calculateTotal() {
        console.log("Penjualan script: calculateTotal() dipanggil.");
        if (!quantityInput || !priceInput || !totalAmountDisplay) {
            console.error("calculateTotal: Satu atau lebih elemen (quantity, price, totalAmountDisplay) tidak ditemukan.");
            return;
        }
        const currentQuantity = parseFloat(quantityInput.value) || 0;
        const currentPrice = parseFloat(priceInput.value) || 0;
        const total = currentQuantity * currentPrice;
        totalAmountDisplay.textContent = formatCurrency(total);
        console.log(`Penjualan script: Kuantitas=${currentQuantity}, Harga=${currentPrice}, Total=${total}`);
    }

    function clearForm() {
        console.log("Penjualan script: clearForm() dipanggil.");
        if (manualSaleForm) {
            manualSaleForm.reset(); // Ini akan mengosongkan semua input dalam form, termasuk type="date"
        } else {
            console.error("clearForm: Element #manualSaleForm tidak ditemukan.");
        }
        calculateTotal(); // Update tampilan total menjadi Rp 0
        if (productNameInput) {
            productNameInput.focus();
        } else {
            console.error("clearForm: Element #productNameInput tidak ditemukan.");
        }
    }

    async function saveManualSale(event) {
        event.preventDefault();
        console.log("Penjualan script: saveManualSale() dipanggil.");
        const currentUser = auth.currentUser;
        if (!currentUser) {
            showSnackBar('Anda harus login terlebih dahulu.', true);
            console.warn("saveManualSale: User belum login.");
            return;
        }

        if (!saleDateInput || !productNameInput || !quantityInput || !priceInput) {
            showSnackBar('Terjadi kesalahan: Elemen form tidak ditemukan.', true);
            console.error("saveManualSale: Satu atau lebih elemen form tidak ditemukan.");
            return;
        }

        const saleDate = saleDateInput.value;
        const productName = productNameInput.value.trim();
        const quantity = parseInt(quantityInput.value);
        const price = parseFloat(priceInput.value);

        console.log("saveManualSale data:", { saleDate, productName, quantity, price });

        if (!saleDate) { showSnackBar('Tanggal penjualan harus diisi.', true); return; }
        if (!productName) { showSnackBar('Nama produk harus diisi.', true); return; }
        if (isNaN(quantity) || quantity <= 0) { showSnackBar('Kuantitas harus angka positif.', true); return; }
        if (isNaN(price) || price < 0) { showSnackBar('Harga harus angka positif atau nol.', true); return; }

        showLoading(true);
        try {
            await db.collection('penjualan').add({
                user_id: currentUser.uid,
                item_id: productName,
                tanggal: saleDate,
                jumlah_terjual: quantity,
                harga_item: price,
                timestamp: firebase.firestore.FieldValue.serverTimestamp(),
                source: 'manual_input'
            });
            showSnackBar('Data penjualan berhasil disimpan!');
            clearForm();
        } catch (error) {
            console.error("Error saving sale:", error);
            showSnackBar('Gagal menyimpan data: ' + error.message, true);
        } finally {
            showLoading(false);
        }
    }

    async function handleCsvUpload(event) {
        event.preventDefault();
        console.log("Penjualan script: handleCsvUpload() dipanggil.");
        const currentUser = auth.currentUser;
        if (!currentUser) {
            showSnackBar('Anda harus login terlebih dahulu.', true);
            console.warn("handleCsvUpload: User belum login.");
            return;
        }
        if (!csvFileInput) {
            showSnackBar('Terjadi kesalahan: Elemen input file tidak ditemukan.', true);
            console.error("handleCsvUpload: Element #csvFileInput tidak ditemukan.");
            return;
        }

        const file = csvFileInput.files[0];
        if (!file) {
            showSnackBar('Silakan pilih file CSV terlebih dahulu.', true);
            return;
        }

        showLoading(true);

        Papa.parse(file, {
            header: false,
            skipEmptyLines: true,
            complete: async function(results) {
                console.log("CSV Parsing complete:", results);
                const dataRows = results.data;
                if (dataRows.length <= 1) {
                    showSnackBar('File CSV kosong atau hanya berisi header.', true);
                    showLoading(false);
                    if (csvUploadForm) csvUploadForm.reset();
                    return;
                }

                const salesDataList = [];
                let hasErrorInCsv = false;
                for (let i = 1; i < dataRows.length; i++) {
                    const row = dataRows[i];
                    const rowNumber = i + 1;

                    if (row.length !== 4) {
                        showSnackBar(`Baris ${rowNumber}: Jumlah kolom tidak sesuai (harap 4 kolom, ditemukan ${row.length}).`, true);
                        hasErrorInCsv = true; break;
                    }

                    const [itemId, dateString, quantityString, priceString] = row.map(val => String(val || '').trim());

                    if (!itemId) {
                        showSnackBar(`Baris ${rowNumber}: item_id tidak boleh kosong.`, true);
                        hasErrorInCsv = true; break;
                    }
                    if (!/^\d{4}-\d{2}-\d{2}$/.test(dateString)) {
                        showSnackBar(`Baris ${rowNumber}: Format tanggal salah untuk "${dateString}". Harap gunakan YYYY-MM-DD.`, true);
                        hasErrorInCsv = true; break;
                    }
                    const quantity = parseInt(quantityString);
                    if (isNaN(quantity) || quantity <= 0) {
                        showSnackBar(`Baris ${rowNumber}: Kuantitas salah "${quantityString}". Harus angka bulat positif.`, true);
                        hasErrorInCsv = true; break;
                    }
                    const price = parseFloat(priceString.replace(',', '.'));
                    if (isNaN(price) || price < 0) {
                        showSnackBar(`Baris ${rowNumber}: Harga salah "${priceString}". Harus angka positif atau nol.`, true);
                        hasErrorInCsv = true; break;
                    }

                    salesDataList.push({
                        user_id: currentUser.uid,
                        item_id: itemId,
                        tanggal: dateString,
                        jumlah_terjual: quantity,
                        harga_item: price,
                        timestamp: firebase.firestore.FieldValue.serverTimestamp(),
                        source: 'csv_upload'
                    });
                }

                if (hasErrorInCsv) {
                    showLoading(false);
                    if (csvUploadForm) csvUploadForm.reset();
                    return;
                }

                if (salesDataList.length > 0) {
                    try {
                        const batch = db.batch();
                        salesDataList.forEach(saleData => {
                            const docRef = db.collection('penjualan').doc();
                            batch.set(docRef, saleData);
                        });
                        await batch.commit();
                        showSnackBar(`${salesDataList.length} data penjualan berhasil diimpor dari CSV!`);
                        if (csvUploadForm) csvUploadForm.reset();
                    } catch (error) {
                        console.error("Error batch saving CSV data:", error);
                        showSnackBar('Gagal menyimpan data CSV: ' + error.message, true);
                    }
                } else if (!hasErrorInCsv) {
                    showSnackBar('Tidak ada data valid yang ditemukan dalam file CSV.', true);
                }
                showLoading(false);
            },
            error: function(error, file) {
                console.error("Error parsing CSV:", error, file);
                showSnackBar('Gagal memproses file CSV: ' + error.message, true);
                showLoading(false);
                if (csvUploadForm) csvUploadForm.reset();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log("Penjualan script: DOMContentLoaded event fired.");

        // Inisialisasi variabel elemen DOM
        saleDateInput = document.getElementById('saleDate');
        productNameInput = document.getElementById('productName');
        quantityInput = document.getElementById('quantity');
        priceInput = document.getElementById('price');
        totalAmountDisplay = document.getElementById('totalAmountDisplay');
        manualSaleForm = document.getElementById('manualSaleForm');
        clearFormButton = document.getElementById('clearFormButton');
        csvUploadForm = document.getElementById('csvUploadForm');
        csvFileInput = document.getElementById('csvFile');

        // Verifikasi apakah semua elemen penting ditemukan
        if (!saleDateInput) console.error("Element #saleDate tidak ditemukan!");
        if (!productNameInput) console.error("Element #productName tidak ditemukan!");
        if (!quantityInput) console.error("Element #quantity tidak ditemukan!");
        if (!priceInput) console.error("Element #price tidak ditemukan!");
        if (!totalAmountDisplay) console.error("Element #totalAmountDisplay tidak ditemukan!");
        if (!manualSaleForm) console.error("Element #manualSaleForm tidak ditemukan!");
        if (!clearFormButton) console.error("Element #clearFormButton tidak ditemukan!");
        if (!csvUploadForm) console.error("Element #csvUploadForm tidak ditemukan!");
        if (!csvFileInput) console.error("Element #csvFileInput tidak ditemukan!");


        auth.onAuthStateChanged(user => {
            if (user) {
                // console.log("User is signed in:", user.uid);
            } else {
                console.warn("User not logged in. Sales functionality will require login.");
                // Anda bisa menonaktifkan form atau tombol di sini jika user belum login
                // contoh: if(manualSaleForm) manualSaleForm.style.opacity = '0.5';
            }
        });

        if (quantityInput) {
            quantityInput.addEventListener('input', calculateTotal);
            console.log("Listener 'input' ditambahkan ke #quantity.");
        }
        if (priceInput) {
            priceInput.addEventListener('input', calculateTotal);
            console.log("Listener 'input' ditambahkan ke #price.");
        }
        if (manualSaleForm) {
            manualSaleForm.addEventListener('submit', saveManualSale);
            console.log("Listener 'submit' ditambahkan ke #manualSaleForm.");
        }
        if (clearFormButton) {
            clearFormButton.addEventListener('click', clearForm);
            console.log("Listener 'click' ditambahkan ke #clearFormButton.");
        }
        if (csvUploadForm) {
            csvUploadForm.addEventListener('submit', handleCsvUpload);
            console.log("Listener 'submit' ditambahkan ke #csvUploadForm.");
        }

        calculateTotal(); // Panggil sekali saat load untuk inisialisasi tampilan total (Rp 0)
        console.log("Penjualan script: Inisialisasi selesai, event listeners terpasang.");
    });

</script>
@endpush