<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - BizzGrow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(to bottom right, #1E40AF, #0F172A);
            color: white;
            overflow-x: hidden;
        }

        /* --- Main Layout --- */
        .app-layout {
            display: flex;
            width: 100%;
        }

        /* --- Sidebar Styles --- */
        .sidebar {
            width: 250px; /* Lebar default sidebar */
            background-color: #1E2D3D; /* Warna latar belakang sidebar */
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            flex-shrink: 0; /* Pastikan sidebar tidak menyusut */
            position: sticky; /* Sidebar akan tetap di tempat saat scroll */
            top: 0;
            left: 0;
            height: 100vh; /* Memastikan sidebar mengambil seluruh tinggi viewport */
            overflow-y: auto; /* Memungkinkan sidebar discroll jika isinya banyak */
            transition: transform 0.3s ease-in-out; /* Transisi untuk responsive (mobile) */
        }

        .sidebar.hidden {
            transform: translateX(-100%); /* Sembunyikan sidebar di mobile */
            position: absolute; /* Ganti posisi agar tidak mengganggu layout */
        }

        .sidebar-header {
            font-size: 24px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 30px;
            color: #60A5FA; /* Warna BizzGrow */
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1; /* Agar menu mengisi ruang yang tersedia */
        }

        .sidebar-menu-item {
            margin-bottom: 10px;
        }

        .sidebar-menu-item a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            border-radius: 8px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .sidebar-menu-item a:hover {
            background-color: #1E40AF; /* Warna hover */
            color: white;
        }

        .sidebar-menu-item a.active {
            background-color: #3B82F6; /* Warna aktif */
            color: white;
            font-weight: 700;
        }

        .sidebar-menu-item a svg {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            color: inherit; /* Mengambil warna dari link */
        }

        /* --- Logout Button --- */
        .logout-button {
            background: none;
            border: none;
            color: white;
            padding: 12px 15px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.2s ease, color 0.2s ease;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            margin-top: 20px; /* Spasi di atas tombol logout */
        }

        .logout-button:hover {
            background-color: #DC2626; /* Merah untuk logout */
        }

        .logout-button svg {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            color: inherit;
        }

        /* --- Main Content Area --- */
        .main-content {
            flex-grow: 1; /* Konten utama mengambil sisa ruang */
            padding: 30px;
            max-width: calc(100% - 250px); /* Kurangi lebar sidebar */
            box-sizing: border-box;
            overflow-y: auto; /* Memungkinkan konten utama discroll */
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        /* --- Header Section (Same as before) --- */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            flex-grow: 1;
            min-width: 0;
        }

        .profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
            background-color: #3b82f6;
            border: 2px solid white;
        }

        .user-name {
            font-size: 22px;
            font-weight: 600;
            color: #FFFFFF;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .settings-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.2s ease;
        }

        .settings-button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .settings-button svg {
            width: 30px;
            height: 30px;
            color: white;
        }

        /* --- Info Cards Section (Same as before) --- */
        .info-cards-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-card {
            flex: 1;
            background: linear-gradient(to bottom right, #1E40AF, #3B82F6);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 120px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }

        .info-card:hover {
            transform: translateY(-3px);
        }

        .card-icon {
            font-size: 32px;
            color: white;
            margin-bottom: 10px;
        }

        .card-value {
            font-size: 28px;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .card-label {
            font-size: 15px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.8);
        }

        /* --- Placeholder for content below cards --- */
        .dashboard-section-title {
            font-size: 20px;
            font-weight: 600;
            color: white;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .dashboard-content-placeholder {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
        }

        /* --- Mobile / Responsive Adjustments --- */
        .mobile-menu-toggle {
            display: none; /* Sembunyikan di desktop */
            background: none;
            border: none;
            color: white;
            font-size: 30px;
            cursor: pointer;
            padding: 10px;
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 1001; /* Pastikan di atas sidebar */
        }


        @media (max-width: 992px) { /* Untuk tablet dan mobile */
            .sidebar {
                position: fixed; /* Jadikan fixed di mobile */
                height: 100vh;
                top: 0;
                left: 0;
                transform: translateX(-100%); /* Sembunyikan secara default */
                z-index: 1000; /* Pastikan di atas konten utama */
            }

            .sidebar.active {
                transform: translateX(0); /* Tampilkan saat aktif */
            }

            .main-content {
                max-width: 100%; /* Konten utama mengambil lebar penuh */
                margin-left: 0; /* Tidak ada margin samping karena sidebar tersembunyi */
                padding: 20px; /* Sesuaikan padding */
            }

            .mobile-menu-toggle {
                display: block; /* Tampilkan tombol toggle di mobile */
            }

            .header-section {
                padding-left: 50px; /* Beri ruang untuk tombol toggle */
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }
            .user-name {
                font-size: 20px;
            }
            .settings-button svg {
                width: 26px;
                height: 26px;
            }
            .info-cards-row {
                flex-direction: column;
                gap: 10px;
            }
            .info-card {
                padding: 15px;
                min-height: 100px;
            }
            .card-icon {
                font-size: 28px;
            }
            .card-value {
                font-size: 24px;
            }
            .card-label {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .dashboard-container {
                padding: 10px;
            }
            .user-name {
                font-size: 18px;
            }
            .profile-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
            }
            .settings-button svg {
                width: 24px;
                height: 24px;
            }
            .info-card {
                padding: 12px;
                min-height: 90px;
            }
            .card-icon {
                font-size: 24px;
            }
            .card-value {
                font-size: 20px;
            }
            .card-label {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

    <div class="app-layout">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">BizzGrow</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="{{ route('dashboard') }}" class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M3.75 6.75a3 3 0 0 0-3 3v7.5a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3v-7.5a3 3 0 0 0-3-3H3.75ZM16.5 9a1.5 1.5 0 0 0-1.5 1.5v3.75a1.5 1.5 0 0 0 1.5 1.5h1.5a1.5 1.5 0 0 0 1.5-1.5V10.5a1.5 1.5 0 0 0-1.5-1.5h-1.5ZM8.25 9a1.5 1.5 0 0 0-1.5 1.5v3.75a1.5 1.5 0 0 0 1.5 1.5h1.5a1.5 1.5 0 0 0 1.5-1.5V10.5a1.5 1.5 0 0 0-1.5-1.5h-1.5ZM3.75 9a1.5 1.5 0 0 0-1.5 1.5v3.75a1.5 1.5 0 0 0 1.5 1.5h1.5a1.5 1.5 0 0 0 1.5-1.5V10.5a1.5 1.5 0 0 0-1.5-1.5H3.75Z" clip-rule="evenodd" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.875 14.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM11.25 14.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM14.625 14.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM16.875 19.25a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75v-.75ZM21.75 6.75a.75.75 0 0 0-.75-.75H3.75a.75.75 0 0 0-.75.75v10.5c0 .414.336.75.75.75h14.25a.75.75 0 0 0 .671-.418c1.121-2.316 2.053-4.706 2.74-7.151a.75.75 0 0 0-.007-.781Zm-1.614 1.341c-.02.062-.045.123-.072.183h-5.225a.75.75 0 0 1-.75-.75V6.75H20.25c.023.238.041.478.055.719ZM11.25 6.75a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1-.75-.75V6.75ZM18.75 6.75a.75.75 0 0 1 .75-.75H20.25v.75a.75.75 0 0 1-.75.75h-.75a.75.75 0 0 1-.75-.75V6.75ZM3.75 6.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 .75.75v.75a.75.75.75 0 0 1-.75.75H4.5a.75.75 0 0 1-.75-.75V6.75Z" clip-rule="evenodd" />
                        </svg>
                        Penjualan
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.25 11.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75Zm0 4.5a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75ZM2.25 6.75a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                        </svg>
                        Prediksi
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                        Akun
                    </a>
                </li>
            </ul>

            <button class="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a.75.75 0 0 1-.75.75h-6a.75.75 0 0 1-.75-.75V5.25a.75.75 0 0 1-.75-.75h6a.75.75 0 0 1 .75.75V9a.75.75 0 0 0 1.5 0V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm10.72 4.03a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06l3.03-3.03H10.5a.75.75 0 0 1 0-1.5h4.94l-3.03-3.03a.75.75 0 0 1 1.06-1.06l4.25 4.25Z" clip-rule="evenodd" />
                </svg>
                Logout
            </button>
        </aside>

        <button class="mobile-menu-toggle" id="mobile-menu-toggle">☰</button>


        <main class="main-content" id="main-content">
            <div class="dashboard-container">
                <div class="header-section">
                    <div class="user-profile">
                        <img src="{{ $photoUser ?? asset('images/default-avatar.png') }}" alt="User Avatar" class="profile-avatar">
                        <span class="user-name">{{ $namaUser ?? 'Loading...' }}</span>
                    </div>
                    <button class="settings-button" onclick="showProfileSettings()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.078 2.296a.75.75 0 0 1 .844 0 9.75 9.75 0 0 1 5.924 5.924.75.75 0 0 1 0 .844 9.75 9.75 0 0 1-5.924 5.924.75.75 0 0 1-.844 0 9.75 9.75 0 0 1-5.924-5.924.75.75 0 0 1 0-.844 9.75 9.75 0 0 1 5.924-5.924ZM9.75 12a2.25 2.25 0 1 0 4.5 0 2.25 2.25 0 0 0-4.5 0ZM12 1.5A10.5 10.5 0 1 0 22.5 12 10.5 10.5 0 0 0 12 1.5Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div class="info-cards-row">
                    <div class="info-card">
                        <div class="card-icon">&#128176;</div>
                        <div class="card-value">{{ $totalPendapatanFormatted ?? 'Rp 0' }}</div>
                        <div class="card-label">Total pendapatan</div>
                    </div>
                    <div class="info-card">
                        <div class="card-icon">&#128200;</div>
                        <div class="card-value">{{ $totalProdukTerjual ?? '0' }}</div>
                        <div class="card-label">Total produk terjual</div>
                    </div>
                </div>

                <h2 class="dashboard-section-title">Aktivitas Terakhir</h2>
                <div class="dashboard-content-placeholder">
                    <p>Data aktivitas terbaru akan ditampilkan di sini.</p>
                </div>

                <h2 class="dashboard-section-title">Laporan Keuangan Cepat</h2>
                <div class="dashboard-content-placeholder">
                    <p>Grafik dan ringkasan keuangan akan ditampilkan di sini.</p>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>
        </main>
    </div>

    <script>
        // Fungsi untuk menampilkan popup profil (placeholder)
        function showProfileSettings() {
            alert('Settings / Profile popup would appear here. You can implement this using a modal dialog.');
        }

        // Script untuk toggle sidebar di mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mainContent = document.getElementById('main-content');

            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                // Optional: add overlay to main content when sidebar is open
                // mainContent.classList.toggle('sidebar-open-overlay');
            });

        });
    </script>

</body>

</html>