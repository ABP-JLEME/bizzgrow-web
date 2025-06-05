<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - BizzGrow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        /* Gaya untuk pesan error (sama seperti sebelumnya) */
        .error-message { color: #ff4d4d; background-color: #ffe6e6; border: 1px solid #ff0000; padding: 10px; margin-bottom: 15px; border-radius: 8px; font-size: 14px; text-align: center; }
        .error-message ul { list-style: none; padding: 0; margin: 0; }
    </style>
</head>
<body>
    <div class="background-waves"></div>
    <div class="circle circle1"></div>
    <div class="circle circle2"></div>
    <div class="circle circle3"></div>

    <div class="content-wrapper">
        <div class="welcome-text">
            <h1>Selamat datang!👋</h1>
            <p>Silakan login untuk masuk ke BizzGrow...</p>
        </div>

        <div class="login-container">
            <form id="loginForm" method="POST" action="#"> @csrf {{-- Tetap butuh CSRF token untuk AJAX ke backend Laravel --}}

                <div id="form-error-message" class="error-message" style="display:none;">
                    <ul><li></li></ul>
                </div>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>

                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>

                <button type="submit">Masuk</button>

                <div class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                </div>
            </form>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";

        // KONFIGURASI FIREBASE ANDA (SALIN DARI KONSOL FIREBASE -> WEB APP)
        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        const loginForm = document.getElementById('loginForm');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const errorMessageDiv = document.getElementById('form-error-message');
        const errorMessageList = errorMessageDiv.querySelector('ul');

        function showErrorMessage(message) {
            errorMessageList.innerHTML = `<li>${message}</li>`;
            errorMessageDiv.style.display = 'block';
        }

        function hideErrorMessage() {
            errorMessageDiv.style.display = 'none';
            errorMessageList.innerHTML = '';
        }

        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Mencegah submit form HTML default

            hideErrorMessage(); // Sembunyikan pesan error sebelumnya

            const email = emailInput.value;
            const password = passwordInput.value;

            if (!email || !password) {
                showErrorMessage("Email dan kata sandi wajib diisi.");
                return;
            }

            try {
                const userCredential = await signInWithEmailAndPassword(auth, email, password);
                const user = userCredential.user;
                const idToken = await user.getIdToken(); // Dapatkan Firebase ID Token

                // Kirim ID Token ke backend Laravel untuk verifikasi dan pembuatan sesi
                const csrfToken = document.querySelector('input[name="_token"]').value;

                const response = await fetch("{{ route('verify.firebase.token') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Kirim CSRF token
                    },
                    body: JSON.stringify({ idToken: idToken })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect; // Redirect ke dashboard
                } else {
                    showErrorMessage(data.message || "Terjadi kesalahan saat login.");
                }

            } catch (error) {
                // Tangani error dari Firebase Auth SDK
                let displayMessage = "Login gagal. Silakan coba lagi.";
                if (error.code) {
                    switch (error.code) {
                        case 'auth/invalid-email': displayMessage = 'Format email tidak valid.'; break;
                        case 'auth/user-disabled': displayMessage = 'Akun pengguna ini telah dinonaktifkan.'; break;
                        case 'auth/user-not-found': displayMessage = 'Email tidak terdaftar.'; break;
                        case 'auth/wrong-password': displayMessage = 'Kata sandi salah.'; break;
                        case 'auth/too-many-requests': displayMessage = 'Terlalu banyak percobaan login. Coba lagi nanti.'; break;
                        default: displayMessage = `Error Firebase: ${error.message}`;
                    }
                } else {
                    displayMessage = `Terjadi kesalahan tidak terduga: ${error.message}`;
                }
                showErrorMessage(displayMessage);
            }
        });
    </script>
</body>
</html>