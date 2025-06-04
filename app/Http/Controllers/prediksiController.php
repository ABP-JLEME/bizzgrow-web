<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
// Use the NumberFormatter for currency if 'intl' extension is enabled in PHP
use NumberFormatter;

class prediksiController extends Controller
{

    public function showPrediksiPage()
    {
        // Asumsi Anda mendapatkan data ini dari database, sesi, atau API
        $isLoadingItems = false; // atau true jika sedang fetching
        $selectedItemId = session('selected_item_id_prediksi'); // contoh ambil dari sesi
        $selectedItemName = null; // Anda mungkin perlu fetch nama item berdasarkan ID

        // Contoh item list (biasanya dari DB)
        $itemDropdownList = [
            // ['value' => null, 'label' => 'Error loading items'], // Jika error
            // ['value' => null, 'label' => 'Produk kurang dari 120 data'], // Jika kosong
            ['value' => 'PROD001', 'label' => 'Produk A (Stok: 150)'],
            ['value' => 'PROD002', 'label' => 'Produk B (Stok: 200)'],
        ];
        if ($selectedItemId && empty($selectedItemName)) {
            foreach ($itemDropdownList as $item) {
                if ($item['value'] == $selectedItemId) {
                    $selectedItemName = explode(' (', $item['label'])[0];
                    break;
                }
            }
        }


        $timeRangeOptions = [
            ['label' => '1 Minggu', 'days' => 7],
            ['label' => '2 Minggu', 'days' => 14],
            // ... (sesuai _timeRangeOptions di Flutter)
            ['label' => '3 Bulan', 'days' => 90],
        ];
        $selectedTimeRangeDays = session('selected_time_range_days_prediksi', 7);

        $dataPointOptions = ['daily', 'weekly', 'monthly'];
        $selectedDataPointAggregation = session('selected_data_point_aggregation_prediksi', 'daily');

        $isPredicting = session('is_predicting_process', false); // Status proses prediksi
        $globalErrorMessage = session('global_error_message_prediksi'); // Pesan error global
        $errorMessageFromApi = session('api_error_message_prediksi'); // Pesan error dari API Flask

        $forecastData = session('forecast_data_result'); // Hasil array dari API (jika sukses)
        $historicalSpots = session('historical_spots_for_chart', []); // Data historis untuk chart
        $forecastSpots = session('forecast_spots_for_chart', []); // Data prediksi untuk chart

        $predictedRevenue = session('predicted_revenue_result', 0.0);
        $salesGrowthPercentage = session('sales_growth_percentage_result', 0.0);

        // Warna untuk JS (jika tidak diambil dari CSS variables di JS)
        $styles = [
            'accentBlueColor' => '#00B0FF',
            'successColor' => '#4CAF50',
            'textWhite70Color' => 'rgba(224,224,224,0.7)',
            // ...tambahkan warna lain jika perlu di JS
        ];
        
        // Hapus flash session agar tidak muncul lagi di refresh berikutnya
        session()->forget([
            'is_predicting_process', 'global_error_message_prediksi', 'api_error_message_prediksi',
            'forecast_data_result', 'historical_spots_for_chart', 'forecast_spots_for_chart',
            'predicted_revenue_result', 'sales_growth_percentage_result',
            'snackbar_message', 'snackbar_type' // Untuk pesan snackbar
        ]);


        return view('prediksi', compact(
            'isLoadingItems', 'selectedItemId', 'selectedItemName', 'itemDropdownList',
            'timeRangeOptions', 'selectedTimeRangeDays',
            'dataPointOptions', 'selectedDataPointAggregation',
            'isPredicting', 'globalErrorMessage', 'errorMessageFromApi',
            'forecastData', 'historicalSpots', 'forecastSpots',
            'predictedRevenue', 'salesGrowthPercentage', 'styles'
        ));
    }

    public function generatePrediction(Request $request)
    {
        // 1. Validasi input $request->selected_item_id, $request->selected_time_range_days, dll.
        // 2. Set session('is_predicting_process', true);
        // 3. Panggil API Flask Anda (misalnya menggunakan Http::post(...))
        //    - Tangani timeout, connection error, dll.
        //    - Tangani response sukses dan error dari API Flask
        // 4. Jika sukses:
        //    - Proses response JSON dari Flask.
        //    - Ambil 'forecast' data.
        //    - Panggil fungsi (mirip _processAndDisplayResults) untuk:
        //        - Fetch data penjualan historis dari DB Laravel (sesuai user_id, item_id, rentang tanggal).
        //        - Lakukan agregasi data historis dan forecast menjadi format FlSpot (atau {x, y} untuk Chart.js).
        //        - Hitung _predictedRevenue, _salesGrowthPercentage.
        //    - Simpan hasil ke session: forecast_data_result, historical_spots_for_chart, dll.
        //    - Simpan pesan sukses ke session('snackbar_message', 'Prediksi berhasil dibuat!'), session('snackbar_type', 'success');
        // 5. Jika error (dari API Flask atau proses di Laravel):
        //    - Simpan pesan error ke session('api_error_message_prediksi', 'Pesan error dari API...'); atau
        //    - session('global_error_message_prediksi', 'Terjadi kesalahan saat memproses prediksi.');
        //    - Simpan pesan error ke session('snackbar_message', 'Gagal membuat prediksi.'), session('snackbar_type', 'error');
        // 6. Set session('is_predicting_process', false);
        // 7. Redirect kembali ke halaman prediksi: return redirect()->route('prediksi.show');

        // Contoh Sederhana (TANPA LOGIKA LENGKAP):
        session([
            'selected_item_id_prediksi' => $request->input('selected_item_id'),
            'selected_time_range_days_prediksi' => $request->input('selected_time_range_days'),
            'selected_data_point_aggregation_prediksi' => $request->input('selected_data_point_aggregation'),
        ]);

        // SIMULASI HASIL PREDIKSI
        if ($request->input('selected_item_id') == 'PROD001') {
            session([
                'forecast_data_result' => [10.0, 12.0, 11.0, 15.0, 13.0, 16.0, 14.0], // 7 hari
                'historical_spots_for_chart' => [
                    ['x' => 0, 'y' => 5], ['x' => 1, 'y' => 7], ['x' => 2, 'y' => 6],
                    ['x' => 3, 'y' => 8], ['x' => 4, 'y' => 9], ['x' => 5, 'y' => 7],
                    ['x' => 6, 'y' => 10]
                ],
                'forecast_spots_for_chart' => [ // Dimulai dari titik terakhir historis
                    ['x' => 6, 'y' => 10], // Titik sambung
                    ['x' => 7, 'y' => 12], ['x' => 8, 'y' => 11], ['x' => 9, 'y' => 15],
                    ['x' => 10, 'y' => 13], ['x' => 11, 'y' => 16], ['x' => 12, 'y' => 14]
                ],
                'predicted_revenue_result' => 910000, // total forecast sales (91) * harga item (misal 10000)
                'sales_growth_percentage_result' => 15.5,
                'snackbar_message' => 'Prediksi berhasil!',
                'snackbar_type' => 'success'
            ]);
        } else {
            session([
                'api_error_message_prediksi' => 'Gagal mendapatkan prediksi untuk item ini.',
                'snackbar_message' => 'Prediksi gagal.',
                'snackbar_type' => 'error'
            ]);
        }
        return redirect()->route('prediksi.show'); // Ganti nama route
    }
}
