<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
        .submenu-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 10px;
            margin-top: 5px;
        }
        .admin-profile {
            position: fixed;
            top: 15px;
            right: 20px;
            font-size: 16px;
            z-index: 1000;
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
        .summary-card {
            border-radius: 15px;
            padding: 20px;
            text-align: left;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #c9f267;
        }
        .summary-card h6 {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .summary-card p {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
        .summary-icon {
            font-size: 2.2rem;
            opacity: 0.7;
        }
        .dropdown-toggle-custom::after {
            content: "▼";
            font-size: 0.6rem;
            margin-left: auto;
        }
        .dropdown-toggle-custom[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }
        .btn-cetak {
            background-color: #CCE77D;
            border: none;
            color: #000;
        }
        .btn-cetak:hover {
            background-color: #bbd96e;
            color: #000;
        }
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }
        .badge-lunas {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-belum-lunas {
            background-color: #f8d7da;
            color: #721c24;
        }
        /* Pagination default */
        .pagination .page-link {
            border: none !important;
            background: transparent !important;
            color: #000 !important; /* teks hitam */
            box-shadow: none !important;
        }

        /* Hover: kasih underline hitam */
        .pagination .page-link:hover {
            text-decoration: underline !important;
            text-decoration-color: #000 !important;
            background: transparent !important;
            color: #000 !important;
        }

        /* Halaman aktif */
        .pagination .page-item.active .page-link {
            background: transparent !important;
            font-weight: bold;
            text-decoration: underline !important;
            text-decoration-color: #000 !important;
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
                    <li><a href="{{ route('loan-settings.index') }}" class="nav-link sub-link">Setting Pinjaman</a></li>
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

    <!-- Konten -->
    <div id="content">
        <div class="card p-4">
            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <h4 class="fw-bold mb-4">Kas</h4>

            <!-- Filter, Pencarian, dan Tombol Cetak -->
            <div class="row align-items-center mb-3">
                <div class="col-lg-8">
                    <div class="d-flex gap-2 flex-wrap">
                        <!-- Pencarian dengan ikon search -->
                        <form method="GET" action="{{ route('kas') }}">
                        <div class="input-group" style="width: 580px;">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                class="form-control border-start-0" 
                                placeholder="Cari No. Anggota / Nama Anggota / Keterangan..." 
                                id="searchInput"
                                name="search" 
                                value="{{ request('search') }}">
                        </div>
                    </form>
                    </div>
                </div>

                <!-- Input Limit -->
                <div class="col-lg-2">
                    <select id="limitInput" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>

                <!-- Tombol Cetak -->
                <div class="col-lg-2 text-lg-end mt-2 mt-lg-0">
                    <button class="btn btn-cetak w-100 w-lg-auto">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                </div>
            </div>

            <!-- Tabel Pinjaman -->
            <div class="table-responsive">
                <table class="table align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal Transaksi</th>
                            <th>No. Anggota</th>
                            <th>Nama Anggota</th>
                            <th>Keterangan</th>
                            <th>ID Transaksi</th>
                            <th>Debit</th>
                            <th>Kredit</th>
                            <th>Saldo</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kas as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row['transaction_date'])->format('d/m/Y') }}</td>
                            <td>{{ $row['member_number'] }}</td>
                            <td>{{ $row['member_name'] }}</td>
                            <td>{{ $row['description'] }}</td>
                            <td>{{ $row['reference_id'] }}</td>
                            <td>{{ number_format($row['debit'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['credit'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('kas.destroy', $row['id']) }}" 
                                onclick="return confirm('Apakah Anda yakin menghapus transaksi ini?')" 
                                class="btn btn-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">Tidak ada data kas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="paginationInfo"></div>
                <nav>
                    <ul class="pagination mb-0" id="pagination"></ul>
                </nav>
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const table = document.querySelector(".table tbody");
            const rows = table.querySelectorAll("tr");
            const pagination = document.getElementById("pagination");
            const paginationInfo = document.getElementById("paginationInfo");
            const limitInput = document.getElementById("limitInput");
            const searchInput = document.getElementById("searchInput");

            let currentPage = 1;
            let rowsPerPage = parseInt(limitInput.value);

            function displayTable() {
                let start = (currentPage - 1) * rowsPerPage;
                let end = start + rowsPerPage;

                let visibleRows = Array.from(rows).filter(row => row.style.display !== "noneSearch");

                visibleRows.forEach((row, i) => {
                    if (i >= start && i < end) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });

                let totalPages = Math.ceil(visibleRows.length / rowsPerPage);
                updatePagination(totalPages, visibleRows.length);
            }

            function updatePagination(totalPages, totalRows) {
                pagination.innerHTML = "";
                paginationInfo.textContent = `Halaman ${currentPage} dari ${totalPages} (Total ${totalRows} data)`;

                // Prev
                let prev = document.createElement("li");
                prev.className = "page-item " + (currentPage === 1 ? "disabled" : "");
                prev.innerHTML = `<a class="page-link" href="#">&#8592;</a>`; // ← panah kiri
                prev.onclick = () => {
                    if (currentPage > 1) {
                        currentPage--;
                        displayTable();
                    }
                };
                pagination.appendChild(prev);

                // Nomor Halaman
                for (let i = 1; i <= totalPages; i++) {
                    let li = document.createElement("li");
                    li.className = "page-item " + (i === currentPage ? "active" : "");
                    li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                    li.onclick = () => {
                        currentPage = i;
                        displayTable();
                    };
                    pagination.appendChild(li);
                }

                // Next
                let next = document.createElement("li");
                next.className = "page-item " + (currentPage === totalPages ? "disabled" : "");
                next.innerHTML = `<a class="page-link" href="#">&#8594;</a>`; // → panah kanan
                next.onclick = () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        displayTable();
                    }
                };
                pagination.appendChild(next);
            }

            // Dropdown limit
            limitInput.addEventListener("change", () => {
                rowsPerPage = parseInt(limitInput.value);
                currentPage = 1;
                displayTable();
            });

            // Search
            searchInput.addEventListener("keyup", () => {
                const keyword = searchInput.value.toLowerCase();
                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(keyword) ? "" : "noneSearch";
                });
                currentPage = 1;
                displayTable();
            });

            displayTable();
        });
    </script>
</body>
</html>