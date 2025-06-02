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

            // Handle klik menu item di profile page
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('click', function() {
                    console.log('Menu item clicked:', this.querySelector('.menu-text').textContent);
                    // Tambahkan navigasi atau fungsi lain di sini
                });
            });

            // Handle klik profile card di profile page
            document.querySelector('.profile-card')?.addEventListener('click', function() {
                console.log('Profile card clicked');
                // Tambahkan navigasi ke halaman edit profil misalnya
            });

            // Handle klik tombol logout di main content
            document.querySelector('.main-content-logout-button')?.addEventListener('click', function(event) {
                if(confirm('Apakah Anda yakin ingin keluar dari akun?')) {
                    document.getElementById('logout-form').submit();
                }
            });
        });
    </script>
</body>
</html>