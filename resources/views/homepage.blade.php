@php
    $strukturLink = auth()->check() ? route('struktur.organisasi') : route('login.keycloak');
    $laporan1Link = auth()->check() ? '#' : route('login.keycloak');
    $laporan2Link = auth()->check() ? '#' : route('login.keycloak');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistem Koperasi Simpan Pinjam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
        font-family: 'Segoe UI', sans-serif;
        }

        .navbar-brand img {
        width: 35px;
        margin-right: 8px;
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

        .hero {
        background-color: #f8f9fa;
        padding: 30px 0;
        }

        .section-green-wrapper {
        background-color: transparent;
        padding: 30px 0;
        }

        .section-green {
        background-color: #018E3E;
        color: white;
        width: 88%;
        margin: auto;
        padding: 40px 20px;
        border-radius: 12px;
        }

        .contact-form {
        padding: 40px 0;
        }

        .bg-hijau-custom {
        background-color: #018E3E;
        }

        .dropdown-menu .dropdown-item {
        color: black;
        }

        .dropdown-menu .dropdown-item:hover,
        .dropdown-menu .dropdown-item:focus,
        .dropdown-menu .dropdown-item:active {
        background-color: #018E3E !important;
        color: white !important;
        }
        .timeline {
        position: relative;
        margin: 30px 0;
        padding: 0;
        list-style: none;
        }

        .timeline li {
        position: relative;
        padding-left: 50px;
        margin-bottom: 20px;
        color: white;
        }

        .timeline li::before {
        content: "";
        position: absolute;
        top: 0;
        left: 20px;
        width: 18px;
        height: 18px;
        background-color: #018E3E;
        border: 3px solid white;
        border-radius: 50%;
        z-index: 2;
        }

        .timeline li::after {
        content: "";
        position: absolute;
        top: 0;
        bottom: -20px;
        left: 28px;
        width: 2px;
        background-color: white;
        z-index: 1;
        }

        /* Hapus garis setelah bullet terakhir */
        .timeline li:last-child::after {
        display: none;
        }
        footer {
        background-color: #018E3E;
        color: white;
        text-align: center;
        padding: 10px 0;
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
                        <a class="nav-link active" href="{{ route('homepage') }}">Beranda</a>
                    </li>

                    <!-- Struktur Anggota -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="{{ $strukturLink }}">Struktur Organisasi</a>
                    </li>

                    <!-- Laporan Keuangan -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Laporan Keuangan</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ $laporan1Link }}">Laporan 1</a></li>
                            <li><a class="dropdown-item" href="{{ $laporan2Link }}">Laporan 2</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="ms-auto">
                    @guest
                        <!-- Tampilkan jika belum login -->
                        <a href="{{ route('login.keycloak') }}" class="btn btn-success me-2">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-success">Daftar</a>
                    @endguest

                    @auth
                        <!-- Tampilkan jika sudah login -->
                        <div class="dropdown">
                            <a class="dropdown-toggle text-decoration-none text-dark" href="{{ route('profil') }}" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profil') }}">Profil</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            @if (Session::has('warning'))
                <div class="alert alert-warning">
                    {{ Session::get('warning') }}
                </div>
            @endif
            <div class="row align-items-center">
                <div class="col-md-6 text-start">
                    <h2 class="fw-bold">SISTEM KOPERASI<br>SIMPAN PINJAM</h2><br>
                    <p>
                        Sistem Koperasi Simpan Pinjam adalah platform digital yang dirancang untuk memudahkan pengelolaan kegiatan koperasi secara modern dan transparan.
                        Melalui sistem ini, anggota koperasi dapat melakukan simpanan, pengajuan pinjaman, pelunasan, serta memantau transaksi secara real-time.
                    </p><br>
                    <a href="#fitur" class="btn btn-success">Go</a>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('image/animasi.png') }}" alt="Ilustrasi Koperasi" class="img-fluid" width="450">
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="section-green-wrapper text-center" id="fitur">
        <div class="section-green rounded">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('image/diskusi.jpg') }}" class="img-fluid rounded" alt="Meeting" width="350">
                    </div>
                    <div class="col-md-6 text-start text-white">
                        <h4>Sistem koperasi dapat melakukan berbagai kegiatan berikut:</h4>
                        <ul class="timeline">
                            <li>Pendaftaran anggota koperasi</li>
                            <li>Melakukan pinjaman</li>
                            <li>Melakukan simpanan</li>
                            <li>Melihat lapporan keuangan</li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="contact-form">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <form>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" id="nama" class="form-control" placeholder="Nama Anda">
                            </div>
                            <div class="col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" class="form-control" placeholder="Email Anda">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pesan" class="form-label">Pesan</label>
                            <textarea id="pesan" rows="4" class="form-control" placeholder="Pesan Anda..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <div class="bg-hijau-custom text-white p-4 rounded">
                        <h5>Contact Us</h5>
                        <p><strong>Alamat:</strong><br>Jl. Letja Sumbariyah No. 6, Dusun Makasare, Pabian, Kota Sumenep</p>
                        <p><strong>Hubungi:</strong><br>Telp: (0328) 662395<br>Email: diskominfo@sumenepkab.go.id</p>
                        <p><strong>Jam buka:</strong><br>Senin–Jumat: 08.00–15.30<br>Sabtu–Minggu: Libur</p>
                        <div>
                            <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-white me-2"><i class="bi bi-whatsapp"></i></a>
                            <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="mb-0">Copyright &copy; 2025 - Koperasi</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>