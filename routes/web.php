


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\KeycloakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute untuk Autentikasi Keycloak ---

// Rute ini akan menjadi titik masuk utama untuk login.
// Ketika pengguna mencoba mengakses rute yang memerlukan otentikasi dan belum login,
// Laravel akan mengarahkan mereka ke rute dengan nama 'login'.
// Kita akan mengarahkan langsung ke proses otentikasi Keycloak.
Route::get('/auth/keycloak', [KeycloakController::class, 'redirectToKeycloak'])->name('login.keycloak');

// Rute callback dari Keycloak setelah autentikasi berhasil
Route::get('/auth/keycloak/callback', [KeycloakController::class, 'handleKeycloakCallback']);

Route::get('/redirect', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login.keycloak');
    }

    switch ($user->role) {
        case 'admin':
            return redirect()->route('dashboard');
        case 'anggota':
            return redirect()->route('homepage');
        default:
            abort(403, 'Role tidak dikenal');
    }
})->name('redirect.role');

// Route POST utama (sudah benar)
Route::post('/logout', [KeycloakController::class, 'logout'])->name('logout');

// Tambahan GET agar bisa redirect jika token expired
Route::get('/logout', function () {
    return redirect()->route('logout')->with('error', 'Sesi kamu sudah habis. Silakan login ulang.');
});

Route::middleware(['auth'])->group(function () {
    //Homepage User
    Route::get('/homepage', [HomeController::class, 'index'])->name('homepage');
    Route::get('/struktur-organisasi', [HomeController::class, 'strukturOrganisasi'])->name('struktur.organisasi');

    //profil
    Route::get('/profil-saya', [HomeController::class, 'showProfile'])->name('profil');
    Route::get('/edit', [HomeController::class, 'editProfile'])->name('user.edit');
    Route::put('/update', [HomeController::class, 'updateProfile'])->name('user.update');

    //keuangan
    Route::get('/laporan-simpanan', [HomeController::class, 'laporan'])->name('simpanan.laporanUser');
});

// Contoh rute dashboard yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil/edit', [DashboardController::class, 'editProfile'])->name('profil.edit');
    Route::put('/profil/update', [DashboardController::class, 'updateProfile'])->name('profil.update');

    //Anggota
    Route::get('/dashboard/anggota', [DashboardController::class, 'indexMembers'])->name('anggota.index');
    Route::match(['get', 'post'], 'dashboard/anggota/tambah', [DashboardController::class, 'storeMember'])->name('anggota.store');
    Route::get('/dashboard/anggota/{id}', [DashboardController::class, 'detailMember'])->name('anggota.detail');
    Route::get('/dashboard/anggota/edit/{id}', [DashboardController::class, 'editMember'])->name('anggota.edit');
    Route::put('/dashboard/anggota/edit/{id}/update', [DashboardController::class, 'updateMember'])->name('anggota.update');
    Route::delete('/dashboard/anggota/hapus/{id}', [DashboardController::class, 'deleteMember'])->name('anggota.hapus');
    Route::get('/dashboard/anggota/simpanan/{member_id}', [DashboardController::class, 'simpanan'])->name('simpanan.anggota');

    // Pegawai
    Route::get('/pegawai', [DashboardController::class, 'indexPegawai'])->name('pegawai.index');
    Route::get('/pegawai/{id}', [DashboardController::class, 'detailPegawai'])->name('pegawai.detail');
    Route::put('/pegawai/{id}/ubah', [DashboardController::class, 'ubahMenjadiAnggota'])->name('pegawai.ubah');

    //Catatan Keuangan
    Route::get('/tambah_transaksi', function () {return view('admin.keuangan.tambahTransaksi'); })->name('tambahTransaksi');
    Route::get('/kas', [DashboardController::class, 'kas'])->name('kas');
    Route::delete('/kas/hapus/{id}', [DashboardController::class, 'kas_destroy'])->name('kas.destroy');

    /* Simpanan Pokok */
    Route::get('/simpanan/pokok', [SimpananController::class, 'tabelSPokok'])->name('simpanan.pokok');
    Route::get('/simpanan/pokok/export', [SimpananController::class, 'exportSpokok'])->name('simpanan.pokok.export');
    Route::get('/simpanan/pokok/tambah', [SimpananController::class, 'indexSPokok'])->name('simpanan.pokok.index');
    Route::post('/simpanan/pokok/create', [SimpananController::class, 'tambahSimpananPokok'])->name('simpanan.pokok.tambah');

    /* Simpanan Wajib */
    Route::get('/simpanan/wajib', [SimpananController::class, 'tabelSWajib'])->name('simpanan.wajib');
    Route::get('/simpanan/wajib/export', [SimpananController::class, 'exportSwajib'])->name('simpanan.wajib.export');
    Route::get('/simpanan/wajib/tambah', [SimpananController::class, 'indexSWajib'])->name('simpanan.wajib.index');
    Route::post('/simpanan/wajib/create', [SimpananController::class, 'tambahSimpananWajib'])->name('simpanan.wajib.tambah');

    /* Simpanan Sukarela */
    Route::get('/simpanan/sukarela', [SimpananController::class, 'tabelSSukarela'])->name('simpanan.sukarela');
    Route::get('/simpanan/sukarela/export', [SimpananController::class, 'exportSsukarela'])->name('simpanan.sukarela.export');
    Route::get('/simpanan/sukarela/tambah', [SimpananController::class, 'indexSSukarela'])->name('simpanan.sukarela.index');
    Route::post('/simpanan/sukarela/create', [SimpananController::class, 'tambahSimpananSukarela'])->name('simpanan.sukarela.tambah');

    /*Laporan Simpanan */
    Route::get('/laporan/simpanan', [SimpananController::class, 'laporan'])->name('simpanan.laporan');
    Route::get('/laporan/simpanan/export', [SimpananController::class, 'export'])->name('simpanan.export');
    Route::get('/simpanan/{id}/edit/{tahun}/{bulan}', [SimpananController::class, 'edit'])->name('simpanan.edit');
    Route::put('/simpanan/{id}/edit/update/{tahun}/{bulan}', [SimpananController::class, 'update'])->name('simpanan.update');

    /*Pinjaman */
    Route::get('/setting-pinjaman', [PinjamanController::class, 'loan_setting'])->name('loan-settings.index');
    Route::post('/setting-pinjaman', [PinjamanController::class, 'loan_setting_update'])->name('loan-settings.store');
});


Route::get('/token', function () {
    return response()->json([
        'token' => session('keycloak_access_token'),
        'refresh_token' => session('keycloak_refresh_token'),
        'expires_in' => session('keycloak_expires_in'),
    ]);
});

// Contoh halaman login sederhana (bisa berupa tombol "Login with Keycloak")
Route::get('/logout/callback', function () {
    return redirect('/'); // atau tampilan logout sukses
})->name('logout.callback');


Route::get('/register', function () {
    return view('auth.register'); // Buat view auth/login.blade.php
})->name('register');

Route::post('/register', [\App\Http\Controllers\Auth\KeycloakController::class, 'register'])->name('register.submit');

// Rute default (halaman utama)
Route::get('/', function () {
    // Anda bisa menambahkan tombol "Login dengan Keycloak" di sini juga jika mau
    return view('homepage');
});