@php
    $strukturLink = auth()->check() ? route('struktur.organisasi') : route('login.keycloak');
    $laporan1Link = auth()->check() ? route('simpanan.laporanUser') : route('login.keycloak');
    $laporan2Link = auth()->check() ? '#' : route('login.keycloak');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Struktur Anggota - Koperasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        }

        .navbar-brand img {
        width: 35px;
        margin-right: 8px;
        }

        .table-container {
        padding: 40px 20px;
        flex: 1;
        }

        table {
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
        border: 1px solid #ccc; /* border luar tabel */
        }

        /* border antar cell */
        table th,
        table td {
        border: 1px solid #ccc;
        padding: 8px 12px;
        text-align: center;
        vertical-align: middle;
        }

        table thead th:first-child {
        border-top-left-radius: 10px;
        }
        table thead th:last-child {
        border-top-right-radius: 10px;
        }

        footer {
        background-color: #018E3E;
        color: white;
        text-align: center;
        padding: 10px 0;
        }

        .dropdown-item.active {
        background-color: #4caf50;
        color: white;
        }

        .dropdown-item:hover {
        background-color: #4caf50;
        color: white;
        }

        .navbar-center {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        }

        .navbar-nav-center {
        display: flex;
        gap: 20px;
        }

        .pagination {
        display: flex;
        justify-content: center;
        margin-top: 15px;
        }

        .pagination li {
        margin: 0 5px;
        }

        .pagination li a {
        background: none !important;
        border: none !important;
        color: black !important;
        font-weight: normal;
        font-size: 14px;
        cursor: pointer;
        }


        .pagination li.active a {
        text-decoration: underline;
        }

        .pagination li.disabled a {
        color: #aaa !important;
        pointer-events: none;
        }

        html {
        scroll-behavior: smooth;
        }
    </style>

</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm px-4 sticky-top">

        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center fw-bold text-dark" href="{{ route('homepage') }}">
                <img src="{{ asset('image/koperasi.png') }}" alt="Logo" /> KOPERASI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav navbar-center navbar-nav-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('homepage') }}">Beranda</a>
                    </li>

                    <!-- Struktur Anggota -->
                    <li class="nav-item dropdown">
                        <a class="nav-link active" href="{{ $strukturLink }}">Struktur Organisasi</a>
                    </li>

                    <!-- Laporan Keuangan -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Laporan Keuangan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ $laporan1Link }}">Laporan Simpanan</a></li>
                            <li><a class="dropdown-item" href="{{ $laporan2Link }}">Laporan 2</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle text-decoration-none text-dark" href="{{ route('profil') }}" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (isset($user) && $user->profile_photo)
                                <img src="{{ asset($user->profile_photo) }}"
                                    alt="Profile Photo"
                                    class="rounded-circle"
                                    width="30" height="30">
                            @else
                                <i class="bi bi-person-circle fs-4"></i>
                            @endif
                            {{ $user->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profil') }}">Profil</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten -->
    <div class="container table-container">
        <!-- Data Pegawai -->
        <h4 id="data-pegawai" class="fw-bold mb-3">Data Pegawai</h4>
        <div class="table-responsive">
            <table class="table align-middle" id="tabel-pegawai">

                <thead class="table-light">
                    <tr>
                        <th>No. anggota</th>
                        <th>Nama</th>
                        <th>Jenis kelamin</th>
                        <th>Email</th>
                        <th>HP</th>
                        <th>Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pegawai as $item)
                        <tr>
                            <td>{{ $item->member_number }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->gender }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->position }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav><ul class="pagination" id="pagination-pegawai"></ul></nav>

        <!-- Data Anggota -->
        <h4 id="data-anggota" class="fw-bold mt-5 mb-3">Data Anggota</h4>
        <div class="table-responsive">
            <table class="table align-middle" id="tabel-anggota">

                <thead class="table-light">
                    <tr>
                        <th>No. anggota</th>
                        <th>Nama</th>
                        <th>Jenis kelamin</th>
                        <th>Tgl lahir</th>
                        <th>Email</th>
                        <th>HP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggota as $item)
                        <tr>
                            <td>{{ $item->member_number }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->gender }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->birth_date)->format('d - m - Y') }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <nav><ul class="pagination" id="pagination-anggota"></ul></nav>
    </div>

    <!-- Footer -->
    <footer>
        Copyright &copy; 2025 - Koperasi
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function paginateTable(tableId, paginationId, rowsPerPage = 5) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector("tbody");
        const rows = tbody.querySelectorAll("tr");
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        const pagination = document.getElementById(paginationId);

        let currentPage = 1;

        function showPage(page) {
            currentPage = page;
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? "" : "none";
            });

            renderPagination();
        }

        function renderPagination() {
            pagination.innerHTML = "";

            const prev = document.createElement("li");
            prev.className = page-item ${currentPage === 1 ? "disabled" : ""};
            prev.innerHTML = <a class="page-link" href="#">«</a>;
            prev.onclick = () => currentPage > 1 && showPage(currentPage - 1);
            pagination.appendChild(prev);

            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement("li");
                li.className = page-item ${i === currentPage ? "active" : ""};
                li.innerHTML = <a class="page-link" href="#">${i}</a>;
                li.onclick = () => showPage(i);
                pagination.appendChild(li);
            }

            const next = document.createElement("li");
            next.className = page-item ${currentPage === totalPages ? "disabled" : ""};
            next.innerHTML = <a class="page-link" href="#">»</a>;
            next.onclick = () => currentPage < totalPages && showPage(currentPage + 1);
            pagination.appendChild(next);
        }

        showPage(1);
        }

        document.addEventListener("DOMContentLoaded", function () {
            paginateTable("tabel-pegawai", "pagination-pegawai", 5);
            paginateTable("tabel-anggota", "pagination-anggota", 5);
        });
    </script>
</body>
</html>