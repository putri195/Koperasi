<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Simpanan - Koperasi</title>
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
          <h4 class="fw-bold mb-4">Simpanan</h4>

          <!-- Filter, Pencarian, dan Tombol Cetak -->
          <div class="row align-items-center mb-3">
              <form method="GET" action="{{ route('simpanan.laporan') }}" class="row align-items-center mb-3">
                  <div class="col-lg-10">
                      <div class="d-flex gap-2 flex-wrap">
                          <!-- Pencarian dengan ikon search -->
                          <div class="input-group" style="width: 500px;">
                              <span class="input-group-text bg-white border-end-0">
                                  <i class="bi bi-search"></i>
                              </span>
                              <input type="text" name="search" class="form-control border-start-0"
                                    placeholder="Cari anggota..."
                                    value="{{ request('search') }}">
                          </div>

                          <select name="periode" class="form-select" style="width: 170px;" onchange="this.form.submit()">
                              <option value="">Pilih Periode</option>
                              @foreach($availablePeriods as $period)
                                  @php
                                      $value = $period->bulan . '-' . $period->tahun;
                                      $namaBulan = \Carbon\Carbon::create()->month($period->bulan)->translatedFormat('F');
                                      $selected = ($bulan == $period->bulan && $tahun == $period->tahun) ? 'selected' : '';
                                  @endphp
                                  <option value="{{ $value }}" {{ $selected }}>
                                      {{ $namaBulan }} {{ $period->tahun }}
                                  </option>
                              @endforeach
                          </select>
                      </div>
                  </div>

                  <!-- Tombol Cetak -->
                  <div class="col-lg-2 text-lg-end mt-2 mt-lg-0">
                      <a href="{{ route('simpanan.export', ['bulan' => $bulan, 'tahun' => $tahun, 'search' => request('search')]) }}"
                        class="btn btn-cetak w-100 w-lg-auto">
                          <i class="bi bi-printer"></i> Cetak
                      </a>
                  </div>
              </form>
          </div>

          <!-- Summary Cards -->
          <div class="row mb-4 text-center">
              <div class="col-md-4 mb-3">
                  <div class="summary-card">
                      <div>
                          <h6>Total Simpanan Pokok</h6>
                          <p>{{ number_format($summary->total_pokok, 0, ',', '.') }}</p>
                      </div>
                      <i class="bi bi-cash-stack summary-icon text-dark"></i>
                  </div>
              </div>
              <div class="col-md-4 mb-3">
                  <div class="summary-card">
                      <div>
                          <h6>Total Simpanan Wajib</h6>
                          <p>{{ number_format($summary->total_wajib, 0, ',', '.') }}</p>
                      </div>
                      <i class="bi bi-calendar-check summary-icon text-dark"></i>
                  </div>
              </div>
              <div class="col-md-4 mb-3">
                  <div class="summary-card">
                      <div>
                          <h6>Total Simpanan Sukarela</h6>
                          <p>{{ number_format($summary->total_sukarela, 0, ',', '.') }}</p>
                      </div>
                      <i class="bi bi-wallet2 summary-icon text-dark"></i>
                  </div>
              </div>
          </div>

            <!-- Tabel Simpanan -->
            <div class="table-responsive">
                @php
                    // ubah angka bulan ke nama bulan
                    $namaBulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');
                @endphp
                <table class="table align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="text-center align-middle">No. anggota</th>
                            <th rowspan="2" class="text-center align-middle">Nama anggota</th>
                            <th colspan="3" class="text-center align-middle">Sisa simpanan sebelumnya</th>
                            <th colspan="3" class="text-center align-middle">Pembayaran {{ $namaBulan }} {{ $tahun }}</th>
                            <th colspan="3" class="text-center align-middle">Pengambilan {{ $namaBulan }} {{ $tahun }}</th>
                            <th rowspan="2" class="text-center align-middle">Total</th>
                            <th rowspan="2" class="text-center align-middle">Aksi</th>
                        </tr>
                        <tr>
                            <th class="text-center align-middle">S. Pokok</th>
                            <th class="text-center align-middle">S. Wajib</th>
                            <th class="text-center align-middle">S. Sukarela</th>
                            <th class="text-center align-middle">S. Pokok</th>
                            <th class="text-center align-middle">S. Wajib</th>
                            <th class="text-center align-middle">S. Sukarela</th>
                            <th class="text-center align-middle">S. Pokok</th>
                            <th class="text-center align-middle">S. Wajib</th>
                            <th class="text-center align-middle">S. Sukarela</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            @php
                                // biar aman kalau null
                                $sblPokok    = $row['sebelumnya']->pokok    ?? 0;
                                $sblWajib    = $row['sebelumnya']->wajib    ?? 0;
                                $sblSukarela = $row['sebelumnya']->sukarela ?? 0;

                                $byrPokok    = $row['pembayaran']->pokok    ?? 0;
                                $byrWajib    = $row['pembayaran']->wajib    ?? 0;
                                $byrSukarela = $row['pembayaran']->sukarela ?? 0;

                                $ambilPokok    = $row['pengambilan']->pokok    ?? 0;
                                $ambilWajib    = $row['pengambilan']->wajib    ?? 0;
                                $ambilSukarela = $row['pengambilan']->sukarela ?? 0;

                            @endphp
                            <tr>
                                <td>{{ $row['member_number'] }}</td>
                                <td>{{ $row['name'] }}</td>

                                {{-- Simpanan Sebelumnya --}}
                                <td>{{ number_format($sblPokok, 0, ',', '.') }}</td>
                                <td>{{ number_format($sblWajib, 0, ',', '.') }}</td>
                                <td>{{ number_format($sblSukarela, 0, ',', '.') }}</td>

                                {{-- Pembayaran bulan terpilih --}}
                                <td>{{ number_format($byrPokok, 0, ',', '.') }}</td>
                                <td>{{ number_format($byrWajib, 0, ',', '.') }}</td>
                                <td>{{ number_format($byrSukarela, 0, ',', '.') }}</td>

                                {{-- Pengambilan bulan terpilih --}}
                                <td>{{ number_format($ambilPokok, 0, ',', '.') }}</td>
                                <td>{{ number_format($ambilWajib, 0, ',', '.') }}</td>
                                <td>{{ number_format($ambilSukarela, 0, ',', '.') }}</td>

                                {{-- Saldo akhir --}}
                                <td>{{ $row['total'] }}</td>

                                {{-- Aksi --}}
                                <td>
                                    <a href="{{ route('simpanan.edit', ['id' => $row['member_id'], 'tahun' => $tahun, 'bulan' => $bulan]) }}" 
                                    class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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