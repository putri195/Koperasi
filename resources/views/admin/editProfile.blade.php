<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Edit Profil - Koperasi</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
        <style>
            body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            }

            .admin-profile {
            position: fixed;
            top: 15px;
            right: 20px;
            font-size: 16px;
            z-index: 1000;
            }

            .logo-koperasi {
            padding: 20px;
            }

            .card {
            border: none;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            width: 100%;
            }

            .form-label {
            font-weight: 500;
            }

            .btn-simpan {
            background-color: #c9f267;
            color: #000;
            border: none;
            }

            .btn-batal {
            background-color: #777;
            color: #fff;
            border: none;
            }

            .btn-upload {
            background-color: #c9f267;
            border: none;
            color: #000;
            }

            .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto;
            }

            .full-width-container {
            width: 100%;
            padding: 20px 40px;
            margin-top: 20px;
            }
            .logo-header {
            margin-top: 20px;
            }

            .logo-header img {
            width: 30px;
            height: auto;
            }

            .logo-header strong {
            font-size: 20px;
            }

            .dropdown-menu {
                border: 1.5px solid rgba(40, 40, 40, 0.1);
                border-radius: 10px;
            }

            .dropdown-menu .dropdown-item:hover {
                background-color: #c9f267;
                border-radius: 8px;
                color: #000;
                margin: 0 8px;
                width: 90%;
            }
            @media (max-width: 768px) {
            .form-section {
                flex-direction: column;
            }
            }
        </style>
    </head>
    <body>

        <!-- Logo Koperasi -->
        <div class="logo-header d-flex align-items-center gap-2 my-3 ms-3" style="padding-left: 25px;">
            <img src="{{ asset('image/koperasi.png')}}" alt="Logo" width="30">
            <strong class="fs-4">KOPERASI</strong>
        </div>

        <!-- Profil Admin -->
        <div class="admin-profile dropdown">
            <a class="dropdown-toggle text-decoration-none text-dark" href="{{ route('profil.edit') }}" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                @if ($user->profile_photo)
                    <img src="{{ asset($user->profile_photo) }}"
                            alt="Profile Photo"
                            class="rounded-circle"
                            width="30" height="30">
                @else
                    <i class="bi bi-person-circle fs-4"></i>
                @endif
                {{ $user->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                <li>
                    <a class="dropdown-item" href="{{ route('profil.edit') }}">
                        <i class="bi bi-pencil-square me-2"></i>Edit Profil
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Konten Full Width -->
        <div class="full-width-container mt-3">
            <div class="card p-4 mx-auto">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <h3 class="fw-bold mb-4">Edit Profil</h3>
                <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')  
                    <div class="row form-section">
                        <!-- Form Input -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">No. Anggota:</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $member->member_number }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">First Name:</label>
                                <input type="text" class="form-control" name="first_name" value="{{ $firstName }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Name:</label>
                                <input type="text" class="form-control" name="last_name" value="{{ $lastName }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username:</label>
                                <input type="text" class="form-control" name="username" value="{{ $user->username }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <input type="password" class="form-control" name="password">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti password</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password:</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin:</label>
                                <select class="form-select" name="gender" required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki" {{ $member->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ $member->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir:</label>
                                <input type="date" class="form-control" name="birth_date" value="{{ $member->birth_date }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. HP:</label>
                                <input type="text" class="form-control" name="phone" value="{{ $member->phone }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat:</label>
                                <textarea class="form-control" name="address" rows="2" required>{{ $member->address }}</textarea>
                            </div>
                        </div>

                        <!-- Kolom Kanan - Foto Profil -->
                        <div class="col-md-4 text-center">
                            <div class="profile-photo mb-3">
                                @if($user->profile_photo)
                                    <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" class="img-fluid rounded-circle" style="width:100px; height:100px; object-fit:cover;">
                                @else
                                    <i class="bi bi-person-fill"></i>
                                @endif
                            </div>
                            <input type="file" name="profile_photo" accept="image/*" class="d-none" id="profile_photo">
                            <label for="profile_photo" class="btn btn-upload">Upload Photo</label>
                            <small id="file-name" class="text-muted d-block mt-2"></small>
                            <script>
                                document.getElementById('profile_photo').addEventListener('change', function () {
                                    const fileName = this.files[0]?.name || "Belum ada file dipilih";
                                    document.getElementById('file-name').innerText = "File terpilih: " + fileName;
                                });
                            </script>
                        </div>
                    </div>

                    <!-- Tombol Simpan & Batal -->
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-simpan px-4">Simpan</button>
                        <a href="{{ url()->previous() ?? route('dashboard') }}" class="btn btn-batal px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
            const fileInput = document.getElementById('profile_photo');
            const profilePreview = document.querySelector('.profile-photo');
            
            fileInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const file = this.files[0];

                    // Buat URL untuk gambar yang dipilih
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Ganti isi profilePreview dengan image preview
                        profilePreview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded-circle" style="width:100px; height:100px; object-fit:cover;">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>