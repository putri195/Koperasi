


<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\KeycloakController;
use App\Http\Controllers\DashboardController;
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
});

// Contoh rute dashboard yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil/edit', [DashboardController::class, 'editProfile'])->name('profil.edit');
    Route::put('/profil/update', [DashboardController::class, 'updateProfile'])->name('profil.update');

    //Anggota
    Route::get('/dashboard/anggota', [DashboardController::class, 'indexMembers'])->name('anggota.index');
    Route::match(['get', 'post'], 'dashboard/anggota/tambah', [DashboardController::class, 'storeMember'])->name('anggota.store');
    Route::get('/anggota/{id}', [DashboardController::class, 'detailMember'])->name('anggota.detail');
    Route::get('/anggota/{id}/edit', [DashboardController::class, 'editMember'])->name('anggota.edit');
    Route::put('/anggota/{id}', [DashboardController::class, 'updateMember'])->name('anggota.update');
    Route::delete('/anggota/{id}/hapus', [DashboardController::class, 'deleteMember'])->name('anggota.hapus');

    // Pegawai
    Route::get('/pegawai', [DashboardController::class, 'indexPegawai'])->name('pegawai.index');
    Route::get('/pegawai/{id}', [DashboardController::class, 'detailPegawai'])->name('pegawai.detail');
    Route::put('/pegawai/{id}/ubah', [DashboardController::class, 'ubahMenjadiAnggota'])->name('pegawai.ubah');

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