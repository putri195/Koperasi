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
        .btn-success {
        background-color: #c9f267;
        color: #000;
        border: none;
        }
        .btn-success:hover {
        background-color: #b8df5c;
        color: #000;
        }
        .btn-warning {
        background-color: #ffc107;
        border: none;
        color: #000;
        }
        .btn-danger {
        background-color: #dc3545;
        border: none;
        color: #fff;
        }
        .card {
        border: none;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        }
        .input-group-text {
        border-right: none;
        background-color: #fff;
        }
        .form-control {
        border-left: none;
        }
        .form-control:focus, .input-group-text:focus {
        box-shadow: none;
        }
        .table {
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
        border: 1px solid #dee2e6;
        border-radius: 10px;
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
        .table td, .table th {
        border: 1px solid #dee2e6;
        }
        .dropdown-toggle-custom::after {
        content: "▼";
        font-size: 0.6rem;
        margin-left: auto;
        }
        .dropdown-toggle-custom[aria-expanded="true"]::after {
        transform: rotate(180deg);
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
            <li>
                <a href="#submenuSimpanan" data-bs-toggle="collapse" class="nav-link dropdown-toggle-custom d-flex justify-content-between align-items-center" id="toggleSimpanan">
                <span><i class="bi bi-folder"></i> Simpanan</span>
                </a>
                <div class="collapse" id="submenuSimpanan">
                <ul class="nav flex-column">
                    <li><a href="{{ route('simpanan.laporan') }}" class="nav-link sub-link">Simpanan</a></li>
                    <li><a href="{{ route('simpanan.pokok') }}" class="nav-link sub-link">Simpanan Pokok</a></li>
                    <li><a href="{{ route('simpanan.wajib') }}" class="nav-link sub-link">Simpanan Wajib</a></li>
                    <li><a href="{{ route('simpanan.sukarela') }}" class="nav-link sub-link">Simpanan Sukarela</a></li>
                </ul>
                </div>
            </li>
            <li>
                <a href="#submenuPinjaman" data-bs-toggle="collapse" class="nav-link dropdown-toggle-custom d-flex justify-content-between align-items-center" id="togglePinjaman">
                <span><i class="bi bi-journal-text"></i> Pinjaman</span>
                </a>
                <div class="collapse" id="submenuPinjaman">
                <ul class="nav flex-column">
                    <li><a href="#" class="nav-link sub-link">Pinjaman</a></li>
                    <li><a href="#" class="nav-link sub-link">Setting Pinjaman</a></li>
                </ul>
                </div>
            </li>

            <li><a href="#" class="nav-link"><i class="bi bi-cash"></i> Angsuran</a></li>
            <li>
                <a href="#submenuKeuangan" data-bs-toggle="collapse" class="nav-link dropdown-toggle-custom d-flex justify-content-between align-items-center" id="toggleKeuangan">
                <span><i class="bi bi-currency-dollar"></i> Keuangan</span>
                </a>
                <div class="collapse" id="submenuKeuangan">
                <ul class="nav flex-column">
                    <li><a href="{{ route('tambahTransaksi') }}" class="nav-link sub-link">Tambah transaksi</a></li>
                    <li><a href="{{ route('kas') }}" class="nav-link sub-link">Kas</a></li>
                </ul>
                </div>
            </li>
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
        <!-- Judul Anggota -->
            <div class="mb-3">
                <h4 class="fw-bold mb-0">Anggota</h4>
            </div>

            <!-- Pencarian + Tombol Tambah -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <form action="{{ route('anggota.index') }}" method="GET" class="flex-grow-1" style="max-width: 700px;">
                    <div class="input-group position-relative">
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
                <a href="{{ route('anggota.store') }}" class="btn btn-success rounded-3 px-3 mt-2 mt-md-0">+ Tambah Anggota</a>
            </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-middle text-center">
                <thead class="table-light">
                    <tr>
                    <th>No. anggota</th>
                    <th>Nama</th>
                    <th>Tgl lahir</th>
                    <th>Email</th>
                    <th>HP</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>{{ $member->member_number ?? '-' }}</td>
                            <td>{{ $member->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($member->birth_date)->format('d - m - Y') }}</td>
                            <td>{{ $member->user->email ?? '-' }}</td>
                            <td>{{ $member->phone ?? '-' }}</td>
                            <td>
                                <a href="{{ route('anggota.detail', ['id' => $member->id]) }}" class="btn btn-primary btn-sm me-1"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('anggota.edit', $member->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
                                <form action="{{ route('anggota.hapus', $member->id) }}" method="POST" class="d-inline" 
                                    onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada anggota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($members->hasPages())
            <nav>
                <ul class="pagination justify-content-center mt-4">
                    {{-- Tombol Previous --}}
                    @if ($members->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">«</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $members->previousPageUrl() }}&search={{ request('search') }}" rel="prev">«</a>
                        </li>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($members->getUrlRange(1, $members->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $members->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&search={{ request('search') }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($members->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $members->nextPageUrl() }}&search={{ request('search') }}" rel="next">»</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">»</span>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const submenu = document.getElementById('submenuSimpanan');
        const simpananToggle = document.getElementById('simpananMenuToggle');

        // ====== SIDEBAR UTAMA (BUKA/TUTUP) ======
        const isSidebarOpen = localStorage.getItem('sidebarOpen') === 'true';

        if (isSidebarOpen) {
            sidebar.classList.add('show');
            content.classList.remove('full');
        } else {
            sidebar.classList.remove('show');
            content.classList.add('full');
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            content.classList.toggle('full');
            localStorage.setItem('sidebarOpen', sidebar.classList.contains('show'));
        });

        // ====== SUBMENU SIMPANAN ======
        const submenuSimpanan = document.getElementById('submenuSimpanan');
        const toggleSimpanan = document.getElementById('toggleSimpanan');
        const simpananOpen = localStorage.getItem('simpananDropdownOpen') === 'true';

        if (simpananOpen) {
        submenuSimpanan.classList.add('show');
        toggleSimpanan.setAttribute('aria-expanded', 'true');
        }

        toggleSimpanan.addEventListener('click', () => {
        const isOpen = submenuSimpanan.classList.contains('show');
        setTimeout(() => {
            localStorage.setItem('simpananDropdownOpen', !isOpen);
        }, 100);
        });

        // ====== SUBMENU KEUANGAN ======
        const submenuKeuangan = document.getElementById('submenuKeuangan');
        const toggleKeuangan = document.getElementById('toggleKeuangan');
        const keuanganOpen = localStorage.getItem('keuanganDropdownOpen') === 'true';

        if (keuanganOpen) {
        submenuKeuangan.classList.add('show');
        toggleKeuangan.setAttribute('aria-expanded', 'true');
        }

        toggleKeuangan.addEventListener('click', () => {
        const isOpen = submenuKeuangan.classList.contains('show');
        setTimeout(() => {
            localStorage.setItem('keuanganDropdownOpen', !isOpen);
        }, 100);
        });

        // ====== SUBMENU PINJAMAN ======
        const submenuPinjaman = document.getElementById('submenuPinjaman');
        const togglePinjaman = document.getElementById('togglePinjaman');
        const pinjamanOpen = localStorage.getItem('pinjamanDropdownOpen') === 'true';

        if (pinjamanOpen) {
        submenuPinjaman.classList.add('show');
        togglePinjaman.setAttribute('aria-expanded', 'true');
        }

        togglePinjaman.addEventListener('click', () => {
        const isOpen = submenuPinjaman.classList.contains('show');
        setTimeout(() => {
            localStorage.setItem('pinjamanDropdownOpen', !isOpen);
        }, 100);
        });
    </script>
</body>
</html>