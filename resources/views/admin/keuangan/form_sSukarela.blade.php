<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Simpanan</title>
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
        padding-top: 50px; 
        z-index: 999;
        box-shadow: 0 0px 8px rgba(0, 0, 0, 0.2);
        margin-left: -250px;
        }
        #sidebar.show {
        margin-left: 0;
        }
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
            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <h4 class="fw-bold mb-4">Form Simpanan Sukarela</h4>
            <form action="{{ route('simpanan.sukarela.tambah') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3 align-items-center">
                    <label for="tanggal" class="col-sm-3 col-form-label">Tanggal:</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control" id="tanggal" name="tanggal">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="keterangan" class="col-sm-3 col-form-label">Keterangan:</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="JumlahSimpanan" class="col-sm-3 col-form-label">Jumlah Simpanan:</label>
                    <div class="col-sm-9">
                        <input type="number" class="form-control" id="JumlahSimpanan" name="JumlahSimpanan">
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <label for="pilih_anggota" class="col-sm-3 col-form-label">Pilih anggota:</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="pilih_anggota" name="pilih_anggota">
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->member_number }} - {{ $member->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-success" onclick="window.location.href='{{ route('tambahTransaksi') }}';">Batal</button>
                </div>
            </form>
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