<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Anggota - Koperasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
        min-height: 100vh;
        margin: 0;
        padding: 0;
        }
        #sidebar {
        width: 240px;
        background-color: #fff;
        border-right: 1px solid #e0e0e0;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding: 20px;
        padding-top: 50px; /* ✅ Tambahkan padding atas agar tidak kepotong navbar */
        z-index: 999;
        box-shadow: 0 0px 8px rgba(0, 0, 0, 0.2);
        margin-left: -250px;
        }
        #sidebar.show {
        margin-left: 0; /* buka */
        }
        /* #sidebar.collased {
        margin-left: -250px;
        } */
        #sidebar .nav-link:hover {
        background-color: #c9f267 !important;
        border-radius: 8px;
        color: #000 !important;
        font-weight: 500;
        }
        #toggleSidebar {
        border: none;
        background-color: transparent;
        }
        #toggleSidebar:hover {
        background-color: #c9f267;
        border-radius: 8px;
        }
        .navbar {
        position: fixed;
        width: 100%;
        z-index: 1000;
        }
        .nav-link {
        color: #000 !important;
        }
        .nav-link.active {
        background-color: #c9f267;
        font-weight: bold;
        color: #000 !important;
        border-radius: 8px;
        box-shadow: 0 0px 8px rgba(0, 0, 0, 0.2);
        }
        .nav-link i {
        margin-right: 8px;
        }
        #content {
        margin-left: 260px;
        padding: 40px;
        padding-top: 95px;
        /* transition: margin-left 0.3s; */
        }
        #content.full {
        margin-left: 0;
        }
        .admin-profile {
        position: fixed;
        top: 15px;
        right: 20px;
        font-size: 16px;
        z-index: 1000;
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
        .btn-success {
        background-color: #c9f267;
        color: #000;
        border: none;
        }
        .btn-success:hover {
        background-color: #b8df5c;
        }
        .card {
        border: none;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        }
        .form-label {
        font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary" id="toggleSidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset('image/koperasi.png')}}" alt="Logo" width="30">
                    <strong class="fs-5">KOPERASI</strong>
                </div>
            </div>

            <!-- Admin Profile -->
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
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column">
        <ul class="nav nav-pills flex-column mb-auto mt-4">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </li>
            <li><a href="{{ route('pegawai.index') }}" class="nav-link"><i class="bi bi-person"></i> Pegawai</a></li>
            <li><a href="{{ route('anggota.index') }}" class="nav-link"><i class="bi bi-people"></i> Anggota</a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-folder"></i> Simpanan</a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-journal-text"></i> Pinjaman</a></li>
            <li><a href="#" class="nav-link"><i class="bi bi-cash"></i> Angsuran</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="content">
        <div class="card p-4">
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

            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif    

            <h4 class="fw-bold mb-4">Edit Anggota</h4>
            <form action="{{ route('anggota.update', $member->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3 align-items-center">
                    <label for="member_number" class="col-sm-3 col-form-label">No. Anggota:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="member_number" name="member_number" value="{{ old('member_number', $member->member_number) }}">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="name" class="col-sm-3 col-form-label">Nama:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="name" name="name" value="{{ $member->user->name }}" disabled>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="gender" class="col-sm-3 col-form-label">Jenis Kelamin:</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="gender" name="gender">
                            <option value="Laki-laki" {{ $member->gender == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ $member->gender == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="birth_date" class="col-sm-3 col-form-label">Tgl Lahir:</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $member->birth_date) }}">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="email" class="col-sm-3 col-form-label">Email:</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" value="{{ $member->user->email }}" disabled>
                    </div>
                </div>
                
                <div class="row mb-3 align-items-center">
                    <label for="phone" class="col-sm-3 col-form-label">HP:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $member->phone) }}">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="address" class="col-sm-3 col-form-label">Alamat:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $member->address) }}">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="position" class="col-sm-3 col-form-label">Jabatan:</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="position" name="position">
                            @foreach(['Anggota', 'Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Pengawas', 'Manajer', 'Staff'] as $jabatan)
                                <option value="{{ $jabatan }}" {{ $member->position === $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="{{ route('anggota.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        // Cek status sidebar dari localStorage saat halaman dimuat
        const isSidebarOpen = localStorage.getItem('sidebarOpen') === 'true';

        if (isSidebarOpen) {
        sidebar.classList.add('show');
        content.classList.remove('full');
        } else {
        sidebar.classList.remove('show');
        content.classList.add('full');
        }

        // Toggle sidebar saat tombol ditekan
        toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
        content.classList.toggle('full');
        localStorage.setItem('sidebarOpen', sidebar.classList.contains('show'));
        });
    </script>
</body>
</html>