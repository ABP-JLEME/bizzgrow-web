{{-- resources/views/prediksi.blade.php --}}

@extends('layouts.app')

@section('title', 'Prediksi Penjualan')

@section('content')

{{-- === START: Firebase Initialization and Chart.js === --}}
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.min.js"></script> {{-- date-fns utility --}}

<style>
    /* Basic Styling - sesuaikan dengan app.css dan sidebar.css Anda */
    .prediksi-container {
        font-family: 'Inter', sans-serif;
        color: #E0E0E0; /* textWhiteColor approximation */
    }

    .prediksi-card {
        background-color: #2C2F33; /* cardBackgroundColor approximation */
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
        border: 1px solid #4F545C; /* subtleBorderColor approximation */
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .prediksi-section-title {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
        color: #E0E0E0; /* textWhiteColor */
    }
    .prediksi-section-title i { /* Material Icons class */
        margin-right: 10px;
        color: #00A3FF; /* accentBlueColor approximation */
        font-size: 22px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 15px;
        font-weight: 500;
        color: #E0E0E0; /* textWhiteColor */
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 16px;
        background-color: #36393F; /* cardLightBackgroundColor approximation */
        border: 1.2px solid #5A5E63; /* subtleBorderColor.withOpacity(0.8) */
        border-radius: 12px;
        color: #E0E0E0; /* textWhiteColor */
        font-size: 15px;
        appearance: none; /* For custom arrow on select */
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }
    .form-select {
         background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23C7C7C7' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
    }


    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #00A3FF; /* accentBlueColor */
        box-shadow: 0 0 0 0.2rem rgba(0, 163, 255, 0.25);
    }

    .segmented-control {
        display: flex;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        border: 1.2px solid #5A5E63;
    }

    .segmented-control input[type="radio"] {
        display: none;
    }

    .segmented-control label {
        flex: 1;
        text-align: center;
        /* padding: 12px 10px; */
        padding-top: 12px;
        padding-bottom: 12px;
        padding-left: 10px;
        padding-right: 10px;
        background-color: #36393F; /* cardLightBackgroundColor */
        color: #B0B0B0; /* textWhite70Color */
        cursor: pointer;
        font-size: 13.5px;
        font-weight: 500;
        transition: background-color 0.3s, color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        margin: 0;
        line-height: 1.2;   
    }
    .segmented-control label i {
        margin-right: 6px;
        font-size: 18px;
        display: flex; 
        align-items: center;
    }

    .segmented-control input[type="radio"]:checked + label {
        background-color: #00A3FF; /* accentBlueColor */
        color: #FFFFFF; /* textWhiteColor */
    }
    .segmented-control label:not(:last-child) {
        border-right: 1.2px solid #5A5E63;
    }


    .btn-predict {
        width: 100%;
        padding: 16px;
        font-size: 18px;
        font-weight: bold;
        color: #FFFFFF;
        background-color: #00A3FF; /* accentBlueColor */
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-predict i {
        margin-right: 8px;
        font-size: 22px;
    }

    .btn-predict:hover {
        background-color: #007acc;
    }
    .btn-predict:disabled {
        background-color: #00A3FF80; /* accentBlueColor with opacity */
        color: #E0E0E0B3; /* textWhite70Color with opacity */
        cursor: not-allowed;
    }

    .results-section-title {
        text-align: center;
        font-size: 21px;
        font-weight: bold;
        color: #E0E0E0;
        margin-bottom: 20px;
    }

    .key-metrics-row {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }

    .key-metric-card {
        flex: 1;
        background-color: #2C2F33; /* cardBackgroundColor */
        padding: 18px 16px;
        border-radius: 14px;
        border: 1px solid #4F545C;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    }
    .key-metric-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
    }
    .key-metric-title {
        font-size: 15px;
        font-weight: 600;
        color: #E0E0E0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .key-metric-subtitle {
        font-size: 12px;
        color: #B0B0B0; /* textWhite70Color */
    }
    .key-metric-icon i {
        font-size: 30px;
    }
    .key-metric-value {
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 0.5px;
        color: #E0E0E0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .text-success { color: #28A745; } /* successColor */
    .text-danger  { color: #DC3545; } /* errorColor */
    .text-warning { color: #FFC107; } /* warningColor */


    .chart-legend {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 24px;
        margin-top: 18px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 500;
        color: #E0E0E0;
    }
    .legend-color-box {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }
    .legend-color-box-dashed {
        width: 18px;
        height: 16px; /* Enough height for dashes */
        /* Dashed line will be drawn by JS or a simple div setup if needed */
        border: 2px dashed; /* Placeholder, better to use a custom element or SVG */
        display: flex;
        align-items: center;
    }
     .legend-line-dashed {
        width: 18px;
        height: 2px; /* Thickness of the line */
        border-top: 3px dashed; /* color will be set by JS */
    }


    .loading-indicator, .error-display, .placeholder-info {
        padding: 20px;
        margin: 20px 0;
        border-radius: 14px;
        text-align: center;
    }
    .loading-indicator {
        color: #B0B0B0;
    }
    .loading-indicator .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #5A5E63;
        border-top-color: #00A3FF;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 16px auto;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .error-display {
        background-color: rgba(220, 53, 69, 0.1); /* errorColor with opacity */
        border: 1.5px solid rgba(220, 53, 69, 0.4);
        color: #E0E0E0;
        display: flex;
        align-items: flex-start;
        text-align: left;
    }
    .error-display i {
        font-size: 30px;
        color: #DC3545; /* errorColor */
        margin-right: 14px;
    }

    .placeholder-info {
        background-color: rgba(54, 57, 63, 0.5); /* cardLightBackgroundColor with opacity */
        border: 1px solid #4F545C;
    }
    .placeholder-info i {
        font-size: 48px;
        color: rgba(0, 163, 255, 0.8); /* accentBlueColor with opacity */
        margin-bottom: 16px;
    }
    .placeholder-info p {
        font-size: 16px;
        font-weight: 500;
        color: #B0B0B0;
        line-height: 1.5;
    }
    
    /* Helper for snackbar-like messages */
    .snackbar {
        position: fixed;
        left: 50%;
        bottom: 30px;
        transform: translateX(-50%);
        padding: 12px 20px;
        border-radius: 12px;
        color: white;
        font-size: 15px;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center; 
        text-align: left;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        opacity: 0;
        transition: opacity 0.3s ease-in-out, bottom 0.3s ease-in-out;
        min-width: 280px; 
        max-width: 450px;
        box-sizing: border-box;
    }
    .snackbar.show {
        opacity: 1;
        bottom: 50px;
    }
    .snackbar.error { background-color: #DC3545; } /* errorColor */
    .snackbar.success { background-color: #28A745; } /* successColor */
    .snackbar.warning { background-color: #FFC107; color: #212529; } /* warningColor */
    .snackbar i { 
        margin-right: 10px; 
        font-size: 20px; 
        flex-shrink: 0;
    }
    .snackbar span { /* Teks pesan */
        flex-grow: 1; /* Biarkan teks mengambil sisa ruang jika perlu */
    }


    /* Link to Google Material Icons */
    /* Make sure this is included in your main app.blade.php or here */
    /* @import url('https://fonts.googleapis.com/icon?family=Material+Icons+Outlined'); */
    /* Or if you prefer specific icons: */
    /* .material-icons-outlined { font-family: 'Material Icons Outlined'; ... } */
    /* For this example, I'll use common unicode or FontAwesome if available */
    /* Let's assume you have Material Icons or similar loaded */
    /* If not, replace <i> tags with appropriate icons (e.g., SVG, text emojis) */

</style>
{{-- Include Material Icons (if not already in app.blade.php) --}}
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">


<div class="prediksi-container">
    {{-- Notification Snackbar Placeholder --}}
    <div id="snackbar" class="snackbar">
        <i class="material-icons-outlined" id="snackbar-icon"></i>
        <span id="snackbar-message"></span>
    </div>

    {{-- Settings Section --}}
    <div class="prediksi-card">
        <h2 class="prediksi-section-title">
            <i class="material-icons-outlined">settings_applications</i>
            Konfigurasi Prediksi
        </h2>
        <div class="form-group">
            <label for="item-select">Produk yang akan diprediksi</label>
            <div style="position: relative;">
                 <select id="item-select" class="form-select" disabled>
                    <option value="">Memuat produk...</option>
                </select>
                <span id="item-select-loading" style="position: absolute; right: 35px; top: 50%; transform: translateY(-50%); display: none;">
                    <div class="spinner" style="width:20px; height:20px; border-width: 2.5px;"></div>
                </span>
            </div>
            <small style="color: #B0B0B0; font-size: 12px; margin-top: 4px; display: block;">Minimal 120 data penjualan historis per produk.</small>
        </div>

        <div class="form-group">
            <label for="time-range-select">Periode Prediksi</label>
            <select id="time-range-select" class="form-select">
                <option value="7">1 Minggu</option>
                <option value="14">2 Minggu</option>
                <option value="21">3 Minggu</option>
                <option value="30" selected>1 Bulan</option>
                <option value="90">3 Bulan</option>
            </select>
        </div>

        <div class="form-group">
            <label>Tampilan Chart</label>
            <div class="segmented-control" id="aggregation-select">
                <input type="radio" name="aggregation" id="agg-daily" value="daily" checked>
                <label for="agg-daily"><i class="material-icons-outlined">view_day</i>Harian</label>

                <input type="radio" name="aggregation" id="agg-weekly" value="weekly">
                <label for="agg-weekly"><i class="material-icons-outlined">view_week</i>Mingguan</label>

                <input type="radio" name="aggregation" id="agg-monthly" value="monthly">
                <label for="agg-monthly"><i class="material-icons-outlined">calendar_month</i>Bulanan</label>
            </div>
        </div>
    </div>

    {{-- Generate Button --}}
    <button id="predict-button" class="btn-predict" disabled>
        <i class="material-icons-outlined">online_prediction</i>
        Prediksi Sekarang
    </button>

    {{-- Loading/Error/Results Area --}}
    <div id="loading-indicator" class="loading-indicator" style="display: none;">
        <div class="spinner"></div>
        <p>Melakukan prediksi, ini mungkin membutuhkan sedikit waktu...</p>
    </div>

    <div id="error-display" class="error-display" style="display: none;">
        <i class="material-icons-outlined">error_rounded</i>
        <span id="error-message"></span>
    </div>
    
    <div id="results-section" style="display: none; margin-top: 24px;">
        <h2 class="results-section-title" id="results-title">Forecast Insights for "Item"</h2>
        <div class="key-metrics-row">
            <div class="key-metric-card">
                <div class="key-metric-card-header">
                    <div>
                        <div class="key-metric-title">Hasil Prediksi Pendapatan</div>
                        <div class="key-metric-subtitle" id="revenue-subtitle">Dalam X hari</div>
                    </div>
                    <div class="key-metric-icon"><i class="material-icons-outlined" style="color: #FFC107;">account_balance_wallet</i></div>
                </div>
                <div class="key-metric-value" id="predicted-revenue">Rp 0</div>
            </div>
            <div class="key-metric-card">
                 <div class="key-metric-card-header">
                    <div>
                        <div class="key-metric-title">Pertumbuhan Penjualan</div>
                        <div class="key-metric-subtitle" id="growth-subtitle">vs X hari lalu</div>
                    </div>
                    <div class="key-metric-icon"><i class="material-icons-outlined text-success" id="sales-growth-icon">show_chart</i></div>
                </div>
                <div class="key-metric-value text-success" id="sales-growth-percentage">0.0%</div>
            </div>
        </div>

        <div class="prediksi-card">
            <h2 class="prediksi-section-title">
                <i class="material-icons-outlined">insights</i>
                Sales Trend & Forecast
            </h2>
            <div style="height: 300px; position: relative;">
                <canvas id="sales-chart"></canvas>
                 <div id="chart-loading-indicator" class="loading-indicator" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(44,47,51,0.7); display:none; align-items:center; justify-content:center; flex-direction:column;">
                    <div class="spinner"></div>
                    <p>Menyiapkan data dalam chart...</p>
                </div>
            </div>
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-color-box" style="background: linear-gradient(to right, #007bff, #00A3FFB3);"></div>
                    <span>Historical</span>
                </div>
                <div class="legend-item">
                    <div id="legend-forecast-line" class="legend-line-dashed" style="border-color: #28A745;"></div>
                    <span>Forecasted</span>
                </div>
            </div>
        </div>
    </div>

    <div id="placeholder-info" class="placeholder-info">
        <i class="material-icons-outlined">query_stats</i>
        <p>Konfigurasikan pengaturan dan buat prediksi sekarang.</p>
    </div>

</div>

<script>
    const firebaseConfig = {
        apiKey: "{{ env('FIREBASE_API_KEY') }}",
        authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
        projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
        messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
        appId: "{{ env('FIREBASE_APP_ID') }}"
    };

    // Initialize Firebase
    const app = firebase.initializeApp(firebaseConfig);
    const auth = firebase.auth();
    const db = firebase.firestore();
    const dateFns = window.dateFns; // Use date-fns from global scope

    // DOM Elements
    const itemSelect = document.getElementById('item-select');
    const itemSelectLoading = document.getElementById('item-select-loading');
    const timeRangeSelect = document.getElementById('time-range-select');
    const aggregationSelect = document.getElementById('aggregation-select'); // div containing radio buttons
    const predictButton = document.getElementById('predict-button');
    
    const loadingIndicator = document.getElementById('loading-indicator');
    const errorDisplay = document.getElementById('error-display');
    const errorMessageSpan = document.getElementById('error-message');
    const resultsSection = document.getElementById('results-section');
    const placeholderInfo = document.getElementById('placeholder-info');
    const chartLoadingIndicator = document.getElementById('chart-loading-indicator');

    const resultsTitle = document.getElementById('results-title');
    const revenueSubtitle = document.getElementById('revenue-subtitle');
    const predictedRevenueSpan = document.getElementById('predicted-revenue');
    const growthSubtitle = document.getElementById('growth-subtitle');
    const salesGrowthIcon = document.getElementById('sales-growth-icon');
    const salesGrowthPercentageSpan = document.getElementById('sales-growth-percentage');
    
    const snackbar = document.getElementById('snackbar');
    const snackbarIcon = document.getElementById('snackbar-icon');
    const snackbarMessage = document.getElementById('snackbar-message');

    // State variables
    let currentUser = null;
    let itemDetails = {}; // Store item price, count etc. { itemId: { price: X, count: Y } }
    let eligibleItemsForDropdown = []; // Array of { value: itemId, text: itemId }
    let currentForecastData = null; // Raw forecast data from API
    let historicalSpotsData = [];
    let forecastSpotsData = [];
    let salesChartInstance = null;

    // --- Constants (approximations from Flutter code) ---
    const ACCENT_BLUE_COLOR = '#00A3FF';
    const PRIMARY_BLUE_COLOR = '#007BFF'; // Example, adjust as needed
    const SUCCESS_COLOR = '#28A745';
    const ERROR_COLOR = '#DC3545';
    const WARNING_COLOR = '#FFC107';
    const TEXT_WHITE_COLOR = '#E0E0E0';
    const TEXT_WHITE70_COLOR = '#B0B0B0';
    const CARD_BACKGROUND_COLOR = '#2C2F33';
    const CARD_LIGHT_BACKGROUND_COLOR = '#36393F';
    const SUBTLE_BORDER_COLOR = '#4F545C';


    // --- UTILITY FUNCTIONS ---
    function showStyledSnackBar(message, type = 'success') { // type: success, error, warning
        snackbarMessage.textContent = message;
        snackbar.className = 'snackbar show ' + type; // Reset classes and add new ones
        
        if (type === 'error') snackbarIcon.textContent = 'error_outline_rounded';
        else if (type === 'warning') snackbarIcon.textContent = 'warning_amber_rounded';
        else snackbarIcon.textContent = 'check_circle_outline_rounded';

        setTimeout(() => {
            snackbar.className = snackbar.className.replace('show', '');
        }, type === 'error' ? 5000 : (type === 'warning' ? 4000 : 3000));
    }
    
    function formatCurrency(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    // --- CORE LOGIC FUNCTIONS ---
    async function fetchEligibleItems(userId) {
        itemSelectLoading.style.display = 'inline-block';
        itemSelect.disabled = true;
        itemSelect.innerHTML = '<option value="">Memuat produk...</option>';
        predictButton.disabled = true;
        eligibleItemsForDropdown = [];
        itemDetails = {};

        try {
            const salesSnapshot = await db.collection('penjualan')
                .where('user_id', '==', userId)
                .orderBy('item_id')
                .get();

            if (salesSnapshot.empty) {
                itemSelect.innerHTML = '<option value="" disabled>Tidak ada produk ditemukan</option>';
                return;
            }

            const itemCounts = {};
            const tempItemPrices = {}; // Store last seen price

            salesSnapshot.forEach(doc => {
                const data = doc.data();
                const itemId = data.item_id;
                itemCounts[itemId] = (itemCounts[itemId] || 0) + 1;
                if (data.harga_item !== undefined) {
                    tempItemPrices[itemId] = parseFloat(data.harga_item);
                }
            });
            
            Object.keys(itemCounts).forEach(itemId => {
                if (itemCounts[itemId] >= 120) {
                    eligibleItemsForDropdown.push({ value: itemId, text: itemId });
                    itemDetails[itemId] = { price: tempItemPrices[itemId] || 0.0, count: itemCounts[itemId] };
                }
            });

            if (eligibleItemsForDropdown.length === 0) {
                itemSelect.innerHTML = '<option value="" disabled>Produk (min. 120 data) tidak ditemukan</option>';
            } else {
                itemSelect.innerHTML = '<option value="">Pilih produk</option>'; // Default prompt
                eligibleItemsForDropdown.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.value;
                    option.textContent = item.text;
                    itemSelect.appendChild(option);
                });
                itemSelect.disabled = false;
            }
        } catch (e) {
            console.error("Error fetching items:", e);
            showStyledSnackBar("Error mengambil data produk: " + e.toString(), 'error');
            itemSelect.innerHTML = '<option value="" disabled>Error memuat produk</option>';
        } finally {
            itemSelectLoading.style.display = 'none';
            // predictButton state will be handled by item selection
        }
    }

    async function generatePrediction() {
        if (!currentUser) {
            showStyledSnackBar("Tidak ada user login, silahkan login kembali", 'error');
            return;
        }
        const selectedItemId = itemSelect.value;
        if (!selectedItemId) {
            showStyledSnackBar("Silahkan pilih produk yang akan di prediksi", 'warning');
            return;
        }

        // UI updates for loading state
        predictButton.disabled = true;
        loadingIndicator.style.display = 'block';
        errorDisplay.style.display = 'none';
        resultsSection.style.display = 'none';
        placeholderInfo.style.display = 'none';
        currentForecastData = null;
        historicalSpotsData = [];
        forecastSpotsData = [];

        const userId = currentUser.uid;
        const futureDays = parseInt(timeRangeSelect.value);
        
        // API URL - GANTI DENGAN URL API FLASK ANDA
        // const baseUrl = "http://10.0.2.2:5000"; // Untuk Android Emulator di host yg sama dgn Flutter
        const baseUrl = "http://127.0.0.1:5000"; // Jika Flask jalan di mesin yang sama dgn browser
        // const baseUrl = "https://578d-114-10-45-122.ngrok-free.app"; // Untuk API yang di-deploy

        try {
            showStyledSnackBar(`Training model untuk '${selectedItemId}'...`, 'warning');
            const trainResponse = await fetch(`${baseUrl}/train`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ user_id: userId, item_id: selectedItemId }),
                // Implement timeout if possible, fetch API doesn't have a direct timeout like Dart's http
                // Can use AbortController for timeout
            });
             // .timeout(const Duration(seconds: 180)); // Cannot directly translate timeout

            if (!trainResponse.ok) {
                const errorBody = await trainResponse.json().catch(() => ({error: trainResponse.statusText}));
                throw new Error(`Train API Error: ${trainResponse.status} - ${errorBody.error || trainResponse.statusText}`);
            }
            const trainBody = await trainResponse.json();
            showStyledSnackBar(trainBody.message || `Training model untuk '${selectedItemId}' successful.`);

            showStyledSnackBar(`Fetching forecast for ${futureDays} days...`, 'warning');
            const forecastResponse = await fetch(`${baseUrl}/forecast`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    user_id: userId,
                    item_id: selectedItemId,
                    future_days: futureDays,
                }),
            });
            // .timeout(const Duration(seconds: 90));

            if (!forecastResponse.ok) {
                const errorBody = await forecastResponse.json().catch(() => ({error: forecastResponse.statusText}));
                throw new Error(`Forecast API Error: ${forecastResponse.status} - ${errorBody.error || forecastResponse.statusText}`);
            }
            
            const forecastResult = await forecastResponse.json();
            if (forecastResult.forecast && Array.isArray(forecastResult.forecast)) {
                currentForecastData = forecastResult.forecast.map(e => Number(e));
                if (currentForecastData.length === 0) {
                    showStyledSnackBar("Forecast successful, but no prediction data was returned for the period.", 'warning');
                    await processAndDisplayResults(userId, selectedItemId, futureDays); // Tetap proses untuk chart kosong
                } else {
                    showStyledSnackBar(forecastResult.message || "Forecast received successfully!");
                    await processAndDisplayResults(userId, selectedItemId, futureDays);
                }
            } else {
                throw new Error(forecastResult.error || "Invalid forecast response format.");
            }

        } catch (e) {
            console.error("Error Prediksi:", e);
            let errorMessageToShow = e.message || e.toString();
            if (e.message && e.message.includes("Failed to fetch")) { // Common browser network error
                 errorMessageToShow = `Terjadi kesalahan jaringan atau server tidak dapat dijangkau. Silakan periksa koneksi Anda dan server API (${baseUrl}).`;
            } else if (e.message && e.message.includes("ECONNREFUSED")) { // For Node.js like errors if using a proxy or specific server setup
                 errorMessageToShow = `Koneksi ke server API ditolak. Apakah server sudah berjalan di ${baseUrl}?`;
            }
            // TimeoutException and FormatException are more Dart specific. Fetch API errors are generic.

            errorMessageSpan.textContent = errorMessageToShow;
            errorDisplay.style.display = 'flex'; // 'flex' because of icon and text
            showStyledSnackBar(errorMessageToShow, 'error');
        } finally {
            loadingIndicator.style.display = 'none';
            predictButton.disabled = false; // Re-enable button
            if(!currentForecastData && !errorDisplay.style.display.includes('flex')){ // If no data and no error, show placeholder
                 placeholderInfo.style.display = 'block';
            }
        }
    }

    function aggregateData(dailySourceData, overallPeriodEndDateStr, numberOfPointsToGenerate, aggregationType, isHistorical = true) {
        console.log(`DEBUG AGGREGATE (${isHistorical ? 'Hist' : 'Fcst'}, ${aggregationType}): dailySourceData:`, JSON.stringify(dailySourceData));
        console.log(`DEBUG AGGREGATE (${isHistorical ? 'Hist' : 'Fcst'}, ${aggregationType}): overallPeriodEndDateStr: ${overallPeriodEndDateStr}, numberOfPointsToGenerate: ${numberOfPointsToGenerate}`);

        const aggregatedSpots = [];
        const overallPeriodEndDate = dateFns.parse(overallPeriodEndDateStr, 'YYYY-MM-DD', new Date());

        if (numberOfPointsToGenerate === 0) return aggregatedSpots;
        if (Object.keys(dailySourceData).length === 0) {
            for (let i = 0; i < numberOfPointsToGenerate; i++) aggregatedSpots.push({ x: i, y: 0 });
            return aggregatedSpots;
        }

        for (let i = 0; i < numberOfPointsToGenerate; i++) {
            let periodTotal = 0;
            let pointPeriodStartDate, pointPeriodEndDate;

            if (aggregationType === 'daily') {
                let referenceDay;
                if (isHistorical) {
                    referenceDay = dateFns.subDays(overallPeriodEndDate, (numberOfPointsToGenerate - 1 - i));
                } else { // Forecast: overallPeriodEndDate is the last day of forecast
                    referenceDay = dateFns.subDays(overallPeriodEndDate, (numberOfPointsToGenerate - 1 - i));
                }
                const dateStr = dateFns.format(referenceDay, 'YYYY-MM-DD');
                periodTotal = dailySourceData[dateStr] || 0.0;

                // >>> BARIS BARU UNTUK DEBUG <<<
                if (!isHistorical && dailySourceData[dateStr] === undefined && Object.keys(dailySourceData).length > 0) {
                    console.warn(`WARN AGGREGATE FORECAST DAILY: Tanggal ${dateStr} tidak ditemukan di dailySourceData. Keys: ${Object.keys(dailySourceData).join(', ')}`);
                }
                if (!isHistorical) { // Log khusus untuk forecast
                    console.log(`DEBUG AGGREGATE FORECAST DAILY: RefDay: ${dateStr}, Value: ${dailySourceData[dateStr]}, PeriodTotal: ${periodTotal}`);
                }
                // >>> AKHIR BARIS BARU <<<
            } else if (aggregationType === 'weekly') {
                if (isHistorical) {
                    pointPeriodEndDate = dateFns.subDays(overallPeriodEndDate, (numberOfPointsToGenerate - 1 - i) * 7);
                    pointPeriodStartDate = dateFns.subDays(pointPeriodEndDate, 6);
                } else {
                    pointPeriodEndDate = dateFns.subDays(overallPeriodEndDate, (numberOfPointsToGenerate - 1 - i) * 7);
                    pointPeriodStartDate = dateFns.subDays(pointPeriodEndDate, 6);
                }
                
                let currentDate = pointPeriodStartDate;
                let weeklySumForDebug = 0;
                while(dateFns.isBefore(currentDate, dateFns.addDays(pointPeriodEndDate,1)) && dateFns.isBefore(currentDate, dateFns.addDays(overallPeriodEndDate,1))){
                    const dateStr = dateFns.format(currentDate, 'YYYY-MM-DD');
                    periodTotal += dailySourceData[dateStr] || 0.0;
                    currentDate = dateFns.addDays(currentDate, 1);
                }
                // >>> BARIS BARU UNTUK DEBUG <<<
                if(!isHistorical) { // Log khusus untuk forecast weekly
                    console.log(`DEBUG AGGREGATE FORECAST WEEKLY (Point ${i}): Start: ${dateFns.format(pointPeriodStartDate, 'YYYY-MM-DD')}, End: ${dateFns.format(pointPeriodEndDate, 'yyyy-MM-dd')}, Sum: ${weeklySumForDebug}, PeriodTotal: ${periodTotal}`);
                }
                if (!isHistorical && weeklySumForDebug === 0 && Object.keys(dailySourceData).length > 0 && periodTotal === 0) {
                    // Log detail jika sum 0 tapi ada data di source
                    let relevantKeys = Object.keys(dailySourceData).filter(d => dateFns.isWithinRange(dateFns.parse(d, 'YYYY-MM-DD', new Date()), pointPeriodStartDate, pointPeriodEndDate));
                    if (relevantKeys.length > 0) {
                        console.warn(`WARN AGGREGATE FORECAST WEEKLY: Sum 0 untuk minggu ${dateFns.format(pointPeriodStartDate, 'YYYY-MM-DD')} - ${dateFns.format(pointPeriodEndDate, 'YYYY-MM-DD')} padahal ada data di source: ${relevantKeys.map(k => `${k}:${dailySourceData[k]}`).join(', ')}`);
                    }
                }
                // >>> AKHIR BARIS BARU <<<
            } else if (aggregationType === 'monthly') {
                 let targetMonthDate;
                if (isHistorical) {
                    const monthOffset = numberOfPointsToGenerate - 1 - i;
                    targetMonthDate = dateFns.subMonths(overallPeriodEndDate, monthOffset);
                    pointPeriodStartDate = dateFns.startOfMonth(targetMonthDate);
                    pointPeriodEndDate = dateFns.endOfMonth(targetMonthDate);
                    if (dateFns.isAfter(pointPeriodEndDate, overallPeriodEndDate)) pointPeriodEndDate = overallPeriodEndDate;
                } else {
                    const monthOffset = numberOfPointsToGenerate - 1 - i;
                    targetMonthDate = dateFns.subMonths(overallPeriodEndDate, monthOffset);
                    pointPeriodStartDate = dateFns.startOfMonth(targetMonthDate);
                    pointPeriodEndDate = dateFns.endOfMonth(targetMonthDate);
                     if (dateFns.isAfter(pointPeriodEndDate, overallPeriodEndDate)) pointPeriodEndDate = overallPeriodEndDate;
                }
                
                let currentDate = pointPeriodStartDate;
                while(dateFns.isBefore(currentDate, dateFns.addDays(pointPeriodEndDate,1)) && dateFns.isBefore(currentDate, dateFns.addDays(overallPeriodEndDate,1))){
                    const dateStr = dateFns.format(currentDate, 'YYYY-MM-DD');
                    periodTotal += dailySourceData[dateStr] || 0.0;
                    currentDate = dateFns.addDays(currentDate, 1);
                }
            }
            aggregatedSpots.push({ x: i, y: Math.round(Math.max(0, periodTotal)) });
        }
        return aggregatedSpots;
    }
    
    async function processAndDisplayResults(userId, itemId, futureDaysForForecast) {
        chartLoadingIndicator.style.display = 'flex';
        if (currentForecastData === null) { // Check if API returned data
            showStyledSnackBar("Data prediksi tidak tersedia. Tidak dapat memproses hasil.", 'error');
            errorMessageSpan.textContent = "Data prediksi tidak ada.";
            errorDisplay.style.display = 'flex';
            historicalSpotsData = [];
            forecastSpotsData = [];
            renderSalesChart(); // Render empty or error chart
            chartLoadingIndicator.style.display = 'none';
            return;
        }

        const selectedAggregation = document.querySelector('input[name="aggregation"]:checked').value;
        let pointsToDisplayOnChart;
        if (selectedAggregation === 'weekly') {
            pointsToDisplayOnChart = Math.ceil(futureDaysForForecast / 7);
        } else if (selectedAggregation === 'monthly') {
            pointsToDisplayOnChart = Math.ceil(futureDaysForForecast / 30); // Rough estimate
        } else { // daily
            pointsToDisplayOnChart = futureDaysForForecast;
        }
        if (pointsToDisplayOnChart === 0 && futureDaysForForecast > 0) pointsToDisplayOnChart = 1;
        
        if (pointsToDisplayOnChart === 0 && futureDaysForForecast === 0) {
            errorMessageSpan.textContent = "Tidak bisa melakukan prediksi untuk 0 hari.";
            errorDisplay.style.display = 'flex';
            historicalSpotsData = [];
            forecastSpotsData = [];
            resultsSection.style.display = 'none'; // Hide results if 0 days
            renderSalesChart();
            chartLoadingIndicator.style.display = 'none';
            return;
        }

        // Fetch historical data for growth comparison
        const endDateForGrowthComp = dateFns.subDays(new Date(), 1);
        const startDateForGrowthComp = dateFns.subDays(endDateForGrowthComp, futureDaysForForecast - 1);
        
        let actualTotalHistoricalSalesForGrowthComparison = 0;
        const historicalForGrowthSnapshot = await db.collection('penjualan')
            .where('user_id', '==', userId).where('item_id', '==', itemId)
            .where('tanggal', '>=', dateFns.format(startDateForGrowthComp, 'YYYY-MM-DD'))
            .where('tanggal', '<=', dateFns.format(endDateForGrowthComp, 'YYYY-MM-DD'))
            .get();

        historicalForGrowthSnapshot.forEach(doc => {
            actualTotalHistoricalSalesForGrowthComparison += Number(doc.data().jumlah_terjual);
        });

        // Fetch historical data for chart aggregation
        let daysOfRawHistoricalDataToFetchForChartAggregation;
        if (selectedAggregation === 'weekly') {
            daysOfRawHistoricalDataToFetchForChartAggregation = pointsToDisplayOnChart * 7;
        } else if (selectedAggregation === 'monthly') {
            daysOfRawHistoricalDataToFetchForChartAggregation = pointsToDisplayOnChart * 30;
        } else {
            daysOfRawHistoricalDataToFetchForChartAggregation = pointsToDisplayOnChart;
        }
        
        const historicalChartPeriodEndDate = dateFns.subDays(new Date(), 1);
        const historicalChartPeriodStartDate = dateFns.subDays(historicalChartPeriodEndDate, daysOfRawHistoricalDataToFetchForChartAggregation - 1);

        const historicalChartSnapshot = await db.collection('penjualan')
            .where('user_id', '==', userId).where('item_id', '==', itemId)
            .where('tanggal', '>=', dateFns.format(historicalChartPeriodStartDate, 'YYYY-MM-DD'))
            .where('tanggal', '<=', dateFns.format(historicalChartPeriodEndDate, 'YYYY-MM-DD'))
            .orderBy('tanggal', 'asc').get();
        
        const rawDailyHistoricalSalesForChart = {};
        historicalChartSnapshot.forEach(doc => {
            const data = doc.data();
            rawDailyHistoricalSalesForChart[data.tanggal] = (rawDailyHistoricalSalesForChart[data.tanggal] || 0.0) + Number(data.jumlah_terjual);
        });
        
        const aggregatedHistoricalSpots = aggregateData(
            rawDailyHistoricalSalesForChart, 
            dateFns.format(historicalChartPeriodEndDate, 'YYYY-MM-DD'), 
            pointsToDisplayOnChart, 
            selectedAggregation,
            true
        );
        historicalSpotsData = aggregatedHistoricalSpots;
        
        const lastHistoricalY = aggregatedHistoricalSpots.length > 0 ? aggregatedHistoricalSpots[aggregatedHistoricalSpots.length - 1].y : 0.0;
        const actualHistoricalPointsCount = aggregatedHistoricalSpots.length;

        // Prepare forecast data for aggregation
        const dailyForecastMap = {};
        const forecastApiStartDate = new Date(); 
        currentForecastData.forEach((val, i) => {
            const forecastDate = dateFns.addDays(forecastApiStartDate, i);
            const formattedKey = dateFns.format(forecastDate, 'YYYY-MM-DD'); // <<< INI YANG PENTING
            // console.log(`DEBUG dailyForecastMap KeyGen: Original Date: ${forecastDate}, Formatted Key: ${formattedKey}`); // <<< TAMBAHKAN INI
            dailyForecastMap[formattedKey] = val;
        });
        const forecastAggregationEndDate = dateFns.addDays(forecastApiStartDate, currentForecastData.length - 1);
        
        const aggregatedForecastSpotsRaw = aggregateData(
            dailyForecastMap,
            dateFns.format(forecastAggregationEndDate, 'YYYY-MM-DD'), // <<< PASTIKAN FORMAT INI BENAR
            pointsToDisplayOnChart,
            selectedAggregation,
            false
        );

        const finalForecastSpots = [];
        if (actualHistoricalPointsCount > 0) {
            finalForecastSpots.push({ x: actualHistoricalPointsCount - 1, y: lastHistoricalY });
        }
        
        aggregatedForecastSpotsRaw.forEach((spot, i) => {
            const currentForecastX = (actualHistoricalPointsCount > 0 ? actualHistoricalPointsCount - 1 : -1) + 1 + i;
            finalForecastSpots.push({ x: currentForecastX, y: spot.y });
        });
        forecastSpotsData = finalForecastSpots;
        
        const totalRawPredictedSales = currentForecastData.reduce((sum, val) => sum + Math.max(0, val), 0.0);

        let itemPrice = itemDetails[itemId]?.price || 0.0;
        if (itemPrice === 0.0 && itemId) {
            const lastSale = await db.collection('penjualan')
                                      .where('user_id', '==', userId)
                                      .where('item_id', '==', itemId)
                                      .orderBy('timestamp', 'desc') // Assuming you have a 'timestamp' field
                                      .limit(1).get();
            if (!lastSale.empty && lastSale.docs[0].data().harga_item !== undefined) {
                itemPrice = Number(lastSale.docs[0].data().harga_item);
                if (itemDetails[itemId]) {
                    itemDetails[itemId].price = itemPrice;
                }
            } else {
                showStyledSnackBar(`Harga untuk '${itemId}' tidak ditemukan. Perhitungan pendapatan mungkin tidak akurat.`, 'warning');
            }
        }
        const predictedRevenue = totalRawPredictedSales * itemPrice;

        let salesGrowth = 0;
        if (actualTotalHistoricalSalesForGrowthComparison > 0) {
          salesGrowth = ((totalRawPredictedSales - actualTotalHistoricalSalesForGrowthComparison) / actualTotalHistoricalSalesForGrowthComparison) * 100;
        } else if (totalRawPredictedSales > 0) {
          salesGrowth = Infinity; 
        }

        // Update UI
        resultsTitle.textContent = `Forecast Insights for "${itemId}"`;
        revenueSubtitle.textContent = `Dalam ${futureDaysForForecast} hari`;
        predictedRevenueSpan.textContent = formatCurrency(predictedRevenue);
        
        growthSubtitle.textContent = `vs ${futureDaysForForecast} hari lalu`;
        if (salesGrowth === Infinity) {
            salesGrowthPercentageSpan.textContent = "∞ %";
        } else {
            salesGrowthPercentageSpan.textContent = `${salesGrowth.toFixed(1)}%`;
        }

        if (salesGrowth === Infinity || salesGrowth >= 0) {
            salesGrowthIcon.className = 'material-icons-outlined text-success';
            salesGrowthIcon.textContent = 'show_chart';
            salesGrowthPercentageSpan.className = 'key-metric-value text-success';
        } else {
            salesGrowthIcon.className = 'material-icons-outlined text-danger';
            salesGrowthIcon.textContent = 'trending_down';
            salesGrowthPercentageSpan.className = 'key-metric-value text-danger';
        }

        resultsSection.style.display = 'block';
        errorDisplay.style.display = 'none'; // Hide any previous error
        placeholderInfo.style.display = 'none';
        
        renderSalesChart();
        chartLoadingIndicator.style.display = 'none';
    }

    function renderSalesChart() {
        const ctx = document.getElementById('sales-chart').getContext('2d');
        
        const noHistoricalData = historicalSpotsData.length === 0 || historicalSpotsData.every(s => s.y === 0 && s.x >= 0);
        const noForecastDataGenuine = forecastSpotsData.length === 0 || forecastSpotsData.every(s => s.y === 0 && s.x >= 0);

        // Prepare labels for X-axis
        let labels = [];
        const totalHistoricalPoints = historicalSpotsData.length;
        // Assuming forecastSpotsData starts with a connecting point that is the last historical point
        const totalPureForecastPoints = forecastSpotsData.length > 0 ? forecastSpotsData.length -1 : 0; 
        const totalPointsOnChart = totalHistoricalPoints + totalPureForecastPoints;

        const aggregationUnit = (document.querySelector('input[name="aggregation"]:checked')?.value || 'daily')[0].toUpperCase();

        for (let i = 0; i < totalPointsOnChart; i++) {
            if (i < totalHistoricalPoints) {
                 labels.push(`${aggregationUnit}-${totalHistoricalPoints - i}`); // H-n, H-n-1, ..., H-1
            } else {
                 labels.push(`${aggregationUnit}+${i - totalHistoricalPoints + 1}`); // F+1, F+2, ...
            }
        }
        if (totalHistoricalPoints > 0 && labels.length >= totalHistoricalPoints) { // Adjust last historical label
             labels[totalHistoricalPoints - 1] = `${aggregationUnit}-1`;
        }
         // Correctly format X-axis labels for Chart.js (it will use array index if not specified)
        // We use the 'x' from our spots {x,y} for data, labels array is for display ticks
        
        console.log("DEBUG: historicalSpotsData for chart:", JSON.stringify(historicalSpotsData));
        console.log("DEBUG: forecastSpotsData for chart:", JSON.stringify(forecastSpotsData));

        const datasets = [];
        if (historicalSpotsData.length > 0) {
            datasets.push({
                label: 'Historical Sales',
                data: historicalSpotsData, // Chart.js expects [{x: val, y: val}, ...] for scatter/line or just [y1, y2,...]
                borderColor: PRIMARY_BLUE_COLOR, // Or gradient
                backgroundColor: hexToRgba(PRIMARY_BLUE_COLOR, 0.3),
                tension: 0.35,
                fill: true,
                pointRadius: 0, // Hide dots
                pointHoverRadius: 5
            });
        }
        if (forecastSpotsData.length > 1) { // Need more than one point to draw a line
            datasets.push({
                label: 'Forecasted Sales',
                data: forecastSpotsData,
                borderColor: SUCCESS_COLOR,
                backgroundColor: hexToRgba(SUCCESS_COLOR, 0.25),
                tension: 0.35,
                borderDash: [6, 4],
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 5
            });
        }

        // Calculate min/max Y, or let Chart.js handle it
        let minY = 0;
        let maxY = 10; // Default if no data

        const allYValues = [...historicalSpotsData.map(s => s.y), ...forecastSpotsData.map(s => s.y)];
        if (allYValues.length > 0) {
            const finiteYValues = allYValues.filter(y => Number.isFinite(y));
            if (finiteYValues.length > 0) {
                minY = Math.min(...finiteYValues);
                maxY = Math.max(...finiteYValues);
            }
        }
        
        if (minY === maxY) {
            minY = Math.max(0, minY - (minY === 0 ? 0 : 5));
            maxY = maxY + 5;
            if (minY === 0 && maxY === 0) maxY = 10;
        } else {
            const range = maxY - minY;
            minY = Math.max(0, minY - range * 0.1);
            maxY = maxY + range * 0.1;
        }


        if (salesChartInstance) {
            salesChartInstance.destroy();
        }
        
        if(datasets.length === 0 && (noHistoricalData && noForecastDataGenuine && !currentForecastData && errorDisplay.style.display.includes('none'))) {
            // No data to show, and no error, typically initial state or after clearing.
            // Chart.js might show an empty canvas or error.
            // We can optionally display a message on the canvas.
            // For now, let it be empty.
        }


        salesChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                // labels: labels, // Let Chart.js generate numeric X-axis from data points {x,y}
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        type: 'linear', // Since our x is numeric index
                        grid: {
                            color: hexToRgba(SUBTLE_BORDER_COLOR, 0.3)
                        },
                        ticks: {
                            color: TEXT_WHITE70_COLOR,
                            stepSize: 1, // Show integer ticks
                            callback: function(value, index, ticks) {
                                // value is the x-coordinate of the spot
                                // Map this 'value' back to our H-n, F+m labels
                                const pointIndex = Math.round(value); // x should be integer
                                if (pointIndex < 0) return '';

                                let labelText = '';
                                const totalHist = historicalSpotsData.length;
                                // totalPureForecast is forecastSpotsData.length - 1 (if connecting point exists)
                                const totalPureFcst = forecastSpotsData.length > 0 ? forecastSpotsData.length -1 : 0;


                                if (pointIndex < totalHist) {
                                    labelText = `${aggregationUnit}-${totalHist - pointIndex}`;
                                } else if (pointIndex < totalHist + totalPureFcst) {
                                    labelText = `${aggregationUnit}+${pointIndex - totalHist + 1}`;
                                }
                                
                                // Logic to show fewer labels if too many points
                                const totalChartPoints = totalHist + totalPureFcst;
                                let labelInterval = 1;
                                if (totalChartPoints > 25) labelInterval = Math.round(totalChartPoints / 5);
                                else if (totalChartPoints > 15) labelInterval = Math.round(totalChartPoints / 4);
                                else if (totalChartPoints > 8) labelInterval = 2;

                                const isFirst = pointIndex === 0;
                                const isLast = pointIndex === (totalChartPoints -1);

                                if (!isFirst && !isLast && pointIndex % labelInterval !== 0) {
                                     if(isLast && (totalChartPoints -1) % labelInterval !== 0) { /* allow last */ }
                                     else return '';
                                }
                                return labelText;
                            }
                        }
                    },
                    y: {
                        min: minY,
                        max: maxY,
                        grid: {
                            color: hexToRgba(SUBTLE_BORDER_COLOR, 0.3)
                        },
                        ticks: {
                            color: TEXT_WHITE70_COLOR,
                            // Chart.js will auto-calculate nice tick values
                            callback: function(value, index, values) {
                                if (value >= 1000000) return (value / 1000000).toFixed(value % 1000000 !== 0 ? 1:0) + 'M';
                                if (value >= 1000) return (value / 1000).toFixed(value % 1000 !== 0 ? 1:0) + 'K';
                                return value.toString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Using custom legend
                    },
                    tooltip: {
                        backgroundColor: hexToRgba(CARD_LIGHT_BACKGROUND_COLOR, 0.98),
                        titleColor: TEXT_WHITE_COLOR,
                        bodyColor: TEXT_WHITE_COLOR,
                        borderColor: hexToRgba(SUBTLE_BORDER_COLOR, 0.8),
                        borderWidth: 1.2,
                        padding: {x: 10, y: 8},
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toFixed(context.parsed.y % 1 === 0 ? 0 : 1) + ' sales';
                                }
                                return label;
                            },
                             labelColor: function(context) {
                                return {
                                    borderColor: context.dataset.borderColor,
                                    backgroundColor: context.dataset.borderColor,
                                    borderWidth: 2,
                                    // borderDash: [2, 2], // if you want dashed box
                                    borderRadius: 2,
                                };
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    }
    
    function hexToRgba(hex, alpha) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    // --- EVENT LISTENERS ---
    document.addEventListener('DOMContentLoaded', () => {
        auth.onAuthStateChanged(user => {
            if (user) {
                currentUser = user;
                fetchEligibleItems(user.uid);
                placeholderInfo.style.display = 'block'; // Show initial placeholder
                resultsSection.style.display = 'none';
                errorDisplay.style.display = 'none';
                loadingIndicator.style.display = 'none';
                renderSalesChart(); // Initialize an empty chart
            } else {
                currentUser = null;
                // Redirect to login if not on login page
                if (window.location.pathname !== '/login') { // Adjust path if needed
                    window.location.href = '{{ route("login") }}';
                }
            }
        });

        itemSelect.addEventListener('change', () => {
            if (itemSelect.value) {
                predictButton.disabled = false;
            } else {
                predictButton.disabled = true;
            }
            // Clear previous results if item changes
            resultsSection.style.display = 'none';
            currentForecastData = null;
            historicalSpotsData = [];
            forecastSpotsData = [];
            if (salesChartInstance) salesChartInstance.destroy(); salesChartInstance = null; // Clear chart too
            placeholderInfo.style.display = 'block';
            errorDisplay.style.display = 'none';
            renderSalesChart();
        });

        timeRangeSelect.addEventListener('change', () => {
            if (currentForecastData) { // If a prediction was already made
                showStyledSnackBar("Periode prediksi diubah. Silahkan prediksi ulang.", 'warning');
                resultsSection.style.display = 'none';
                currentForecastData = null; // Invalidate old forecast
                if (salesChartInstance) salesChartInstance.destroy(); salesChartInstance = null;
                placeholderInfo.style.display = 'block';
                errorDisplay.style.display = 'none';
                renderSalesChart();
            }
        });

        document.querySelectorAll('input[name="aggregation"]').forEach(radio => {
            radio.addEventListener('change', (event) => {
                if (currentForecastData && currentUser && itemSelect.value) {
                    // Re-process and display results with new aggregation
                    // No need to call API again
                    processAndDisplayResults(currentUser.uid, itemSelect.value, parseInt(timeRangeSelect.value))
                        .catch(e => {
                            console.error("Error re-aggregating chart:", e);
                            showStyledSnackBar("Error re-aggregating chart: " + e.toString(), 'error');
                        });
                }
            });
        });

        predictButton.addEventListener('click', generatePrediction);
    });

</script>

@endsection