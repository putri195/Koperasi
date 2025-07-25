<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Saya - Koperasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
        min-height: 100vh;
        margin: 0;
        padding: 0;
        }

        .fixed-header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1030;
        background-color: white;
        border-bottom: 1px solid #ddd;
        padding: 12px 25px;
        }

        .logo-header {
        display: flex;
        align-items: center;
        gap: 10px;
        }

        .logo-header img {
        width: 30px;
        height: auto;
        }

        .logo-header strong {
        font-size: 20px;
        }

        .admin-profile .dropdown-toggle {
        font-size: 16px;
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

        .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 64px;
        color: #777;
        margin: 0 auto 20px;
        }

        .label {
        font-weight: 500;
        color: #555;
        }

        .value {
        font-weight: 600;
        }

        .inner-container {
        max-width: 700px;
        margin: 0 auto;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="fixed-header d-flex justify-content-between align-items-center">
        <div class="logo-header">
            <img src="{{ asset('image/koperasi.png') }}" alt="Logo" />
            <strong class="fs-4">KOPERASI</strong>
        </div>

        <div class="admin-profile dropdown">
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

    <!-- Konten Profil -->
    <div class="container pt-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Profil Saya</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('homepage') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
                <a href="{{ route('user.edit') }}" class="btn btn-success">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profil
                </a>
            </div>
        </div>

        <div class="card p-4">
            @if (session('warning'))
                <div class="alert alert-warning mt-4">
                    {{ session('warning') }}
                </div>
            @endif

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
            <!-- Foto Profil -->
            <div class="text-center">
                <!-- Jika belum upload gambar, tampilkan icon -->
                <div class="profile-photo">
                    @if($user->profile_photo)
                        <img src="{{ asset($user->profile_photo) }}" alt="Profile Photo" class="img-fluid rounded-circle" style="width:115px; height:115px; object-fit:cover;">
                    @else
                        <i class="bi bi-person-circle"></i>
                    @endif
                </div>
            </div>

            <!-- Container Isi Profil -->
            <div class="d-flex justify-content-center">
                <div class="row mt-3 w-50 ps-5"> <!-- Atur lebar agar tidak terlalu melebar -->
                    <div class="col-md-6 mb-3">
                        <div class="label ps-2">No. Anggota</div>
                        <div class="value ps-2">{{ $member?->member_number ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="label ps-5">Username</div>
                        <div class="value ps-5">{{ $user->username ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="label ps-2">Nama Lengkap</div>
                        <div class="value ps-2">{{ $user->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="label ps-5">Email</div>
                        <div class="value ps-5">{{ $user->email ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="label ps-2">Jenis Kelamin</div>
                        <div class="value ps-2">{{ $member?->gender ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="label ps-5">Tanggal Lahir</div>
                        <div class="value ps-5">{{ $member?->birth_date ? \Carbon\Carbon::parse($member->birth_date)->format('d-m-Y') : '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="label ps-2">No. HP</div>
                        <div class="value ps-2">{{ $member?->phone ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="label ps-5">Alamat</div>
                        <div class="value ps-5">{{ $member?->address ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>