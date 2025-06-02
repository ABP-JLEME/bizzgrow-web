{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('title', 'Dashboard BizzGrow')

@section('content')

     {{-- === START: Firebase Initialization and User Data Fetch (Frontend) === --}}
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>
    <script>
    
        const firebaseConfig = {
            apiKey: "AIzaSyBc49rzGyXD1AUfM6x22ddQP_oV8q93Y_g",
            authDomain: "bizzgrow-9644e.firebaseapp.com",
            projectId: "bizzgrow-9644e",
            storageBucket: "bizzgrow-9644e.firebasestorage.app",
            messagingSenderId: "283921804867",
            appId: "1:283921804867:web:0caa9479e1e177deb82ef6"
        };

        // Initialize Firebase
        const app = firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const db = firebase.firestore(); // Initialize Firestore

        document.addEventListener('DOMContentLoaded', function() {

            const dashboardUserNameSpan = document.getElementById('dashboard-user-name');
            const dashboardUserPhotoImg = document.getElementById('dashboard-user-photo');

            // Listen for authentication state changes
            auth.onAuthStateChanged(user => {
                if (user) {
                    // User is signed in. Get their UID.
                    const uid = user.uid;

                    // Skema: collection 'users', document ID adalah UID pengguna
                    db.collection("users").doc(uid).get().then((doc) => {
                        if (doc.exists) {
                            const userData = doc.data();
                            const userName = userData.name || user.email;
                            const userPhoto = userData.photoUrl || '{{ asset("images/avatar-default.png") }}';

                            if (dashboardUserNameSpan) {
                                dashboardUserNameSpan.textContent = userName;
                            }
                            if (dashboardUserPhotoImg) {
                                dashboardUserPhotoImg.src = userPhoto;
                            }
                        } else {
                            if (dashboardUserNameSpan) {
                                dashboardUserNameSpan.textContent = user.displayName || user.email;
                            }
                            if (dashboardUserPhotoImg) {
                                dashboardUserPhotoImg.src = user.photoURL || '{{ asset("images/avatar-default.png") }}';
                            }
                            console.warn("No user document found in Firestore for UID:", uid);
                        }
                    }).catch((error) => {
                        console.error("Error getting user document from Firestore:", error);
                        if (dashboardUserNameSpan) {
                            dashboardUserNameSpan.textContent = user.displayName || user.email;
                        }
                        if (dashboardUserPhotoImg) {
                            dashboardUserPhotoImg.src = user.photoURL || '{{ asset("images/avatar-default.png") }}';
                        }
                    });

                    // Ambil data penjualan untuk menghitung total pendapatan & total produk terjual
                    db.collection("penjualan")
                    .where("user_id", "==", uid)
                    .get()
                    .then((querySnapshot) => {
                        let totalPendapatan = 0;
                        let totalProduk = 0;

                        querySnapshot.forEach((doc) => {
                        const data = doc.data();
                        const harga = data.harga_item;
                        const jumlah = data.jumlah_terjual;

                        if (typeof harga === 'number' && typeof jumlah === 'number') {
                            totalPendapatan += harga * jumlah;
                            totalProduk += jumlah;
                        }
                        });

                        // Update tampilan
                        const totalPendapatanEl = document.getElementById('total-pendapatan');
                        const totalProdukEl = document.getElementById('total-produk');

                        if (totalPendapatanEl) {
                        totalPendapatanEl.textContent = totalPendapatan.toLocaleString('id-ID');
                        }

                        if (totalProdukEl) {
                        totalProdukEl.textContent = totalProduk.toLocaleString('id-ID');
                        }
                    })
                    .catch((error) => {
                        console.error("[ERROR] Gagal mengambil data penjualan:", error);
                    });

                    // Ambil tanggal hari ini dalam format yyyy-MM-dd
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');
                    const todayString = `${yyyy}-${mm}-${dd}`;

                    db.collection("penjualan")
                    .where("user_id", "==", uid)
                    .where("tanggal", "==", todayString) // filter HANYA untuk hari ini
                    .get()
                    .then((querySnapshot) => {
                        let totalPendapatan = 0;
                        let totalProduk = 0;

                        querySnapshot.forEach((doc) => {
                            const data = doc.data();
                            const harga = data.harga_item;
                            const jumlah = data.jumlah_terjual;

                            if (typeof harga === 'number' && typeof jumlah === 'number') {
                                totalPendapatan += harga * jumlah;
                                totalProduk += jumlah;
                            }
                        });

                        // Update tampilan
                        const totalPendapatanEl = document.getElementById('total-pendapatan-hari-ini');
                        const totalProdukEl = document.getElementById('total-produk-hari-ini');

                        if (totalPendapatanEl) {
                            totalPendapatanEl.textContent = totalPendapatan.toLocaleString('id-ID');
                        }

                        if (totalProdukEl) {
                            totalProdukEl.textContent = totalProduk.toLocaleString('id-ID');
                        }

                        console.log(`[DEBUG] Pendapatan hari ini: ${totalPendapatan}, Produk: ${totalProduk}`);
                    })
                    .catch((error) => {
                        console.error("[ERROR] Gagal mengambil data penjualan hari ini:", error);
                    });


                } else {
                    window.location.href = '{{ route("login") }}';
                }

            });
        });
    </script>

    <div class="header-section">
        <div class="user-profile">
            <img src="" alt="User Avatar" class="profile-avatar" id="dashboard-user-photo">
            <span class="user-name" id="dashboard-user-name"></span>
        </div>
    </div>

    <div class="info-cards-row">
        <div class="info-card">
            <div class="card-icon">&#128176;</div>
            <div class="card-value" id="total-pendapatan"></div>
            <div class="card-label">Total pendapatan</div>
        </div>
        <div class="info-card">
            <div class="card-icon">&#128230;</div>
            <div class="card-value" id="total-produk"></div>
            <div class="card-label">Total produk terjual</div>
        </div>
    </div>
    <div class="info-cards-row">
        <div class="info-card">
            <div class="card-icon">&#128184;</div>
            <div class="card-value" id="total-pendapatan-hari-ini"></div>
            <div class="card-label">Total pendapatan hari ini</div>
        </div>
        <div class="info-card">
            <div class="card-icon">&#129530;</div>
            <div class="card-value" id="total-produk-hari-ini"></div>
            <div class="card-label">Total produk terjual hari ini</div>
        </div>
    </div>

    <h2 class="dashboard-section-title">Chart bro</h2>
    <div class="dashboard-content-placeholder">
        <p>Grafik dan ringkasan keuangan akan ditampilkan di sini.</p>
    </div>
@endsection