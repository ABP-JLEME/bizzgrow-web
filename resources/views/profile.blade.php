{{-- resources/views/profile.blade.php --}}

@extends('layouts.app')

@section('title', 'Akun Pengguna')

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

        const app = firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();
        const db = firebase.firestore();

        document.addEventListener('DOMContentLoaded', function() {
            const profileNameElem = document.getElementById('profile-name');
            const profileEmailElem = document.getElementById('profile-email');
            const profilePhotoElem = document.getElementById('profile-photo');
            const profileDateElem = document.getElementById('profile-date');

            auth.onAuthStateChanged(user => {
                if (user) {
                    const uid = user.uid;
                    db.collection("users").doc(uid).get().then((doc) => {
                        if (doc.exists) {
                            const userData = doc.data();
                            const userName = userData.name || user.email;
                            const userEmail = user.email;
                            const userPhoto = userData.photoUrl || '{{ asset("images/default-avatar.png") }}';

                            // Mengambil createdAt, asumsikan tersimpan sebagai timestamp Firebase
                            let joinDateText = "Bergabung sejak tanggal tidak diketahui";
                            if(userData.createdAt){
                                const createdAtDate = userData.createdAt.toDate(); // convert Firebase Timestamp ke JS Date
                                joinDateText = "Bergabung sejak " + createdAtDate.toLocaleDateString('id-ID', {
                                    day: 'numeric', month: 'long', year: 'numeric'
                                });
                            }

                            if(profileNameElem) profileNameElem.textContent = userName;
                            if(profileEmailElem) profileEmailElem.textContent = userEmail;
                            if(profilePhotoElem) profilePhotoElem.src = userPhoto;
                            if(profileDateElem) profileDateElem.textContent = joinDateText;

                        } else {
                            // Jika dokumen user tidak ada di Firestore, fallback pakai data auth user
                            if(profileNameElem) profileNameElem.textContent = user.displayName || user.email;
                            if(profileEmailElem) profileEmailElem.textContent = user.email;
                            if(profilePhotoElem) profilePhotoElem.src = user.photoURL || '{{ asset("images/default-avatar.png") }}';
                            if(profileDateElem) profileDateElem.textContent = "Bergabung sejak tanggal tidak diketahui";
                        }
                    }).catch(error => {
                        console.error("Error getting user document from Firestore:", error);
                        if(profileNameElem) profileNameElem.textContent = user.displayName || user.email;
                        if(profileEmailElem) profileEmailElem.textContent = user.email;
                        if(profilePhotoElem) profilePhotoElem.src = user.photoURL || '{{ asset("images/default-avatar.png") }}';
                        if(profileDateElem) profileDateElem.textContent = "Bergabung sejak tanggal tidak diketahui";
                    });
                } else {
                    window.location.href = '{{ route("login") }}';
                }
            });
        });
    </script>

    <h1 class="profile-page-header">Akun</h1>

    <div class="profile-section">
        <div class="profile-card">
            <div class="profile-avatar">
                <img src="{{asset('images/default-avatar.png') }}" id="profile-photo" alt="User Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="profile-info">
                <h3 id="profile-name"></h3>
                <div class="profile-email" id="profile-email">{{'email@example.com' }}</div>
                <div class="profile-date" id="profile-date">Bergabung sejak ....</div>
            </div>
            <div class="chevron-right"></div>
        </div>
    </div>

    <div class="menu-section">
        <div class="section-title">Preferensi</div>
        <div class="menu-group">
            <div class="menu-item">
                <div class="menu-icon">🔔</div>
                <div class="menu-text">Notifikasi</div>
                <div class="chevron-right"></div>
            </div>
            <div class="menu-item">
                <div class="menu-icon">🌐</div>
                <div class="menu-text">Bahasa</div>
                <div class="chevron-right"></div>
            </div>
            <div class="menu-item">
                <div class="menu-icon">🎨</div>
                <div class="menu-text">Tema</div>
                <div class="chevron-right"></div>
            </div>
        </div>
    </div>

    <div class="menu-section">
        <div class="section-title">Bantuan</div>
        <div class="menu-group">
            <div class="menu-item">
                <div class="menu-icon">❓</div>
                <div class="menu-text">Pusat Bantuan</div>
                <div class="chevron-right"></div>
            </div>
            <div class="menu-item">
                <div class="menu-icon">📋</div>
                <div class="menu-text">Ketentuan Layanan</div>
                <div class="chevron-right"></div>
            </div>
            <div class="menu-item">
                <div class="menu-icon">🛡️</div>
                <div class="menu-text">Kebijakan Privasi</div>
                <div class="chevron-right"></div>
            </div>
        </div>
    </div>

    <div class="menu-section">
        <div class="section-title">Tentang Aplikasi</div>
        <div class="menu-group">
            <div class="menu-item">
                <div class="menu-icon">📱</div>
                <div class="menu-text">versi 1.0.0</div>
                <div class="chevron-right"></div>
            </div>
        </div>
    </div>

@endsection