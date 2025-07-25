<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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

    // Halaman dashboard utama
    public function index(Request $request)
    {
        // dd(session()->all());
        $accessToken = $this->checkSessionAndRole('admin');

        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) {
            return $accessToken;
        }

        return view('admin.dashboard', [
            'user' => auth()->user(),
            'token' => $accessToken,
        ]);
    }

    // Daftar Anggota + Fitur Pencarian
    public function indexMembers(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $query = Member::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('member_number', 'like', "%$search%");
        }

        // Urutkan berdasarkan nomor anggota
        $members = $query->orderBy('member_number', 'asc')->paginate(10);

        return view('admin.anggota.tabelAnggota', [
            'members' => $members,
            'user' => auth()->user(),
            'token' => $accessToken,
        ]);
    }

    // Menambahkan anggota baru (khusus admin)
    public function storeMember(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');

        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) {
            return $accessToken;
        }

        // Jika request GET: tampilkan form tambah anggota
        if ($request->isMethod('get')) {
            $user = auth()->user();

            // Ambil user yang belum jadi anggota (tidak ada di tabel members)
            $users = User::whereNotIn('id', Member::pluck('user_id'))->get();

            return view('admin.anggota.tambahAnggota', [
                'user' => $user,
                'users' => $users,
            ]);
        }

        // Jika request POST: simpan data anggota
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:members,user_id',
            'member_number' => 'nullable|string|max:50',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required'
        ]);

        Member::create($validated);

        return redirect()->route('anggota.index')->with('success', 'Anggota baru berhasil ditambahkan.');
    }

    public function detailMember($id)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        // Ambil data anggota dan relasi user
        $member = Member::with('user')->findOrFail($id);

        // Hitung umur
        $umur = now()->year - \Carbon\Carbon::parse($member->birth_date)->year;

        return view('admin.anggota.detailAnggota', [
            'member' => $member,
            'user' => auth()->user(), // user login (admin)
            'umur' => $umur,
        ]);
    }

    // Menampilkan form edit anggota
    public function editMember($id)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $member = Member::with('user')->findOrFail($id);

        return view('admin.anggota.editAnggota', [
            'member' => $member,
            'user' => auth()->user(),
        ]);
    }

    // Memproses update data anggota
    public function updateMember(Request $request, $id)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'member_number' => 'nullable|string|max:50',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required',
        ]);

        $member->update($validated);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function deleteMember($id)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $anggota = Member::findOrFail($id);
        $anggota->delete(); // Menghapus anggota

        return redirect()->route('anggota.index')->with('message', 'Anggota berhasil dihapus.');
    }

    // Daftar Pegawai (selain anggota biasa)
    public function indexPegawai(Request $request)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $query = Member::with('user')->where('position', '!=', 'anggota');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('member_number', 'like', "%$search%")
                ->orWhere('position', 'like', "%$search%")
                ->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%$search%");
                });
            });
        }

        // Urutan posisi sesuai gambar
        $query->orderByRaw("
            FIELD(position, 
                'ketua', 
                'wakil ketua', 
                'sekretaris', 
                'bendahara', 
                'pengawas', 
                'manajer', 
                'staf'
            )
        ");
        
        $pegawai = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.pegawai.tabelPegawai', [
            'pegawai' => $pegawai,
            'user' => auth()->user(),
            'token' => $accessToken,
        ]);
    }

    // Detail Pegawai
    public function detailPegawai($id)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $pegawai = Member::with('user')->findOrFail($id);
        $umur = now()->year - \Carbon\Carbon::parse($pegawai->birth_date)->year;

        return view('admin.pegawai.detailPegawai', [
            'pegawai' => $pegawai,
            'user' => auth()->user(),
            'umur' => $umur,
        ]);
    }

    // Mengubah jabatan pegawai menjadi anggota
    public function ubahMenjadiAnggota($id)
    {
        $accessToken = $this->checkSessionAndRole('admin');
        if ($accessToken instanceof \Illuminate\Http\RedirectResponse) return $accessToken;

        $pegawai = Member::findOrFail($id);
        $pegawai->update(['position' => 'anggota']);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil diubah menjadi anggota.');
    }

    public function editProfile()
    {
        $user = auth()->user();
        $member = $user->member;

        $fullName = Str::of($user->name)->explode(' ');
        $firstName = $fullName->first(); // kata pertama
        $lastName = $fullName->slice(1)->implode(' '); // sisa kata setelahnya

        return view('admin.editProfile', compact('user', 'member', 'firstName', 'lastName'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // Validasi
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('profile_photo')) {
            $filename = 'profile_' . time() . '.' . $request->profile_photo->extension();
            $path = $request->file('profile_photo')->storeAs('profile_photos', $filename, 'public');
            $user->profile_photo = 'storage/' . $path; // hasil: storage/profile_photos/nama.jpg
        }

        // Update nama (gabungan first + last name)
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Update di tabel member
        $member = $user->member;
        if ($member) {
            $member->gender = $request->gender;
            $member->birth_date = $request->birth_date;
            $member->phone = $request->phone;
            $member->address = $request->address;
            $member->save();
        }

        // --------- 🔐 UPDATE KE KEYCLOAK ---------
        $admintoken = session('keycloak_tokens.admin_token');
        $keycloakId = $user->keycloak_id;

        if ($admintoken && $keycloakId) {
            $keycloakPayload = [
                'username' => $request->username, // wajib
                'enabled' => true                 // wajib
            ];

            if ($request->filled('first_name')) {
                $keycloakPayload['firstName'] = $request->first_name;
            }
            if ($request->filled('last_name')) {
                $keycloakPayload['lastName'] = $request->last_name;
            }
            if ($request->filled('email')) {
                $keycloakPayload['email'] = $request->email;
                $keycloakPayload['emailVerified'] = true;
            }
            if ($request->filled('password')) {
                $keycloakPayload['credentials'] = [[
                    'type' => 'password',
                    'value' => $request->password,
                    'temporary' => false
                ]];
            }

            // dd($keycloakPayload);

            $response = Http::withToken($admintoken)
                ->contentType('application/json')
                ->put(env('KEYCLOAK_BASE_URL') . '/admin/realms/' . env('KEYCLOAK_REALM') . '/users/' . $keycloakId, $keycloakPayload);

            if (!$response->successful()) {
                Log::error('Gagal update user Keycloak: ', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return back()->with('error', 'Gagal memperbarui profil di Keycloak. Coba lagi.');
            }
        }

        return redirect()->route('profil.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
