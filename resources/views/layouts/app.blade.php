{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BizzGrow App')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> {{-- Link ke CSS utama --}}
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}"> {{-- Link ke CSS sidebar --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-storage-compat.js"></script>

    {{-- CDN Chart.js, Chart.js Datalabels plugin, dan Luxon --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.1.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.0.1/build/global/luxon.min.js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}"
        };

        const app = firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const db = firebase.firestore();
        const storage = firebase.storage(); 
    </script>
    @stack('styles')
</head>
<body>
    <div class="app-layout">
        @include('layouts.sidebar') {{-- Memanggil sidebar --}}

        <button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>

        <main class="main-content">
            <div class="page-container">
                @yield('content') {{-- Konten spesifik halaman --}}
            </div>
        </main>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mainContent = document.querySelector('.main-content'); // Dapatkan main content

            if (mobileMenuToggle && sidebar) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // Opsional: Tutup sidebar saat mengklik di luar sidebar di mobile
            if (mainContent && sidebar) {
                mainContent.addEventListener('click', function() {
                    if (sidebar.classList.contains('active') && window.innerWidth <= 992) {
                        sidebar.classList.remove('active');
                    }
                });
            }

            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    // Cek apakah elemen yang diklik ada di dalam modal atau tidak
                    if (!this.closest('#profileEditModal')) { // Hanya jalankan jika BUKAN dari dalam modal
                        console.log('Menu item (luar modal) clicked:', this.querySelector('.menu-text').textContent);
                        // Tambahkan navigasi atau fungsi lain di sini
                    }
                });
            });

            // Handle klik tombol logout di main content
            document.querySelector('.main-content-logout-button')?.addEventListener('click', function(event) {
                // Ganti confirm() dengan UI custom jika memungkinkan, karena confirm() bisa diblokir.
                // Untuk sekarang, kita biarkan.
                if(confirm('Apakah Anda yakin ingin keluar dari akun?')) {
                    document.getElementById('logout-form').submit();
                }
            });
        });
    </script>
    @stack('scripts') {{-- <--- DITAMBAHKAN UNTUK SCRIPT SPESIFIK HALAMAN --}}
</body>
</html>
