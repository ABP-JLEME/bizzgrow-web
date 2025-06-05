{{-- resources/views/profile.blade.php --}}

@extends('layouts.app')

@section('title', 'Akun Pengguna')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    {{-- Style untuk modal info generik dan penyesuaian snackbar jika diperlukan --}}
    <style>
        /* Generic Info Modal Styles */
        .info-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none; /* Hidden by default */
            align-items: center;
            justify-content: center;
            z-index: 1001; /* Di atas modal edit profil jika ada */
        }

        .info-modal-content {
            background-color: #2C2F33; /* cardBackgroundColor (sesuaikan jika tema berbeda) */
            padding: 25px 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.4);
            text-align: left;
            color: #E0E0E0; /* Warna teks default (sesuaikan jika tema berbeda) */
            border: 1px solid #4F545C; /* subtleBorderColor (sesuaikan jika tema berbeda) */
        }

        .info-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #4F545C; /* subtleBorderColor */
        }

        .info-modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #FFFFFF; /* Warna judul modal (sesuaikan jika tema berbeda) */
        }

        .info-modal-close-btn {
            background: none;
            border: none;
            font-size: 28px;
            color: #A0A0A0; /* Warna tombol tutup (sesuaikan jika tema berbeda) */
            cursor: pointer;
            line-height: 1;
        }
        .info-modal-close-btn:hover {
            color: #FFFFFF; /* Warna tombol tutup saat hover */
        }

        .info-modal-body p {
            margin-bottom: 12px;
            line-height: 1.6;
            font-size: 15px;
        }
        .info-modal-body ul {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 12px;
        }
        .info-modal-body li {
            margin-bottom: 6px;
        }
        .info-modal-body strong {
            font-weight: 600;
            color: #FFFFFF; /* Warna teks strong (sesuaikan jika tema berbeda) */
        }
        .info-modal-body a {
            color: #00A3FF; /* accentBlueColor, untuk link di dalam modal */
            text-decoration: none;
        }
        .info-modal-body a:hover {
            text-decoration: underline;
        }

        /* Snackbar styling (jika belum ada di profile.css atau app.css) */
        /* Ini adalah snackbar yang sudah Anda definisikan, pastikan gayanya sesuai */
        .snackbar {
            position: fixed;
            left: 58%; /* Sesuaikan untuk posisi horizontal yang diinginkan */
            transform: translateX(-50%);
            bottom: 30px;
            background-color: #333; /* Default background */
            color: white;
            padding: 14px 20px;
            border-radius: 8px;
            z-index: 1005; /* Pastikan di atas semua modal lain */
            opacity: 0;
            transition: opacity 0.3s, bottom 0.3s;
            font-size: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
            text-align: center; /* Jika tanpa ikon */
        }
        .snackbar.show {
            opacity: 1;
            bottom: 50px;
        }
        .snackbar.error {
            background-color: #DC3545; /* Warna error */
        }
        /* Tambahkan class .success jika perlu */
        .snackbar.success {
            background-color: #28a745; /* Warna sukses */
        }

    </style>
@endpush

@section('content')
    {{-- Inisialisasi Firebase (diasumsikan dari layouts.app.blade.php atau disertakan di sana) --}}
    {{-- Pastikan firebase-app, auth, firestore, dan storage diinisialisasi --}}
    {{-- Contoh jika perlu diletakkan di sini:
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-firestore-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.10/firebase-storage-compat.js"></script>
    <script>
        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}"
        };
        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
        }
        const auth = firebase.auth();
        const db = firebase.firestore();
        const storage = firebase.storage();
    </script>
    --}}

    <script>
        // Variabel global dari kode asli Anda
        let currentUserData = null;
        let currentUid = null;

        // Fungsi dari kode asli Anda
        function updateProfileDisplay(name, email, photoUrl, joinDate) {
            const profileNameElem = document.getElementById('profile-name');
            const profileEmailElem = document.getElementById('profile-email');
            const profilePhotoElem = document.getElementById('profile-photo');
            const profileDateElem = document.getElementById('profile-date');

            if(profileNameElem) profileNameElem.textContent = name;
            if(profileEmailElem) profileEmailElem.textContent = email;
            if(profilePhotoElem) profilePhotoElem.src = photoUrl;
            if(profileDateElem) profileDateElem.textContent = joinDate;
        }

        function fetchAndDisplayUserData(user) {
            const uid = user.uid;
            currentUid = uid;
            const defaultPhoto = '{{ asset("images/avatar-default.png") }}';

            db.collection("users").doc(uid).get().then((doc) => {
                let userName, userEmail, userPhoto, joinDateText;

                if (doc.exists) {
                    const userData = doc.data();
                    currentUserData = userData;
                    userName = userData.name || user.email;
                    userEmail = user.email; // Selalu ambil dari auth untuk email terverifikasi
                    userPhoto = userData.photoUrl || defaultPhoto;

                    joinDateText = "Bergabung sejak tanggal tidak diketahui";
                    if(userData.createdAt && typeof userData.createdAt.toDate === 'function'){
                        const createdAtDate = userData.createdAt.toDate();
                        joinDateText = "Bergabung sejak " + createdAtDate.toLocaleDateString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric'
                        });
                    } else if (user.metadata.creationTime) { // Fallback ke metadata auth jika ada
                        const creationDate = new Date(user.metadata.creationTime);
                         joinDateText = "Bergabung sejak " + creationDate.toLocaleDateString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric'
                        });
                    }
                } else {
                    console.warn("No user document found in Firestore for UID:", uid, ". Creating one with basic info.");
                    // Buat dokumen pengguna jika tidak ada, untuk konsistensi
                    const basicUserData = {
                        name: user.displayName || user.email.split('@')[0],
                        email: user.email,
                        photoUrl: user.photoURL || defaultPhoto,
                        createdAt: firebase.firestore.FieldValue.serverTimestamp() // Timestamp server
                    };
                    db.collection("users").doc(uid).set(basicUserData)
                        .then(() => {
                             currentUserData = basicUserData;
                             fetchAndDisplayUserData(user); // Panggil ulang untuk memuat data yang baru dibuat
                        })
                        .catch(err => console.error("Error creating user document:", err));

                    // Untuk sementara tampilkan dari auth
                    userName = user.displayName || user.email.split('@')[0];
                    userEmail = user.email;
                    userPhoto = user.photoURL || defaultPhoto;
                    joinDateText = user.metadata.creationTime ? "Bergabung sejak " + new Date(user.metadata.creationTime).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : "Bergabung sejak tanggal tidak diketahui";
                    currentUserData = { name: userName, email: userEmail, photoUrl: userPhoto }; // Data sementara untuk modal
                }
                updateProfileDisplay(userName, userEmail, userPhoto, joinDateText);
            }).catch(error => {
                console.error("Error getting user document from Firestore:", error);
                currentUserData = { name: user.displayName || user.email.split('@')[0], email: user.email, photoUrl: user.photoURL };
                updateProfileDisplay(user.displayName || user.email.split('@')[0], user.email, user.photoURL || defaultPhoto, "Bergabung sejak tanggal tidak diketahui");
            });
        }

        // Fungsi untuk modal info generik (BARU)
        function openInfoModal(title, contentHtml) {
            document.getElementById('infoModalTitle').textContent = title;
            document.getElementById('infoModalBody').innerHTML = contentHtml;
            document.getElementById('infoModal').style.display = 'flex';
        }

        function closeInfoModal() {
            document.getElementById('infoModal').style.display = 'none';
        }

        // Fungsi snackbar dari kode asli Anda
        const styledSnackBar = document.getElementById('styledSnackBar'); // Pindahkan deklarasi ke scope lebih atas jika belum
        function showStyledSnackBar(message, isError = false, type = null) { // Tambahkan param type untuk konsistensi
            if (!styledSnackBar) return; // Guard clause
            styledSnackBar.textContent = message;
            styledSnackBar.className = 'snackbar show'; // Reset classes
            if (isError || type === 'error') {
                styledSnackBar.classList.add('error');
            } else if (type === 'success') {
                styledSnackBar.classList.add('success');
            }
            // Tambahkan .warning jika diperlukan
            setTimeout(() => {
                if (styledSnackBar) styledSnackBar.className = 'snackbar';
            }, 3000);
        }


        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Firebase jika belum (pindahkan ke atas jika ini tempatnya)
            // Contoh: if (!firebase.apps.length) { firebase.initializeApp(firebaseConfig); }
            // const auth = firebase.auth();
            // const db = firebase.firestore();
            // const storage = firebase.storage();

            auth.onAuthStateChanged(user => {
                if (user) {
                    fetchAndDisplayUserData(user);
                } else {
                    window.location.href = '{{ route("login") }}';
                }
            });

            // Modal Edit Profil trigger (dari kode asli Anda)
            const profileCard = document.querySelector('.profile-card');
            if (profileCard) {
                profileCard.addEventListener('click', openProfileEditModal);
            }

            // Event listener untuk menutup info modal (BARU)
            const infoModal = document.getElementById('infoModal'); // Definisikan sekali
            const closeInfoModalBtn = document.getElementById('closeInfoModalBtn');

            if (closeInfoModalBtn) {
                closeInfoModalBtn.addEventListener('click', closeInfoModal);
            }
            if (infoModal) {
                infoModal.addEventListener('click', function(event) {
                    if (event.target === infoModal) { // Klik pada overlay
                        closeInfoModal();
                    }
                });
            }


            // Event listeners untuk item menu baru (BARU)
            document.getElementById('menu-notifikasi')?.addEventListener('click', () => {
                openInfoModal('Notifikasi',
                    '<p>Pengaturan notifikasi akan memungkinkan Anda untuk memilih jenis pemberitahuan yang ingin Anda terima, seperti pembaruan prediksi atau promosi khusus.</p><p><strong>Fitur ini masih dalam tahap pengembangan.</strong></p>'
                );
            });

            document.getElementById('menu-bahasa')?.addEventListener('click', () => {
                openInfoModal('Bahasa',
                    '<p>Saat ini aplikasi hanya mendukung <strong>Bahasa Indonesia</strong>.</p><p>Dukungan untuk bahasa lain akan ditambahkan di masa mendatang.</p>'
                );
            });

            document.getElementById('menu-tema')?.addEventListener('click', () => {
                openInfoModal('Tema Aplikasi',
                    '<p>Pengaturan tema (Dark Mode & Light Mode) akan segera hadir untuk meningkatkan kenyamanan visual Anda saat menggunakan aplikasi.</p><p><strong>Fitur ini masih dalam tahap pengembangan. Nantikan pembaruan selanjutnya!</strong></p>'
                );
            });

            document.getElementById('menu-pusat-bantuan')?.addEventListener('click', () => {
                openInfoModal('Pusat Bantuan',
                    '<p>Jika Anda mengalami kendala atau memiliki pertanyaan terkait penggunaan aplikasi BizzGrow, jangan ragu untuk menghubungi tim dukungan kami melalui:</p><ul><li>Email: <strong>support@bizzgrow.com</strong></li><li>FAQ: Kunjungi halaman FAQ kami di <a href="#">bizzgrow.com/faq</a></li></ul><p>Kami siap membantu Anda!</p>'
                );
            });

            document.getElementById('menu-ketentuan-layanan')?.addEventListener('click', () => {
                openInfoModal('Ketentuan Layanan',
                    '<p>Dengan menggunakan aplikasi BizzGrow, Anda setuju untuk mematuhi Ketentuan Layanan kami. Dokumen lengkap dapat diakses melalui tautan berikut: <a href="#">bizzgrow.com/terms</a>.</p><p>Pastikan Anda membacanya dengan seksama.</p>'
                );
            });

            document.getElementById('menu-kebijakan-privasi')?.addEventListener('click', () => {
                openInfoModal('Kebijakan Privasi',
                    '<p>Kami menghargai privasi Anda. Kebijakan Privasi kami menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda. Baca selengkapnya di: <a href="#">bizzgrow.com/privacy</a>.</p>'
                );
            });

            document.getElementById('menu-versi-aplikasi')?.addEventListener('click', () => {
                openInfoModal('Tentang Aplikasi BizzGrow',
                    '<p><strong>BizzGrow</strong></p><p>Versi: 1.0.0</p><p>© ' + new Date().getFullYear() + ' BizzGrow. Hak Cipta Dilindungi.</p><p>Aplikasi untuk membantu pertumbuhan bisnis Anda melalui analisis dan prediksi menggunakan AI dengan mudah.</p>'
                );
            });
        });
    </script>

    <h1 class="profile-page-header">Akun</h1>

    <div class="profile-section">
        <div class="profile-card"> {{-- Click this to open modal --}}
            <div class="profile-avatar">
                <img src="{{ asset('images/avatar-default.png') }}" id="profile-photo" alt="User Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="profile-info">
                <h3 id="profile-name">Memuat Nama...</h3>
                <div class="profile-email" id="profile-email">Memuat Email...</div>
                <div class="profile-date" id="profile-date">Memuat Tanggal Bergabung...</div>
            </div>
            <!-- <div class="chevron-right">›</div> {{-- Karakter chevron yang lebih umum --}} -->
        </div>
    </div>

    <div class="menu-section">
        <div class="section-title">Preferensi</div>
        <div class="menu-group">
            <div class="menu-item" id="menu-notifikasi">
                <div class="menu-icon">🔔</div>
                <div class="menu-text">Notifikasi</div>
                <div class="chevron-right">›</div>
            </div>
            <div class="menu-item" id="menu-bahasa">
                <div class="menu-icon">🌐</div>
                <div class="menu-text">Bahasa</div>
                <div class="chevron-right">›</div>
            </div>
            <div class="menu-item" id="menu-tema">
                <div class="menu-icon">🎨</div>
                <div class="menu-text">Tema</div>
                <div class="chevron-right">›</div>
            </div>
        </div>
    </div>

    <div class="menu-section">
        <div class="section-title">Bantuan</div>
        <div class="menu-group">
            <div class="menu-item" id="menu-pusat-bantuan">
                <div class="menu-icon">❓</div>
                <div class="menu-text">Pusat Bantuan</div>
                <div class="chevron-right">›</div>
            </div>
            <div class="menu-item" id="menu-ketentuan-layanan">
                <div class="menu-icon">📋</div>
                <div class="menu-text">Ketentuan Layanan</div>
                <div class="chevron-right">›</div>
            </div>
            <div class="menu-item" id="menu-kebijakan-privasi">
                <div class="menu-icon">🛡️</div>
                <div class="menu-text">Kebijakan Privasi</div>
                <div class="chevron-right">›</div>
            </div>
        </div>
    </div>

    <div class="menu-section">
        <div class="section-title">Tentang Aplikasi</div>
        <div class="menu-group">
            <div class="menu-item" id="menu-versi-aplikasi">
                <div class="menu-icon">📱</div>
                <div class="menu-text">Versi 1.0.0</div>
                <div class="chevron-right">›</div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Profil (dari kode asli Anda) --}}
    <div id="profileEditModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Edit Profil</span>
                <button id="closeProfileEditModal" class="modal-close-btn">×</button>
            </div>
            <div class="modal-body">
                <div class="profile-edit-avatar-section">
                    <div class="profile-edit-avatar-container">
                        <img src="{{ asset('images/avatar-default.png') }}" id="modal-profile-avatar-preview" class="profile-edit-avatar" alt="Avatar Preview">
                        <div id="modal-pick-image-trigger" class="profile-edit-camera-icon" title="Ganti Foto">
                            <svg viewBox="0 0 24 24"><path d="M4 4h3l2-2h6l2 2h3v16H4V4zm8 4c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zM12 14.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 9.5 12 9.5s2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path></svg>
                        </div>
                    </div>
                    <span id="modal-change-photo-text" class="profile-edit-change-photo-text">Ganti Foto</span>
                    <input type="file" id="profile-image-upload" accept="image/*" style="display: none;">
                </div>

                <div class="form-group">
                    <label for="modal-profile-name">NAMA</label>
                    <input type="text" id="modal-profile-name" placeholder="Nama Lengkap Anda">
                </div>
            </div>
             <button id="modal-save-profile-button" class="modal-save-btn">Simpan Perubahan</button>
        </div>
    </div>

    {{-- Modal Info Generik (BARU) --}}
    <div id="infoModal" class="info-modal-overlay">
        <div class="info-modal-content">
            <div class="info-modal-header">
                <span id="infoModalTitle" class="info-modal-title">Judul Info</span>
                <button id="closeInfoModalBtn" class="info-modal-close-btn">×</button>
            </div>
            <div id="infoModalBody" class="info-modal-body">
                {{-- Konten akan diisi oleh JavaScript --}}
            </div>
        </div>
    </div>

    {{-- Snackbar (dari kode asli Anda, pastikan selector ID ini benar) --}}
    <div id="styledSnackBar" class="snackbar">Pesan Snackbar</div>

@endsection

@push('scripts')
<script>
    // Variabel global dan fungsi dari kode asli Anda (untuk modal edit profil)
    const profileEditModal = document.getElementById('profileEditModal');
    const closeProfileEditModalBtn = document.getElementById('closeProfileEditModal');
    const modalProfileAvatarPreview = document.getElementById('modal-profile-avatar-preview');
    const modalProfileNameInput = document.getElementById('modal-profile-name');
    const modalPickImageTrigger = document.getElementById('modal-pick-image-trigger');
    const modalChangePhotoText = document.getElementById('modal-change-photo-text');
    const profileImageUploadInput = document.getElementById('profile-image-upload');
    const modalSaveProfileButton = document.getElementById('modal-save-profile-button');
    // const styledSnackBar = document.getElementById('styledSnackBar'); // Sudah dideklarasikan di atas di scope global JS

    let selectedImageFile = null;

    function openProfileEditModal() {
        if (currentUserData && auth.currentUser) {
            modalProfileNameInput.value = currentUserData.name || auth.currentUser.displayName || '';
            modalProfileAvatarPreview.src = currentUserData.photoUrl || auth.currentUser.photoURL || '{{ asset("images/avatar-default.png") }}';
            selectedImageFile = null;
            profileImageUploadInput.value = null;
            if (profileEditModal) profileEditModal.style.display = 'flex';
        } else {
            showStyledSnackBar('Data pengguna belum dimuat.', true, 'error');
        }
    }

    function closeProfileEditModal() {
        if (profileEditModal) profileEditModal.style.display = 'none';
    }

    // Event Listeners untuk Modal Edit Profil (dari kode asli Anda)
    if (closeProfileEditModalBtn) {
      closeProfileEditModalBtn.addEventListener('click', closeProfileEditModal);
    }

    if (profileEditModal) {
        profileEditModal.addEventListener('click', function(event) {
            if (event.target === profileEditModal) {
                closeProfileEditModal();
            }
        });
    }

    if (modalPickImageTrigger) {
        modalPickImageTrigger.addEventListener('click', () => profileImageUploadInput.click());
    }
    if (modalChangePhotoText) {
        modalChangePhotoText.addEventListener('click', () => profileImageUploadInput.click());
    }

    if (profileImageUploadInput) {
        profileImageUploadInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) { // Batas ukuran file 2MB
                    showStyledSnackBar('Ukuran gambar maksimal adalah 2MB.', true, 'error');
                    profileImageUploadInput.value = null; // Reset file input
                    return;
                }
                if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                    showStyledSnackBar('Format gambar tidak valid. Gunakan JPG, PNG, atau GIF.', true, 'error');
                    profileImageUploadInput.value = null; // Reset file input
                    return;
                }

                selectedImageFile = file;
                const reader = new FileReader();
                reader.onload = function(e) {
                    modalProfileAvatarPreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    async function handleProfileUpdate() {
        if (!auth.currentUser) {
            showStyledSnackBar('Anda tidak login!', true, 'error');
            return;
        }

        const newName = modalProfileNameInput.value.trim();
        if (!newName) {
            showStyledSnackBar('Nama tidak boleh kosong.', true, 'error');
            return;
        }

        modalSaveProfileButton.disabled = true;
        modalSaveProfileButton.textContent = 'Menyimpan...';

        try {
            const user = auth.currentUser;
            // Pastikan currentUid sudah terisi dari fetchAndDisplayUserData
            const userDocRef = db.collection("users").doc(currentUid || user.uid);


            let newPhotoURL = (currentUserData ? currentUserData.photoUrl : null) || user.photoURL;

            if (selectedImageFile) {
                const filePath = `profile_pictures/${user.uid}/${Date.now()}_${selectedImageFile.name}`; // Tambahkan timestamp untuk unik
                const fileRef = storage.ref().child(filePath);
                const uploadTask = await fileRef.put(selectedImageFile);
                newPhotoURL = await uploadTask.ref.getDownloadURL();
            }

            const dataToUpdate = {
                name: newName,
                photoUrl: newPhotoURL,
                // updatedAt: firebase.firestore.FieldValue.serverTimestamp() // Opsional: timestamp pembaruan
            };
            // Jika dokumen pengguna belum ada, gunakan set dengan merge agar bisa membuat atau update
            await userDocRef.set(dataToUpdate, { merge: true });


            // Update Auth profile jika ada perubahan nama atau foto
            const authProfileUpdates = {};
            if (user.displayName !== newName) authProfileUpdates.displayName = newName;
            if (user.photoURL !== newPhotoURL) authProfileUpdates.photoURL = newPhotoURL;

            if (Object.keys(authProfileUpdates).length > 0) {
                await user.updateProfile(authProfileUpdates);
            }

            // Re-fetch dan update UI, juga update currentUserData
            await fetchAndDisplayUserData(user);

            closeProfileEditModal();
            showStyledSnackBar('Profil berhasil diperbarui!', false, 'success');

        } catch (error) {
            console.error("Error updating profile:", error);
            showStyledSnackBar(`Gagal menyimpan: ${error.message}`, true, 'error');
        } finally {
            modalSaveProfileButton.disabled = false;
            modalSaveProfileButton.textContent = 'Simpan Perubahan';
        }
    }

    if (modalSaveProfileButton) {
        modalSaveProfileButton.addEventListener('click', handleProfileUpdate);
    }

</script>
@endpush