<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - BizzGrow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
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
            <h1>Daftar Akun</h1>
            <p>Silakan isi data untuk membuat akun baru.</p>
        </div>

        <div class="register-container">
            <form id="registerForm" method="POST" action="#">
                @csrf

                <div id="form-error-message" class="error-message" style="display:none;">
                    <ul><li></li></ul>
                </div>

                <label for="name">Nama Lengkap</label>
                <div class="input-wrapper">
                    <span class="icon">&#128100;</span>
                    <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                </div>

                <label for="email">Email</label>
                <div class="input-wrapper">
                    <span class="icon">&#9993;</span>
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                </div>

                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <span class="icon">&#128274;</span>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
                    <span class="suffix-icon" onclick="togglePasswordVisibility('password')">
                        &#128065;
                    </span>
                </div>

                <button type="submit">Daftar</button>

                <div class="login-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                </div>
            </form>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
        import { getAuth, createUserWithEmailAndPassword, updateProfile } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js";
        import { getFirestore, doc, setDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

        // KONFIGURASI FIREBASE ANDA (SALIN DARI KONSOL FIREBASE -> WEB APP)
        const firebaseConfig = {
            apiKey: "AIzaSyBc49rzGyXD1AUfM6x22ddQP_oV8q93Y_g",
            authDomain: "bizzgrow-9644e.firebaseapp.com",
            projectId: "bizzgrow-9644e",
            storageBucket: "bizzgrow-9644e.firebasestorage.app",
            messagingSenderId: "283921804867",
            appId: "1:283921804867:web:0caa9479e1e177deb82ef6"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const db = getFirestore(app); // Inisialisasi Firestore

        const registerForm = document.getElementById('registerForm');
        const nameInput = document.getElementById('name');
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

        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            hideErrorMessage();

            const name = nameInput.value;
            const email = emailInput.value;
            const password = passwordInput.value;

            if (!name || !email || !password) {
                showErrorMessage("Semua field wajib diisi.");
                return;
            }
            if (password.length < 6) {
                showErrorMessage("Kata sandi minimal 6 karakter.");
                return;
            }

            try {
                // 1. Buat user di Firebase Authentication
                const userCredential = await createUserWithEmailAndPassword(auth, email, password);
                const user = userCredential.user;

                // 2. Update display name di Firebase Auth (opsional, tapi bagus)
                await updateProfile(user, { displayName: name });

                // 3. Simpan data user ke Firestore (di frontend)
                // Set dokumen dengan UID user sebagai ID dokumen
                await setDoc(doc(db, "users", user.uid), {
                    name: name,
                    email: email,
                    photoUrl: user.photoURL || null, // photoURL dari Auth, default null
                    createdAt: serverTimestamp() // Firestore Server Timestamp
                });

                // 4. Dapatkan Firebase ID Token dari user yang baru terdaftar
                const idToken = await user.getIdToken();

                // 5. Kirim ID Token ke backend Laravel untuk verifikasi dan pembuatan sesi
                const csrfToken = document.querySelector('input[name="_token"]').value;

                const response = await fetch("{{ route('verify.firebase.token') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ idToken: idToken })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect; // Redirect ke dashboard
                } else {
                    showErrorMessage(data.message || "Terjadi kesalahan saat pendaftaran.");
                }

            } catch (error) {
                // Tangani error dari Firebase Auth SDK
                let displayMessage = "Pendaftaran gagal. Silakan coba lagi.";
                if (error.code) {
                    switch (error.code) {
                        case 'auth/email-already-in-use': displayMessage = 'Email ini sudah terdaftar.'; break;
                        case 'auth/invalid-email': displayMessage = 'Format email tidak valid.'; break;
                        case 'auth/weak-password': displayMessage = 'Kata sandi terlalu lemah. Minimal 6 karakter.'; break;
                        default: displayMessage = `Error Firebase: ${error.message}`;
                    }
                } else {
                    displayMessage = `Terjadi kesalahan tidak terduga: ${error.message}`;
                }
                showErrorMessage(displayMessage);
            }
        });

        // Fungsi untuk toggle password visibility (sama seperti sebelumnya)
        function togglePasswordVisibility(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = "&#128064;";
            } else {
                input.type = "password";
                icon.innerHTML = "&#128065;";
            }
        }
    </script>
</body>
</html>