<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Pegawai - Koperasi</title>
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
            .card {
            border: none;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            }
            .table {
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            }
            .table td, .table th {
            border: 1px solid #dee2e6;
            }
            .table thead th:first-child {
            border-top-left-radius: 10px;
            }
            .table thead th:last-child {
            border-top-right-radius: 10px;
            }
            .table tbody tr:last-child td:first-child {
            border-bottom-left-radius: 10px;
            }
            .table tbody tr:last-child td:last-child {
            border-bottom-right-radius: 10px;
            }
            .btn-success {
            background-color: #c9f267;
            color: #000;
            border: none;
            }
            .btn-success:hover {
            background-color: #b8df5c;
            }
            .btn-primary {
            background-color: #007bff;
            border: none;
            }
            .btn-warning {
            background-color: #ffc107;
            color: #000;
            border: none;
            }
            .btn-danger {
            background-color: #dc3545;
            border: none;
            color: #fff;
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

    <!-- Konten Pegawai -->
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
            <h4 class="fw-bold mb-4">Pegawai</h4>

            <form method="GET" action="{{ route('pegawai.index') }}">
                <div class="input-group mb-4 position-relative" style="max-width: 100%;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" name="search" class="form-control border-start-0 pe-5"
                        placeholder="Cari berdasarkan nama, nomor anggota, atau jabatan"
                        value="{{ request('search') }}">

                    <button type="button" id="clearSearch" class="btn position-absolute end-0 top-50 translate-middle-y me-2 d-none"
                        style="z-index: 5;">
                        <i class="bi bi-x-circle-fill text-muted"></i>
                    </button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No. anggota</th>
                            <th>Nama</th>
                            <th>Jenis kelamin</th>
                            <th>Email</th>
                            <th>HP</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pegawai as $pegawai)
                            <tr>
                                <td>{{ $pegawai->member_number ?? '-' }}</td>
                                <td>{{ $pegawai->user->name ?? '-' }}</td>
                                <td>{{ $pegawai->gender ?? '-' }}</td>
                                <td>{{ $pegawai->user->email ?? '-' }}</td>
                                <td>{{ $pegawai->phone ?? '-' }}</td>
                                <td>{{ $pegawai->position ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('pegawai.detail', $pegawai->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                                    <form action="{{ route('pegawai.ubah', $pegawai->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ubah jabatan pegawai ini menjadi anggota?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // === Sidebar Toggle & LocalStorage ===
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        // Ambil status dari localStorage
        const isSidebarOpen = localStorage.getItem('sidebarOpen') === 'true';

        // Set tampilan awal sesuai status disimpan
        if (isSidebarOpen) {
            sidebar.classList.add('show');
            content.classList.remove('full');
        } else {
            sidebar.classList.remove('show');
            content.classList.add('full');
        }

        // Toggle saat tombol ditekan
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            content.classList.toggle('full');

            // Simpan status ke localStorage
            localStorage.setItem('sidebarOpen', sidebar.classList.contains('show'));
        });

        // === Input Search Clear Button ===
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');

        // Tampilkan tombol X jika ada isi
        function toggleClearButton() {
            if (searchInput && clearBtn) {
            if (searchInput.value.trim().length > 0) {
                clearBtn.classList.remove('d-none');
            } else {
                clearBtn.classList.add('d-none');
            }
            }
        }

        // Saat input berubah
        if (searchInput) {
            searchInput.addEventListener('input', toggleClearButton);
        }

        // Saat tombol X ditekan
        if (clearBtn) {
            clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            clearBtn.classList.add('d-none');
            searchInput.focus();

            // Optional: auto submit form setelah clear
            searchInput.closest('form').submit();
            });
        }

        // Jalankan saat awal jika input sudah ada nilainya
        toggleClearButton();
    </script>

</body>
</html>