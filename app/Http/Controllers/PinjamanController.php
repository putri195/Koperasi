<?php

namespace App\Http\Controllers;

use App\Models\LoanSetting;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    // Fungsi pengecekan token dan role (jika diperlukan)
    private function checkSessionAndRole($requiredRole = null)
    {
        $tokens = session('keycloak_tokens', []);
        $accessToken = $tokens['access_token'] ?? null;
        $expiresAt = $tokens['expires_in'] ?? null;

        // Cek token kosong atau expired
        if (!$accessToken || ($expiresAt && now()->timestamp > $expiresAt)) {
            session()->flush();
            return redirect()->route('logout')->with('error', 'Sesi Anda telah habis. Silakan login kembali.');
        }

        // Cek role jika diperlukan
        if ($requiredRole && auth()->user()->role !== $requiredRole) {
            abort(403, 'Akses ditolak: hanya ' . $requiredRole);
        }

        return $accessToken;
    }
    
    // Menampilkan form setting pinjaman
    public function loan_setting()
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        // Ambil data pertama (karena cuma ada 1 setting global)
        $setting = LoanSetting::first();

        return view('admin.pinjaman.setting_pinjaman', compact('setting'));
    }

    // Simpan atau update setting pinjaman
    public function loan_setting_update(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;
        
        $request->validate([
            'interest_rate'   => 'required|numeric|min:0',
            'penalty_amount'  => 'required|numeric|min:0',
            'due_day'         => 'required|integer|min:1|max:31',
        ]);

        // Jika belum ada setting, buat baru
        $setting = LoanSetting::first();

        if ($setting) {
            // Update data yang sudah ada
            $setting->update([
                'interest_rate'  => $request->interest_rate,
                'penalty_amount' => $request->penalty_amount,
                'due_day'        => $request->due_day,
            ]);
        } else {
            // Buat data baru
            LoanSetting::create([
                'interest_rate'  => $request->interest_rate,
                'penalty_amount' => $request->penalty_amount,
                'due_day'        => $request->due_day,
            ]);
        }

        return redirect()->route('loan-settings.index')->with('success', 'Setting pinjaman berhasil disimpan.');
    }
}
