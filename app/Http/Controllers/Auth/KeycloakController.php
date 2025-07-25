<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;

class KeycloakController extends Controller
{
    /**
     * Arahkan pengguna ke halaman login Keycloak.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToKeycloak()
    {
        // Redirect ke Keycloak menggunakan driver custom yang sudah diperluas
        // dd(config('services.keycloak'));
        return Socialite::driver('keycloak')->redirect();
        // dd(Socialite::driver('keycloak')->redirect()->getTargetUrl());

        // // Tambahkan ini untuk debugging
        // $baseUrl = Config::get('services.keycloak.base_url');
        // $realms = Config::get('services.keycloak.realms');
        // $clientId = Config::get('services.keycloak.client_id');
        // $redirectUri = Config::get('services.keycloak.redirect');

        // dd([
        //     'KEYCLOAK_BASE_URL_FROM_CONFIG' => $baseUrl,
        //     'KEYCLOAK_REALM_FROM_CONFIG' => $realms,
        //     'KEYCLOAK_CLIENT_ID_FROM_CONFIG' => $clientId,
        //     'KEYCLOAK_REDIRECT_URI_FROM_CONFIG' => $redirectUri,
        //     // Anda juga bisa mencoba melihat URL yang akan dihasilkan Socialite
        //     'SOCIALITE_REDIRECT_URL' => Socialite::driver('keycloak')->redirect()->getTargetUrl()
        // ]);
    }

    private function getAdminAccessToken()
    {
        try {
            $response = Http::asForm()->post(env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/token', [
                'grant_type' => 'client_credentials',
                'client_id' => env('KEYCLOAK_CLIENT_ID'),
                'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
            ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            Log::error('Gagal mendapatkan admin token dari Keycloak', ['response' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('Exception saat ambil admin token: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Tangani callback dari Keycloak setelah login berhasil.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleKeycloakCallback()
    {
        try {
            // Ambil user beserta token dari Keycloak
            $keycloakUser = Socialite::driver('keycloak')->stateless()->user();
            $idToken = $keycloakUser->accessTokenResponseBody['id_token'] ?? null;

            Log::info('Keycloak user data', (array) $keycloakUser);
            // dd($keycloakUser);
            Log::info('Token data', [
                'access_token' => $keycloakUser->token,
                'refresh_token' => $keycloakUser->refreshToken,
                'expires_in' => $keycloakUser->expiresIn,
                'id_token' => $idToken
            ]);

            // Cari user lokal berdasarkan keycloak_id
            $user = User::where('keycloak_id', $keycloakUser->getId())->first();

            // Ambil role dari token Keycloak
            $roles = $keycloakUser->user['realm_access']['roles'] ?? [];
            $roleName = in_array('admin', $roles) ? 'admin' : 'anggota';

            if (!$user) {
                // Buat user baru
                $user = User::create([
                    'keycloak_id'       => $keycloakUser->getId(),
                    'name'              => $keycloakUser->getName() ?? $keycloakUser->getNickname() ?? 'Unknown',
                    'email'             => $keycloakUser->getEmail() ?? 'unknown@example.com',
                    'password'          => bcrypt(str()->random(16)),
                    'role'              => $roleName,
                    'email_verified_at' => now(),
                ]);
            } else {
                // Update info dan role jika berubah
                $user->update([
                    'name'  => $keycloakUser->getName() ?? $keycloakUser->getNickname() ?? $user->name,
                    'email' => $keycloakUser->getEmail() ?? $user->email,
                    'role'  => $roleName, // ✅ Update role juga dari Keycloak
                ]);
            }

            // Login Laravel
            Auth::login($user);

            // Di dalam handleKeycloakCallback, setelah Auth::login($user);
            $adminToken = $this->getAdminAccessToken();

            // Simpan semua token ke dalam satu array session
            session([
                'keycloak_tokens' => [
                    'access_token' => $keycloakUser->token,
                    'refresh_token' => $keycloakUser->refreshToken,
                    'expires_in' => now()->timestamp + $keycloakUser->expiresIn,
                    'id_token' => $idToken,
                    'admin_token' => $adminToken,
                ]
            ]);

            
            // Redirect sesuai role
            return redirect()->intended(route('redirect.role'));


        } catch (\Exception $e) {
            Log::error('Keycloak authentication failed: ' . $e->getMessage(), ['exception' => $e]);
            return redirect('/')->with('error', 'Gagal login melalui Keycloak. Silakan coba lagi.');
        }
    }


    public function register(Request $request)
    {
        try {
            // Validasi form
            $request->validate([
                'first_name' => 'required',
                'last_name'  => 'required',
                'username' => 'required',
                'email'    => 'required',
                'password' => 'required',         
                'role'     => 'required',
            ]);

            $fullName = $request->first_name . ' ' . $request->last_name;

            // Langkah 1: Ambil token admin dari Keycloak
            $tokenResponse = Http::asForm()->post(config('services.keycloak.base_url') . '/realms/master/protocol/openid-connect/token', [
                'grant_type' => 'password',
                'client_id'  => 'admin-cli',
                'username'   => env('KEYCLOAK_ADMIN'),
                'password'   => env('KEYCLOAK_ADMIN_PASSWORD'),
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Gagal mendapatkan token admin Keycloak', ['response' => $tokenResponse->body()]);
                return back()->with('error', 'Gagal mendapatkan token admin Keycloak');
            }

            $adminToken = $tokenResponse->json()['access_token'];

            // Langkah 2: Buat user di Keycloak
            $createUserResponse = Http::withToken($adminToken)->post(config('services.keycloak.base_url') . '/admin/realms/' . config('services.keycloak.realms') . '/users', [
                'enabled'     => true,
                'username'    => $request->username,
                'email'       => $request->email,
                'firstName'     => $request->first_name,
                'lastName'      => $request->last_name,
                'emailVerified' => true,
                'credentials' => [[
                    'type'      => 'password',
                    'value'     => $request->password,
                    'temporary' => false,
                ]],
            ]);

            // if (!$createUserResponse->successful()) {
            //     dd('Keycloak error', $createUserResponse->status(), $createUserResponse->body());
            // }

            // $location = $createUserResponse->header('Location');
            // $keycloakId = basename($location);

            // dd('User Keycloak berhasil dibuat', $keycloakId);


            if ($createUserResponse->status() !== 201) {
                Log::error('Gagal membuat user di Keycloak', ['response' => $createUserResponse->body()]);
                return back()->with('error', 'Gagal membuat user di Keycloak.');
            }

            // Ambil UUID user baru dari header Location
            $location    = $createUserResponse->header('Location');
            $keycloakId  = basename($location); // hasilnya adalah UUID

            // Langkah 3: Ambil ID Role dari Keycloak
            $rolesResponse = Http::withToken($adminToken)->get(config('services.keycloak.base_url') . '/admin/realms/' . config('services.keycloak.realms') . '/roles');
            if (!$rolesResponse->successful()) {
                Log::error('Gagal mengambil role dari Keycloak', ['response' => $rolesResponse->body()]);
                return back()->with('error', 'Gagal mengambil role dari Keycloak.');
            }

            $roleName = $request->role;
            $roleData = collect($rolesResponse->json())->firstWhere('name', $roleName);

            if ($roleData) {
                // Assign role ke user
                Http::withToken($adminToken)->post(config('services.keycloak.base_url') . '/admin/realms/' . config('services.keycloak.realms') . '/users/' . $keycloakId . '/role-mappings/realm', [
                    [
                        'id'   => $roleData['id'],
                        'name' => $roleData['name'],
                    ]
                ]);
            } else {
                Log::warning("Role '{$roleName}' tidak ditemukan di Keycloak.");
            }

            // dd([
            //     'keycloak_id' => $keycloakId,
            //     'name' => $request->name,
            //     'username' => $request->username,
            //     'email' => $request->email,
            //     'role' => $roleName,
            //     'password'          => bcrypt($request->password),
            //     'email_verified_at' => Carbon::now(),
            // ]);
            try {
                $user = User::create([
                    'keycloak_id' => $keycloakId,
                    'name'        => $fullName,
                    'username'    => $request->username,
                    'email'       => $request->email,
                    'role'        => $roleName,
                    'email_verified_at' => Carbon::now(),
                    'password'          => bcrypt($request->password),
                ]);
                
                // dd('User berhasil dibuat!', $user);
                
            } catch (\Exception $e) {
                // dd('Gagal insert ke database', $e->getMessage());
                Log::error('Registrasi Gagal: ' . $e->getMessage(), ['exception' => $e]);
                return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
            // // Langkah 4: Simpan ke database lokal
            // User::create([
            //     'keycloak_id'       => $keycloakId,
            //     'name'              => $request->name,
            //     'username'          => $request->username,
            //     'email'             => $request->email,
            //     'role'              => $roleName,
            //     'email_verified_at' => Carbon::now(), // supaya dianggap sudah terverifikasi
            //     'password'          => Hash::make($request->password),

            // ]);

            return redirect()->route('login.keycloak')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            Log::error('Registrasi Gagal: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Logout dari Laravel dan Keycloak.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $idToken = session('keycloak_tokens.id_token'); // simpan dulu SEBELUM session dihapus

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Buat redirect URI dari route
        $redirectUri = route('logout.callback'); // contoh: http://localhost:8000/logout/callback

        $logoutUrl = config('services.keycloak.base_url') .
            '/realms/' . config('services.keycloak.realms') .
            '/protocol/openid-connect/logout?' .
            http_build_query([
                'id_token_hint' => $idToken,
                'post_logout_redirect_uri' => $redirectUri,
            ]);

        return redirect($logoutUrl);
    }

}
