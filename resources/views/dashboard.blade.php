{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard BizzGrow')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 text-gray-200">

    <header class="flex items-center justify-between mb-10">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/avatar-default.png') }}" alt="User Avatar"
                 class="w-14 h-14 rounded-full border-2 border-blue-500 object-cover" id="dashboard-user-photo">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white" id="dashboard-user-name">Memuat nama...</h1>
                <p class="text-sm text-gray-400">Selamat datang kembali!</p>
            </div>
        </div>
    </header>

    <div id="styledSnackBar" class="snackbar">Pesan Notifikasi</div>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
        <div class="info-card-gradient p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between mb-3"> <h3 class="font-semibold text-lg">Total Pendapatan</h3> <div class="p-2 bg-white/20 rounded-full"> <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> </div> </div>
            <p class="text-3xl font-bold" id="total-pendapatan">Rp 0</p> <p class="text-sm opacity-80 mt-1">Dari semua penjualan</p>
        </div>
        <div class="info-card-gradient p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between mb-3"> <h3 class="font-semibold text-lg">Produk Terjual</h3> <div class="p-2 bg-white/20 rounded-full"> <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg> </div> </div>
            <p class="text-3xl font-bold" id="total-produk">0</p> <p class="text-sm opacity-80 mt-1">Dari semua penjualan</p>
        </div>
        <div class="info-card-dark p-6 rounded-xl shadow-lg text-white">
            <div class="flex items-center justify-between mb-3"> <h3 class="font-semibold text-lg">Pendapatan Hari Ini</h3> <div class="p-2 bg-gray-600 rounded-full"> <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg> </div> </div>
            <p class="text-3xl font-bold text-green-400" id="total-pendapatan-hari-ini">Rp 0</p>
        </div>
        <div class="info-card-dark p-6 rounded-xl shadow-lg text-white">
             <div class="flex items-center justify-between mb-3"> <h3 class="font-semibold text-lg">Produk Terjual Hari Ini</h3> <div class="p-2 bg-gray-600 rounded-full"> <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg> </div> </div>
            <p class="text-3xl font-bold text-yellow-400" id="total-produk-hari-ini">0</p>
        </div>
    </section>

    <section class="bg-gray-800 shadow-xl rounded-xl p-6 md:p-8 mb-10">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h2 class="text-xl md:text-2xl font-semibold text-white mb-3 sm:mb-0">Statistik Pertumbuhan</h2>
            <div class="period-selector inline-flex rounded-lg shadow-sm" role="group">
                <button type="button" id="btn-mingguan" data-period="mingguan"
                        class="px-5 py-2.5 text-sm font-medium transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500 rounded-l-lg">
                    Mingguan
                </button>
                <button type="button" id="btn-bulanan" data-period="bulanan"
                        class="px-5 py-2.5 text-sm font-medium transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500 rounded-r-lg border-l border-gray-600">
                    Bulanan
                </button>
            </div>
        </div>
        <div id="growth-stats-loader" class="loading-spinner-container" style="display: none;"><div class="loader-sm"></div> <span class="ml-3 text-gray-400">Memuat statistik...</span></div>
        <div id="growth-stats-content" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-700/50 p-5 rounded-lg"> <div class="flex items-center text-gray-400 mb-1"> <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9z" /></svg> <h4 class="text-sm font-medium uppercase tracking-wider">Pendapatan</h4> </div> <p class="text-2xl font-bold text-white" id="current-period-revenue">Rp 0</p> <p class="text-xs text-gray-500" id="current-period-label">7 hari terakhir</p> </div>
            <div class="bg-gray-700/50 p-5 rounded-lg"> <div class="flex items-center text-gray-400 mb-1"> <svg xmlns="http://www.w3.org/2000/svg" id="growth-icon-svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg> <h4 class="text-sm font-medium uppercase tracking-wider">Pertumbuhan Penjualan</h4> </div> <p class="text-2xl font-bold text-green-500" id="sales-growth-percentage">0.0%</p> <p class="text-xs text-gray-500" id="comparison-period-label">dibandingkan 7 hari sebelumnya</p> </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-1 xl:grid-cols-2 gap-8 lg:gap-12 mb-10">
        <div class="bg-gray-800 shadow-xl rounded-xl p-6 md:p-8">
            <div class="flex items-center justify-center mb-4 border-b border-gray-700 pb-3">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                <h2 class="text-xl md:text-2xl font-semibold text-white">Popularitas Produk</h2>
            </div>
            <div id="pie-chart-loader" class="loading-spinner-container">
                 <div class="loader-sm"></div> <span class="ml-3 text-gray-400">Memuat chart...</span>
            </div>
            <div class="chart-container-wrapper" id="pieChartWrapper" style="display: none;">
                <div class="pie-chart-canvas-container"> <canvas id="productSalesPieChart"></canvas> </div>
                <div class="pie-chart-legend-container" id="pieChartLegend"></div>
            </div>
            <p id="pie-chart-empty" class="text-center text-gray-500 py-10" style="display: none;">Belum ada data penjualan produk.</p>
        </div>

        <div class="bg-gray-800 shadow-xl rounded-xl p-6 md:p-8 relative">
            <div class="chart-title-overlay">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <h2 class="text-lg md:text-xl font-semibold text-gray-300">Tren Pendapatan</h2>
            </div>
            <div id="line-chart-loader" class="loading-spinner-container pt-10">
                 <div class="loader-sm"></div> <span class="ml-3 text-gray-400">Memuat chart...</span>
            </div>
            <div class="chart-container pt-10" id="revenueLineChartContainer" style="display: none;">
                <canvas id="revenueTrendLineChart"></canvas>
            </div>
             <p id="line-chart-empty" class="text-center text-gray-500 py-10 pt-16" style="display: none;">Belum ada data pendapatan.</p>
        </div>
    </section>

    <section class="bg-gray-800 shadow-xl rounded-xl p-6 md:p-8">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 border-b border-gray-700 pb-3">
            <div class="flex items-center mb-3 sm:mb-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                </svg>
                <h2 class="text-xl md:text-2xl font-semibold text-white">Tren Penjualan per Produk (7 Hari)</h2>
            </div>
            <div class="w-full sm:w-auto sm:max-w-xs md:max-w-sm">
                <select id="item-selector-dropdown" class="block w-full px-4 py-2.5 text-sm text-gray-200 bg-gray-700 border border-gray-600 rounded-lg 
                           focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 
                           placeholder-gray-400 custom-select-arrow-dark">
                    <option value="">Pilih Produk...</option>
                </select>
            </div>
        </div>
        <div id="item-line-chart-loader" class="loading-spinner-container">
             <div class="loader-sm"></div> <span class="ml-3 text-gray-400">Memuat chart...</span>
        </div>
        <div class="item-chart-container" id="itemLineChartContainer" style="display: none;">
            <canvas id="itemSalesTrendLineChart"></canvas>
        </div>
        <p id="item-line-chart-empty" class="text-center text-gray-500 py-10" style="display: none;">Pilih produk untuk melihat tren penjualannya.</p>
    </section>
</div>
@endsection

@push('scripts')

<script>
    Chart.register(ChartDataLabels);

    const defaultUserPhoto = '{{ asset("images/avatar-default.png") }}';
    const { DateTime } = luxon;

    let productSalesPieChartInstance = null;
    let revenueTrendLineChartInstance = null;
    let itemSalesTrendLineChartInstance = null;

    // Fungsi Utilitas
    function showSnackBar(message, isError = false) {
        const styledSnackBar = document.getElementById('styledSnackBar');
        if (!styledSnackBar) return;
        styledSnackBar.textContent = message;
        styledSnackBar.className = 'snackbar show';
        if (isError) { styledSnackBar.classList.add('error'); }
        else { styledSnackBar.classList.add('success'); }
        if(styledSnackBar.timeoutId) clearTimeout(styledSnackBar.timeoutId);
        styledSnackBar.timeoutId = setTimeout(() => {
            styledSnackBar.className = 'snackbar';
        }, 4000);
    }
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(amount || 0);
    }
    function formatDateForFirestore(date) { // date adalah objek Luxon DateTime
        return date.toFormat('yyyy-MM-dd');
    }

    // Fungsi Fetch Data Utama
    function fetchDashboardUserData(user) {
        const dashboardUserNameSpan = document.getElementById('dashboard-user-name');
        const dashboardUserPhotoImg = document.getElementById('dashboard-user-photo');

        if (user) {
            db.collection("users").doc(user.uid).get().then((doc) => {
                let userName = user.displayName || user.email || "Pengguna";
                let userPhoto = user.photoURL || defaultUserPhoto;
                if (doc.exists) {
                    const userData = doc.data();
                    userName = userData.name || userName;
                    userPhoto = userData.photoUrl || userPhoto;
                }
                if (dashboardUserNameSpan) dashboardUserNameSpan.textContent = userName;
                if (dashboardUserPhotoImg) dashboardUserPhotoImg.src = userPhoto;
            }).catch(error => {
                console.error("Error fetching user data from Firestore:", error);
                if (dashboardUserNameSpan) dashboardUserNameSpan.textContent = user.displayName || user.email || "Pengguna";
                if (dashboardUserPhotoImg) dashboardUserPhotoImg.src = user.photoURL || defaultUserPhoto;
            });
        } else {
            if (dashboardUserNameSpan) dashboardUserNameSpan.textContent = "Pengguna Tamu";
            if (dashboardUserPhotoImg) dashboardUserPhotoImg.src = defaultUserPhoto;
        }
    }

    async function fetchSummarySalesData(userId) {
        const totalPendapatanEl = document.getElementById('total-pendapatan');
        const totalProdukEl = document.getElementById('total-produk');
        const totalPendapatanHariIniEl = document.getElementById('total-pendapatan-hari-ini');
        const totalProdukHariIniEl = document.getElementById('total-produk-hari-ini');

        const todayString = DateTime.now().toFormat('yyyy-MM-dd');
        let overallRevenue = 0;
        let overallProductsSold = 0;
        let todayRevenue = 0;
        let todayProductsSold = 0;
        const productSalesCounter = {};

        // Definisikan warna Pie Chart
        const PIE_CHART_COLORS = ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EAB308','#06B6D4','#EC4899','#6366F1','#84CC16','#D946EF'];

        try {
            const querySnapshot = await db.collection("penjualan")
                .where("user_id", "==", userId)
                .get();

            querySnapshot.forEach((doc) => {
                const data = doc.data();
                const harga = data.harga_item;
                const jumlah = data.jumlah_terjual;
                const itemId = data.item_id || "Lainnya";

                if (typeof harga === 'number' && typeof jumlah === 'number') {
                    const saleAmount = harga * jumlah;
                    overallRevenue += saleAmount;
                    overallProductsSold += jumlah;
                    productSalesCounter[itemId] = (productSalesCounter[itemId] || 0) + jumlah;
                    if (data.tanggal === todayString) {
                        todayRevenue += saleAmount;
                        todayProductsSold += jumlah;
                    }
                }
            });

            if (totalPendapatanEl) totalPendapatanEl.textContent = formatCurrency(overallRevenue);
            if (totalProdukEl) totalProdukEl.textContent = overallProductsSold.toLocaleString('id-ID');
            if (totalPendapatanHariIniEl) totalPendapatanHariIniEl.textContent = formatCurrency(todayRevenue);
            if (totalProdukHariIniEl) totalProdukHariIniEl.textContent = todayProductsSold.toLocaleString('id-ID');

            const sortedProductEntries = Object.entries(productSalesCounter).sort(([,a],[,b]) => b-a);
            let limitedProductSales = {};
            const itemColorsForChart = {};
            let colorIndex = 0;
            const legendData = []; // Untuk legenda kustom

            if (sortedProductEntries.length > 5) {
                let othersQuantity = 0;
                for (let i = 0; i < sortedProductEntries.length; i++) {
                    const [itemId, quantity] = sortedProductEntries[i];
                    if (i < 4) { // Top 4
                        limitedProductSales[itemId] = quantity;
                        itemColorsForChart[itemId] = PIE_CHART_COLORS[colorIndex % PIE_CHART_COLORS.length];
                        legendData.push({ id: itemId, name: itemId, quantity: quantity, color: itemColorsForChart[itemId] });
                        colorIndex++;
                    } else {
                        othersQuantity += quantity;
                    }
                }
                if (othersQuantity > 0) {
                    limitedProductSales['Lainnya'] = othersQuantity;
                    itemColorsForChart['Lainnya'] = PIE_CHART_COLORS[colorIndex % PIE_CHART_COLORS.length];
                    legendData.push({ id: 'Lainnya', name: 'Lainnya', quantity: othersQuantity, color: itemColorsForChart['Lainnya'] });
                }
            } else {
                sortedProductEntries.forEach(entry => {
                    const [itemId, quantity] = entry;
                    limitedProductSales[itemId] = quantity;
                    itemColorsForChart[itemId] = PIE_CHART_COLORS[colorIndex % PIE_CHART_COLORS.length];
                    legendData.push({ id: itemId, name: itemId, quantity: quantity, color: itemColorsForChart[itemId] });
                    colorIndex++;
                });
            }
            renderProductSalesPieChart(limitedProductSales, itemColorsForChart, legendData, overallProductsSold);

        } catch (error) {
            console.error("[ERROR] Gagal mengambil data penjualan summary:", error);
            showSnackBar("Gagal memuat data ringkasan penjualan.", true);
            if (totalPendapatanEl) totalPendapatanEl.textContent = formatCurrency(0);
            if (totalProdukEl) totalProdukEl.textContent = "0";
            if (totalPendapatanHariIniEl) totalPendapatanHariIniEl.textContent = formatCurrency(0);
            if (totalProdukHariIniEl) totalProdukHariIniEl.textContent = "0";
            renderProductSalesPieChart({}, {}, [], 0);
        }
    }

    async function fetchRevenueForPeriod(userId, startDate, endDate) {
        const startDateString = formatDateForFirestore(startDate);
        const endDateString = formatDateForFirestore(endDate);
        let totalRevenue = 0;
        const dailyRevenue = {};
        let currentDateIter = startDate;
        while(currentDateIter <= endDate) {
            dailyRevenue[formatDateForFirestore(currentDateIter)] = 0;
            currentDateIter = currentDateIter.plus({ days: 1 });
        }
        try {
            const snapshot = await db.collection('penjualan')
                .where('user_id', '==', userId)
                .where('tanggal', '>=', startDateString)
                .where('tanggal', '<=', endDateString)
                .get();
            snapshot.docs.forEach(doc => {
                const data = doc.data();
                const harga = data.harga_item;
                const jumlah = data.jumlah_terjual;
                const tanggal = data.tanggal;
                if (typeof harga === 'number' && typeof jumlah === 'number' && tanggal && dailyRevenue.hasOwnProperty(tanggal)) {
                    totalRevenue += harga * jumlah;
                    dailyRevenue[tanggal] += harga * jumlah;
                }
            });
            return { totalRevenue, dailyRevenue };
        } catch (e) {
            console.error(`Error fetching revenue for ${startDateString} - ${endDateString}:`, e);
            return { totalRevenue: 0, dailyRevenue };
        }
    }

    async function updateRevenueAndGrowthStats(userId, period = 'mingguan') {
        const growthStatsLoader = document.getElementById('growth-stats-loader');
        const growthStatsContent = document.getElementById('growth-stats-content');
        const currentPeriodRevenueEl = document.getElementById('current-period-revenue');
        const salesGrowthPercentageEl = document.getElementById('sales-growth-percentage');
        const currentPeriodLabelEl = document.getElementById('current-period-label');
        const comparisonPeriodLabelEl = document.getElementById('comparison-period-label');
        const growthIconSvgEl = document.getElementById('growth-icon-svg');

        if (growthStatsLoader) growthStatsLoader.style.display = 'flex';
        if (growthStatsContent) growthStatsContent.style.display = 'none';

        const now = DateTime.now();
        let currentPeriodStartDate, currentPeriodEndDate = now;
        let prevPeriodStartDate, prevPeriodEndDate;

        if (period === 'mingguan') {
            currentPeriodStartDate = now.minus({ days: 6 }).startOf('day');
            prevPeriodEndDate = currentPeriodStartDate.minus({ days: 1 }).endOf('day');
            prevPeriodStartDate = prevPeriodEndDate.minus({ days: 6 }).startOf('day');
            if (currentPeriodLabelEl) currentPeriodLabelEl.textContent = '7 hari terakhir';
            if (comparisonPeriodLabelEl) comparisonPeriodLabelEl.textContent = 'dibandingkan 7 hari sebelumnya';
        } else {
            currentPeriodStartDate = now.minus({ days: 29 }).startOf('day');
            prevPeriodEndDate = currentPeriodStartDate.minus({ days: 1 }).endOf('day');
            prevPeriodStartDate = prevPeriodEndDate.minus({ days: 29 }).startOf('day');
            if (currentPeriodLabelEl) currentPeriodLabelEl.textContent = '30 hari terakhir';
            if (comparisonPeriodLabelEl) comparisonPeriodLabelEl.textContent = 'dibandingkan 30 hari sebelumnya';
        }

        try {
            const [currentPeriodTotalData, prevPeriodTotalData] = await Promise.all([
                fetchRevenueForPeriod(userId, currentPeriodStartDate, currentPeriodEndDate),
                fetchRevenueForPeriod(userId, prevPeriodStartDate, prevPeriodEndDate)
            ]);
            const currentRevenue = currentPeriodTotalData.totalRevenue;
            const previousRevenue = prevPeriodTotalData.totalRevenue;
            let growthPercentage = 0.0;
            if (previousRevenue > 0) { growthPercentage = ((currentRevenue - previousRevenue) / previousRevenue) * 100.0; }
            else if (currentRevenue > 0 && previousRevenue === 0) { growthPercentage = 100.0; }

            if (currentPeriodRevenueEl) currentPeriodRevenueEl.textContent = formatCurrency(currentRevenue);
            if (salesGrowthPercentageEl) {
                salesGrowthPercentageEl.textContent = `${growthPercentage >= 0 ? '+' : ''}${growthPercentage.toFixed(1)}%`;
                salesGrowthPercentageEl.classList.toggle('text-green-500', growthPercentage >= 0);
                salesGrowthPercentageEl.classList.toggle('text-red-500', growthPercentage < 0);
            }
             if (growthIconSvgEl) {
                growthIconSvgEl.classList.toggle('text-green-500', growthPercentage >= 0);
                growthIconSvgEl.classList.toggle('text-red-500', growthPercentage < 0);
                growthIconSvgEl.innerHTML = growthPercentage >= 0 ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />';
            }
            const chartDataForLine = prepareLineChartData(currentPeriodTotalData.dailyRevenue, period, currentPeriodStartDate, currentPeriodEndDate);
            renderRevenueTrendLineChart(chartDataForLine.spots, chartDataForLine.labels, chartDataForLine.maxY, period);
        } catch (error) {
            console.error("Error updating revenue/growth stats:", error);
            if (currentPeriodRevenueEl) currentPeriodRevenueEl.textContent = formatCurrency(0);
            if (salesGrowthPercentageEl) salesGrowthPercentageEl.textContent = "0.0%";
            showSnackBar("Gagal memuat statistik pertumbuhan.", true);
            renderRevenueTrendLineChart([], [], 100, period);
        } finally {
            if (growthStatsLoader) growthStatsLoader.style.display = 'none';
            if (growthStatsContent) growthStatsContent.style.display = 'grid';
        }
    }

    function prepareLineChartData(dailyRevenueMap, period, chartStartDate, chartEndDate) {
        const spots = []; const labels = []; let maxY = 0;
        if (period === 'mingguan') {
            for (let i = 0; i < 7; i++) {
                const date = chartStartDate.plus({ days: i });
                const dateKey = formatDateForFirestore(date);
                const value = dailyRevenueMap[dateKey] || 0;
                spots.push(value); labels.push(i === 6 ? 'Kini' : `H-${6 - i}`);
                if (value > maxY) maxY = value;
            }
        } else {
            for (let i = 0; i < 4; i++) {
                const targetMonthLuxon = chartEndDate.minus({ months: 3 - i });
                const monthKey = targetMonthLuxon.toFormat('yyyy-MM');
                let monthRevenue = 0;
                for (const dateStr in dailyRevenueMap) { if (dateStr.startsWith(monthKey)) { monthRevenue += dailyRevenueMap[dateStr]; }}
                spots.push(monthRevenue); labels.push(targetMonthLuxon.toFormat('MMM', { locale: 'id' }));
                if (monthRevenue > maxY) maxY = monthRevenue;
            }
        }
        maxY = maxY === 0 ? 100 : maxY * 1.25;
        return { spots, labels, maxY };
    }

    // --- FUNGSI BARU DAN MODIFIKASI UNTUK ITEM SALES TREND CHART ---
    async function fetchUniqueItemIds(userId) {
        const itemSelector = document.getElementById('item-selector-dropdown');
        if (!itemSelector) return null;
        itemSelector.innerHTML = '<option value="">Memuat produk...</option>';
        try {
            const snapshot = await db.collection('penjualan').where('user_id', '==', userId).get();
            const itemIds = new Set();
            snapshot.docs.forEach(doc => { const itemId = doc.data().item_id; if (itemId) itemIds.add(itemId); });
            const sortedItemIds = Array.from(itemIds).sort((a, b) => a.toLowerCase().localeCompare(b.toLowerCase())); // Sort case-insensitive
            itemSelector.innerHTML = '<option value="">Pilih Produk...</option>';
            if (sortedItemIds.length === 0) {
                 itemSelector.innerHTML = '<option value="">Belum ada produk</option>';
                 document.getElementById('item-line-chart-loader').style.display = 'none';
                 document.getElementById('itemLineChartContainer').style.display = 'none';
                 document.getElementById('item-line-chart-empty').textContent = 'Belum ada data produk untuk ditampilkan.';
                 document.getElementById('item-line-chart-empty').style.display = 'block';
                 return null;
            }
            sortedItemIds.forEach(id => { const option = document.createElement('option'); option.value = id; option.textContent = id; itemSelector.appendChild(option); });
            return sortedItemIds.length > 0 ? sortedItemIds[0] : null;
        } catch (error) {
            console.error("Error fetching unique item IDs:", error);
            showSnackBar("Gagal memuat daftar produk.", true);
            itemSelector.innerHTML = '<option value="">Gagal memuat</option>';
            return null;
        }
    }

    async function fetchItemSalesTrendData(userId, selectedItemId) {
        const itemChartLoader = document.getElementById('item-line-chart-loader');
        const itemChartContainer = document.getElementById('itemLineChartContainer');
        const itemChartEmpty = document.getElementById('item-line-chart-empty');

        if (!selectedItemId) {
            if (itemChartLoader) itemChartLoader.style.display = 'none';
            if (itemChartContainer) itemChartContainer.style.display = 'none';
            if (itemChartEmpty) { itemChartEmpty.textContent = 'Pilih produk untuk melihat tren penjualannya.'; itemChartEmpty.style.display = 'block';}
            if(itemSalesTrendLineChartInstance) { itemSalesTrendLineChartInstance.destroy(); itemSalesTrendLineChartInstance = null; }
            return { spots: [], labels: [], maxY: 10 };
        }
        if (itemChartLoader) itemChartLoader.style.display = 'flex';
        if (itemChartContainer) itemChartContainer.style.display = 'none';
        if (itemChartEmpty) itemChartEmpty.style.display = 'none';

        const now = DateTime.now(); const expectedDataPoints = 7;
        const firstDateInPeriod = now.minus({ days: expectedDataPoints - 1 }).startOf('day');
        const dailyItemSales = {};
        for (let i = 0; i < expectedDataPoints; i++) { const date = now.minus({ days: (expectedDataPoints - 1) - i }); dailyItemSales[formatDateForFirestore(date)] = 0; }
        let maxVal = 0;

        try {
            const snapshot = await db.collection('penjualan')
                .where('user_id', '==', userId).where('item_id', '==', selectedItemId)
                .where('tanggal', '>=', formatDateForFirestore(firstDateInPeriod))
                .where('tanggal', '<=', formatDateForFirestore(now)).get();
            snapshot.docs.forEach(doc => {
                const data = doc.data(); const tanggalStr = data.tanggal; const jumlah = data.jumlah_terjual;
                if (tanggalStr && typeof jumlah === 'number' && dailyItemSales.hasOwnProperty(tanggalStr)) {
                    dailyItemSales[tanggalStr] += jumlah;
                    if (dailyItemSales[tanggalStr] > maxVal) maxVal = dailyItemSales[tanggalStr];
                }
            });
            const spots = []; const labels = [];
            for (let i = 0; i < expectedDataPoints; i++) {
                const date = now.minus({ days: (expectedDataPoints - 1) - i });
                const key = formatDateForFirestore(date);
                spots.push(dailyItemSales[key] || 0);
                labels.push(i === (expectedDataPoints - 1) ? 'Kini' : `H-${(expectedDataPoints - 1) - i}`);
            }
            maxY = maxVal === 0 ? 10 : Math.ceil(maxVal * 1.25 / 5) * 5; if (maxY < 10 && maxVal >0) maxY = 10;
            return { spots, labels, maxY };
        } catch (error) {
            console.error(`Error fetching sales data for item ${selectedItemId}:`, error);
            showSnackBar(`Gagal memuat data untuk produk ${selectedItemId}.`, true);
            return { spots: [], labels: [], maxY: 10 };
        }
    }

    // --- Fungsi Render Chart ---
    function generatePieChartLegendHTML(legendData, totalOverallSales) {
        const legendContainer = document.getElementById('pieChartLegend');
        if (!legendContainer) return;
        legendContainer.innerHTML = ''; // Kosongkan legenda lama

        if (legendData.length === 0) return;

        legendData.forEach(item => {
            const percentage = totalOverallSales > 0 ? (item.quantity / totalOverallSales * 100).toFixed(1) : 0;
            const itemHTML = `
                <div class="legend-item">
                    <div class="legend-color-box" style="background-color: ${item.color};"></div>
                    <div class="legend-text-container">
                        <div class="legend-text-main">${item.name}</div>
                        <div class="legend-text-sub">${item.quantity.toLocaleString('id-ID')} terjual (${percentage}%)</div>
                    </div>
                </div>
            `;
            legendContainer.innerHTML += itemHTML;
        });
    }

    function renderProductSalesPieChart(limitedProductSales, itemColors, legendData, totalOverallSales) {
        const pieChartLoader = document.getElementById('pie-chart-loader');
        const pieChartWrapper = document.getElementById('pieChartWrapper'); // Wrapper baru untuk chart dan legenda
        const pieChartContainer = document.getElementById('pieChartContainer'); // Kontainer canvas lama, sekarang jadi bagian dari wrapper
        const pieChartEmpty = document.getElementById('pie-chart-empty');
        const ctx = document.getElementById('productSalesPieChart')?.getContext('2d');

        if (pieChartLoader) pieChartLoader.style.display = 'none';
        if (!ctx) {
            if(pieChartWrapper) pieChartWrapper.style.display = 'none';
            if(pieChartEmpty) pieChartEmpty.style.display = 'block';
            return;
        }
        const labels = Object.keys(limitedProductSales);
        const data = Object.values(limitedProductSales);

        if (labels.length === 0) {
            if(pieChartWrapper) pieChartWrapper.style.display = 'none';
            if(pieChartEmpty) pieChartEmpty.style.display = 'block';
            if (productSalesPieChartInstance) { productSalesPieChartInstance.destroy(); productSalesPieChartInstance = null; }
            generatePieChartLegendHTML([], 0); // Kosongkan legenda
            return;
        }
        if(pieChartWrapper) pieChartWrapper.style.display = 'flex'; // Ubah ke flex untuk layout Row
        if(pieChartEmpty) pieChartEmpty.style.display = 'none';

        const backgroundColors = labels.map(label => itemColors[label] || '#CCCCCC');

        if (productSalesPieChartInstance) productSalesPieChartInstance.destroy();
        productSalesPieChartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Produk Terjual', data: data,
                    backgroundColor: backgroundColors,
                    borderColor: '#1f2937', // bg-gray-800, untuk border antar slice
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, // Sembunyikan legenda default Chart.js
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? (value / total * 100) : 0;
                            if (percentage < 7) return ''; // Sembunyikan jika < 7%
                            return percentage.toFixed(0) + '%';
                        },
                        color: '#fff',
                        font: { weight: 'bold', size: '10px', family: 'Inter' },
                        anchor: 'end',
                        align: 'start',
                        offset: -10, // Sesuaikan untuk posisi dalam slice
                        borderRadius: 4,
                        backgroundColor: (context) => { // Latar belakang label sedikit transparan dari warna slicenya
                            const bgColor = context.dataset.backgroundColor[context.dataIndex];
                            return Chart.helpers.color(bgColor).alpha(0.5).rgbString();
                        },
                        padding: 4
                    }
                }
            }
        });
        generatePieChartLegendHTML(legendData, totalOverallSales);
    }

    function renderRevenueTrendLineChart(spots, labels, maxY, period) {
        const lineChartLoader = document.getElementById('line-chart-loader');
        const lineChartContainer = document.getElementById('revenueLineChartContainer');
        const lineChartEmpty = document.getElementById('line-chart-empty');
        const ctx = document.getElementById('revenueTrendLineChart')?.getContext('2d');

        if (lineChartLoader) lineChartLoader.style.display = 'none';
        if (!ctx) {
            if(lineChartContainer) lineChartContainer.style.display = 'none';
            if(lineChartEmpty) lineChartEmpty.style.display = 'block'; return;
        }
        if (!spots || spots.length === 0 || spots.every(s=>s===0)) {
            if(lineChartContainer) lineChartContainer.style.display = 'none';
            if(lineChartEmpty) lineChartEmpty.style.display = 'block';
            if (revenueTrendLineChartInstance) { revenueTrendLineChartInstance.destroy(); revenueTrendLineChartInstance = null; }
            return;
        }
        if(lineChartContainer) lineChartContainer.style.display = 'block';
        if(lineChartEmpty) lineChartEmpty.style.display = 'none';

        const chartLineColor = '#3b82f6'; const gradientFillStart = 'rgba(59, 130, 246, 0.5)';
        const gradientFillEnd = 'rgba(59, 130, 246, 0.05)'; const gridColor = 'rgba(75, 85, 99, 0.3)';
        const axisTextColor = '#9ca3af';

        if (revenueTrendLineChartInstance) {
            revenueTrendLineChartInstance.data.labels = labels;
            revenueTrendLineChartInstance.data.datasets[0].data = spots;
            revenueTrendLineChartInstance.options.scales.y.max = maxY;
            revenueTrendLineChartInstance.options.scales.y.ticks.stepSize = maxY > 0 ? Math.ceil(maxY / 4 / 1000) * 1000 : 25;
            if (period === 'mingguan') { revenueTrendLineChartInstance.options.scales.x.ticks.callback = (v, i) => (i === 0 || i === 2 || i === 4 || i === 6) ? labels[i] : null;
            } else { revenueTrendLineChartInstance.options.scales.x.ticks.callback = (v, i) => labels[i]; }
            revenueTrendLineChartInstance.update();
        } else {
            revenueTrendLineChartInstance = new Chart(ctx, {
                type: 'line', data: { labels: labels, datasets: [{
                    label: 'Pendapatan', data: spots, borderColor: chartLineColor,
                    backgroundColor: (c) => { const {ctx, chartArea} = c.chart; if (!chartArea) return null; const g = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top); g.addColorStop(0, gradientFillEnd); g.addColorStop(1, gradientFillStart); return g; },
                    tension: 0.4, fill: true, pointBackgroundColor: chartLineColor, pointBorderColor: '#1f2937', pointHoverBackgroundColor: '#fff', pointHoverBorderColor: chartLineColor, borderWidth: 2.5, pointRadius: 0, pointHoverRadius: 5
                }]},
                options: { responsive: true, maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: maxY, ticks: { color: axisTextColor, callback: (v) => { if (v >= 1e6) return (v / 1e6).toFixed(1) + 'Jt'; if (v >= 1e3) return (v / 1e3).toFixed(0) + 'Rb'; return v.toLocaleString('id-ID');}, stepSize: maxY > 0 ? Math.ceil(maxY / 4 / 1e3) * 1e3 : 25, padding: 5 }, grid: { color: gridColor, drawBorder: false } },
                        x: { ticks: { color: axisTextColor, callback: (v, i) => (period === 'mingguan' ? (i === 0 || i === 2 || i === 4 || i === 6) : true) ? labels[i] : null, maxRotation: 0, autoSkipPadding: 20 }, grid: { display: false } }
                    },
                    plugins: { legend: { display: false }, tooltip: {
                        mode: 'index', intersect: false, backgroundColor: 'rgba(31, 41, 55, 0.9)', titleColor: '#e5e7eb', bodyColor: '#d1d5db', borderColor: '#4b5563', borderWidth: 1, padding: 10, displayColors: false,
                        callbacks: { title: (ti) => `Periode: ${ti[0].label}`, label: (c) => `Pendapatan: ${formatCurrency(c.parsed.y)}` }
                    }},
                    interaction: { mode: 'nearest', axis: 'x', intersect: false }
                }
            });
        }
    }

    function renderItemSalesTrendLineChart(spots, labels, maxY) {
        const itemChartLoader = document.getElementById('item-line-chart-loader');
        const itemChartContainer = document.getElementById('itemLineChartContainer');
        const itemChartEmpty = document.getElementById('item-line-chart-empty');
        const ctx = document.getElementById('itemSalesTrendLineChart')?.getContext('2d');

        if (itemChartLoader) itemChartLoader.style.display = 'none';
        if (!ctx) {
            if(itemChartContainer) itemChartContainer.style.display = 'none';
            if(itemChartEmpty) { itemChartEmpty.textContent = 'Gagal memuat chart produk.'; itemChartEmpty.style.display = 'block';}
            return;
        }
        if (!spots || spots.length === 0 || spots.every(s => s === 0)) {
            if(itemChartContainer) itemChartContainer.style.display = 'none';
            if(itemChartEmpty) { itemChartEmpty.textContent = 'Belum ada penjualan untuk produk ini dalam 7 hari terakhir.'; itemChartEmpty.style.display = 'block';}
            if (itemSalesTrendLineChartInstance) { itemSalesTrendLineChartInstance.destroy(); itemSalesTrendLineChartInstance = null; }
            return;
        }
        if(itemChartContainer) itemChartContainer.style.display = 'block';
        if(itemChartEmpty) itemChartEmpty.style.display = 'none';

        const chartLineColor = '#8B5CF6'; const gradientFillStart = 'rgba(139, 92, 246, 0.5)';
        const gradientFillEnd = 'rgba(139, 92, 246, 0.05)'; const gridColor = 'rgba(75, 85, 99, 0.3)';
        const axisTextColor = '#9ca3af';

        if (itemSalesTrendLineChartInstance) {
            itemSalesTrendLineChartInstance.data.labels = labels;
            itemSalesTrendLineChartInstance.data.datasets[0].data = spots;
            itemSalesTrendLineChartInstance.options.scales.y.max = maxY;
            itemSalesTrendLineChartInstance.options.scales.y.ticks.stepSize = maxY > 0 ? Math.max(1, Math.ceil(maxY / 4)) : 1;
            itemSalesTrendLineChartInstance.options.scales.x.ticks.callback = (v, i) => (i === 0 || i === 2 || i === 4 || i === 6) ? labels[i] : null;
            itemSalesTrendLineChartInstance.update();
        } else {
            itemSalesTrendLineChartInstance = new Chart(ctx, {
                type: 'line', data: { labels: labels, datasets: [{
                    label: 'Jumlah Terjual', data: spots, borderColor: chartLineColor,
                    backgroundColor: (c) => { const {ctx, chartArea} = c.chart; if (!chartArea) return null; const g = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top); g.addColorStop(0, gradientFillEnd); g.addColorStop(1, gradientFillStart); return g; },
                    tension: 0.4, fill: true, pointBackgroundColor: chartLineColor, pointBorderColor: '#1f2937',
                    pointHoverBackgroundColor: '#fff', pointHoverBorderColor: chartLineColor, borderWidth: 2.5, pointRadius: 3, pointHoverRadius: 6
                }]},
                options: { responsive: true, maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: maxY, ticks: { color: axisTextColor, precision: 0, stepSize: maxY > 0 ? Math.max(1, Math.ceil(maxY / 4)) : 1, padding: 5 }, grid: { color: gridColor, drawBorder: false } },
                        x: { ticks: { color: axisTextColor, callback: (v, i) => (i === 0 || i === 2 || i === 4 || i === 6) ? labels[i] : null, maxRotation: 0, autoSkipPadding: 10 }, grid: { display: false } }
                    },
                    plugins: { legend: { display: false }, tooltip: {
                        mode: 'index', intersect: false, backgroundColor: 'rgba(31, 41, 55, 0.9)', titleColor: '#e5e7eb', bodyColor: '#d1d5db', borderColor: '#4b5563', borderWidth: 1, padding: 10, displayColors: false,
                        callbacks: { title: (ti) => `Periode: ${ti[0].label}`, label: (c) => `Terjual: ${c.parsed.y.toLocaleString('id-ID')}` }
                    }},
                    interaction: { mode: 'nearest', axis: 'x', intersect: false }
                }
            });
        }
    }

    // Event Listener dan Inisialisasi
    document.addEventListener('DOMContentLoaded', function() {
        auth.onAuthStateChanged(async (user) => {
            if (user) {
                fetchDashboardUserData(user);
                await fetchSummarySalesData(user.uid);
                await updateRevenueAndGrowthStats(user.uid, 'mingguan');
                if (btnMingguan) {
                    styleActivePeriodButton(btnMingguan); 
                }
                const defaultSelectedItem = await fetchUniqueItemIds(user.uid);
                if (defaultSelectedItem) {
                    const itemTrendData = await fetchItemSalesTrendData(user.uid, defaultSelectedItem);
                    renderItemSalesTrendLineChart(itemTrendData.spots, itemTrendData.labels, itemTrendData.maxY);
                } else {
                     renderItemSalesTrendLineChart([], [], 10, 'mingguan'); // Pass period
                }
            } else { window.location.href = '{{ route("login") }}'; }
        });

        const btnMingguan = document.getElementById('btn-mingguan');
        const btnBulanan = document.getElementById('btn-bulanan');
        function styleActivePeriodButton(activeButton) {
            [btnMingguan, btnBulanan].forEach(btn => {
                if (btn) {
                    btn.classList.remove('active', 'bg-blue-600', 'text-white', 'shadow-md');
                    // Tambahkan kembali kelas dasar jika belum ada (untuk memastikan)
                    if (!btn.classList.contains('bg-gray-700')) {
                        btn.classList.add('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
                    }
                }
            });
            if (activeButton) {
                activeButton.classList.add('active', 'bg-blue-600', 'text-white', 'shadow-md');
                activeButton.classList.remove('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
            }
        }
        function handlePeriodButtonClick(clickedButton, period) {
            const user = auth.currentUser;
            if (!user) {
                showSnackBar("Sesi berakhir, silakan login kembali.", true);
                // Anda mungkin ingin redirect ke login di sini juga
                return;
            }
            styleActivePeriodButton(clickedButton); // Update visual tombol
            updateRevenueAndGrowthStats(user.uid, period); // Ambil data baru
        }

        if (btnMingguan) {
            btnMingguan.addEventListener('click', function() {
                handlePeriodButtonClick(this, 'mingguan');
            });
        }
        if (btnBulanan) {
            btnBulanan.addEventListener('click', function() {
                handlePeriodButtonClick(this, 'bulanan');
            });
        }
        const itemSelectorDropdown = document.getElementById('item-selector-dropdown');
        if (itemSelectorDropdown) {
            itemSelectorDropdown.addEventListener('change', async function() {
                const selectedItemId = this.value; const user = auth.currentUser;
                if (user && selectedItemId) {
                    const itemTrendData = await fetchItemSalesTrendData(user.uid, selectedItemId);
                    renderItemSalesTrendLineChart(itemTrendData.spots, itemTrendData.labels, itemTrendData.maxY);
                } else if (user && !selectedItemId) {
                    renderItemSalesTrendLineChart([], [], 10); // Kosongkan chart item
                     document.getElementById('item-line-chart-empty').textContent = 'Pilih produk untuk melihat tren penjualannya.';
                     document.getElementById('item-line-chart-empty').style.display = 'block';
                     document.getElementById('itemLineChartContainer').style.display = 'none';
                }
            });
        }
    });
</script>
@endpush