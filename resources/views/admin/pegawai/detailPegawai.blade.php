<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Anggota - Koperasi</title>
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
        .btn-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        }
        .card {
        border: none;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        }
        .back-btn {
        background-color: #c9f267;
        color: #000;
        border: none;
        }
        .back-btn:hover {
        background-color: #b6de5a;
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

    <!-- Content -->
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
            <h4 class="fw-bold mb-4">Detail Pegawai</h4>
            <div class="row">
                <div class="col-md-8">
                    <p><strong>No. anggota:</strong> {{ $pegawai->member_number ?? '-' }}</p>
                    <p><strong>Nama:</strong> {{ $pegawai->user->name }}</p>
                    <p><strong>Jenis kelamin:</strong> {{ $pegawai->gender }}</p>
                    <p><strong>Tgl lahir:</strong> {{ \Carbon\Carbon::parse($pegawai->birth_date)->format('d - m - Y') }}</p>
                    <p><strong>Umur:</strong> {{ $umur }} Tahun</p>
                    <p><strong>Email:</strong> {{ $pegawai->user->email }}</p>
                    <p><strong>HP:</strong> {{ $pegawai->phone ?? '-' }}</p>
                    <p><strong>Alamat:</strong> {{ $pegawai->address ?? '-' }}</p>
                    <p><strong>Jabatan:</strong> {{ $pegawai->position }}</p>
                    <a href="{{ route('pegawai.index') }}" class="btn back-btn px-4 mt-3">Kembali</a>
                </div>
            </div>
        </div>
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