{{-- resources/views/profile.blade.php --}}

@extends('layouts.app')

@section('title', 'Akun Pengguna')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
    <script>
        let currentUserData = null; // To store current user data for the modal
        let currentUid = null;

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
            currentUid = uid; // Store UID
            db.collection("users").doc(uid).get().then((doc) => {
                let userName, userEmail, userPhoto, joinDateText;
                const defaultPhoto = '{{ asset("images/avatar-default.png") }}';

                if (doc.exists) {
                    const userData = doc.data();
                    currentUserData = userData; // Store for modal
                    userName = userData.name || user.email;
                    userEmail = user.email;
                    userPhoto = userData.photoUrl || defaultPhoto;

                    joinDateText = "Bergabung sejak tanggal tidak diketahui";
                    if(userData.createdAt && userData.createdAt.toDate){
                        const createdAtDate = userData.createdAt.toDate();
                        joinDateText = "Bergabung sejak " + createdAtDate.toLocaleDateString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric'
                        });
                    }
                } else {
                    currentUserData = { name: user.displayName, email: user.email, photoUrl: user.photoURL }; // Basic data for modal
                    userName = user.displayName || user.email;
                    userEmail = user.email;
                    userPhoto = user.photoURL || defaultPhoto;
                    joinDateText = "Bergabung sejak tanggal tidak diketahui";
                }
                updateProfileDisplay(userName, userEmail, userPhoto, joinDateText);
            }).catch(error => {
                console.error("Error getting user document from Firestore:", error);
                currentUserData = { name: user.displayName, email: user.email, photoUrl: user.photoURL };
                updateProfileDisplay(user.displayName || user.email, user.email, user.photoURL || '{{ asset("images/avatar-default.png") }}', "Bergabung sejak tanggal tidak diketahui");
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            auth.onAuthStateChanged(user => {
                if (user) {
                    fetchAndDisplayUserData(user);
                } else {
                    window.location.href = '{{ route("login") }}';
                }
            });

            // Modal trigger
            const profileCard = document.querySelector('.profile-card');
            if (profileCard) {
                profileCard.addEventListener('click', openProfileEditModal);
            }
        });
    </script>

    <h1 class="profile-page-header">Akun</h1>

    <div class="profile-section">
        <div class="profile-card"> {{-- Click this to open modal --}}
            <div class="profile-avatar">
                <img src="" id="profile-photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="profile-info">
                <h3 id="profile-name">Nama Pengguna</h3>
                <div class="profile-email" id="profile-email">{{'email@example.com' }}</div>
                <div class="profile-date" id="profile-date">Bergabung sejak ....</div>
            </div>
            <div class="chevron-right"></div>
        </div>
    </div>

    {{-- ... (rest of your menu sections) ... --}}
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


    <div id="profileEditModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-title">Edit Profile</span>
                <button id="closeProfileEditModal" class="modal-close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="profile-edit-avatar-section">
                    <div class="profile-edit-avatar-container">
                        <img src="" id="modal-profile-avatar-preview" class="profile-edit-avatar">
                        <div id="modal-pick-image-trigger" class="profile-edit-camera-icon" title="Change Photo">
                            <svg viewBox="0 0 24 24"><path d="M4 4h3l2-2h6l2 2h3v16H4V4zm8 4c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zM12 14.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 9.5 12 9.5s2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path></svg>
                        </div>
                    </div>
                    <span id="modal-change-photo-text" class="profile-edit-change-photo-text">Change Photo</span>
                    <input type="file" id="profile-image-upload" accept="image/*">
                </div>

                <div class="form-group">
                    <label for="modal-profile-name">NAMA</label>
                    <input type="text" id="modal-profile-name" placeholder="Nama Lengkap Anda">
                </div>
            </div>
             <button id="modal-save-profile-button" class="modal-save-btn">Save Changes</button>
        </div>
    </div>
    <div id="styledSnackBar" class="snackbar">Snackbar Message</div>

@endsection

@push('scripts')
<script>
    const profileEditModal = document.getElementById('profileEditModal');
    const closeProfileEditModalBtn = document.getElementById('closeProfileEditModal');
    const modalProfileAvatarPreview = document.getElementById('modal-profile-avatar-preview');
    const modalProfileNameInput = document.getElementById('modal-profile-name');
    const modalPickImageTrigger = document.getElementById('modal-pick-image-trigger');
    const modalChangePhotoText = document.getElementById('modal-change-photo-text');
    const profileImageUploadInput = document.getElementById('profile-image-upload');
    const modalSaveProfileButton = document.getElementById('modal-save-profile-button');
    const styledSnackBar = document.getElementById('styledSnackBar');

    let selectedImageFile = null; // To store the new image file

    function openProfileEditModal() {
        if (currentUserData && auth.currentUser) {
            modalProfileNameInput.value = currentUserData.name || auth.currentUser.displayName || '';
            modalProfileAvatarPreview.src = currentUserData.photoUrl || auth.currentUser.photoURL || '{{ asset("images/avatar-default.png") }}';
            selectedImageFile = null; // Reset selected file
            profileImageUploadInput.value = null; // Reset file input
            profileEditModal.style.display = 'flex';
        } else {
            showStyledSnackBar('User data not loaded yet.', true);
        }
    }

    function closeProfileEditModal() {
        profileEditModal.style.display = 'none';
    }

    function showStyledSnackBar(message, isError = false) {
        styledSnackBar.textContent = message;
        styledSnackBar.className = 'snackbar show'; // Reset classes and add show
        if (isError) {
            styledSnackBar.classList.add('error');
        }
        setTimeout(() => {
            styledSnackBar.className = 'snackbar'; // Hide
        }, 3000);
    }

    // Event Listeners for Modal
    if (closeProfileEditModalBtn) {
      closeProfileEditModalBtn.addEventListener('click', closeProfileEditModal);
    }

    // Close modal if overlay is clicked
    if (profileEditModal) {
        profileEditModal.addEventListener('click', function(event) {
            if (event.target === profileEditModal) { // Clicked on the overlay itself
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
            showStyledSnackBar('You are not logged in!', true);
            return;
        }

        const newName = modalProfileNameInput.value.trim();
        if (!newName) {
            showStyledSnackBar('Nama tidak boleh kosong.', true);
            return;
        }

        modalSaveProfileButton.disabled = true;
        modalSaveProfileButton.textContent = 'Saving...';

        try {
            const user = auth.currentUser;
            const userDocRef = db.collection("users").doc(user.uid);

            let newPhotoURL = currentUserData.photoUrl || user.photoURL; // Keep old photo if not changed

            // 1. Upload new photo if selected
            if (selectedImageFile) {
                const filePath = `profile_pictures/${user.uid}/${selectedImageFile.name}`;
                const fileRef = storage.ref().child(filePath);
                const uploadTask = await fileRef.put(selectedImageFile);
                newPhotoURL = await uploadTask.ref.getDownloadURL();
            }

            // 2. Update Firestore document
            await userDocRef.update({
                name: newName,
                photoUrl: newPhotoURL
            });

            // 3. Update Auth profile (optional but good for consistency)
            await user.updateProfile({
                displayName: newName,
                photoURL: newPhotoURL
            });

            // 4. Refresh local data and UI
            fetchAndDisplayUserData(user); // Re-fetch and update main page UI

            // 5. Close modal and show success
            closeProfileEditModal();
            showStyledSnackBar('Profil berhasil diperbarui');

        } catch (error) {
            console.error("Error updating profile:", error);
            showStyledSnackBar(`Gagal menyimpan: ${error.message}`, true);
        } finally {
            modalSaveProfileButton.disabled = false;
            modalSaveProfileButton.textContent = 'Save Changes';
        }
    }

    if (modalSaveProfileButton) {
        modalSaveProfileButton.addEventListener('click', handleProfileUpdate);
    }

</script>
@endpush