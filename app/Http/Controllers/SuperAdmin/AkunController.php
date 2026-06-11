<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunController extends Controller
{
    use LogsActivity;

    /**
     * Daftar semua pengguna.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%$search%")
                  ->orWhere('nama', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('superadmin.akun.index', compact('users'));
    }

    /**
     * Form tambah pengguna.
     */
    public function create()
    {
        return view('superadmin.akun.create');
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:user,username',
            'nama'     => 'required|string|max:100',
            'email'    => 'nullable|email|max:150',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:super_admin,admin,mahasiswa,teknisi,dosen',
            'status'   => 'required|in:aktif,nonaktif',
        ], [], [
            'username' => 'Username',
            'nama'     => 'Nama',
            'email'    => 'Email',
            'password' => 'Password',
            'role'     => 'Role',
            'status'   => 'Status',
        ]);

        $user = User::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        $this->logActivity('Tambah Pengguna', "Menambahkan pengguna: {$user->username} (role: {$user->role})");

        return redirect()->route('superadmin.akun.index')
            ->with('success', "Pengguna {$user->username} berhasil ditambahkan.");
    }

    /**
     * Form edit pengguna.
     */
    public function edit($id)
    {
        $pengguna = User::findOrFail($id);
        return view('superadmin.akun.edit', compact('pengguna'));
    }

    /**
     * Update data pengguna.
     */
    public function update(Request $request, $id)
    {
        $pengguna = User::findOrFail($id);

        $request->validate([
            'username' => ['required','string','max:100', Rule::unique('user','username')->ignore($id, 'id_user')],
            'nama'     => 'required|string|max:100',
            'email'    => 'nullable|email|max:150',
            'role'     => 'required|in:super_admin,admin,mahasiswa,teknisi,dosen',
            'status'   => 'required|in:aktif,nonaktif',
        ]);

        $pengguna->update([
            'username' => $request->username,
            'nama'     => $request->nama,
            'email'    => $request->email,
            'role'     => $request->role,
            'status'   => $request->status,
        ]);

        $this->logActivity('Edit Pengguna', "Mengubah data pengguna: {$pengguna->username}");

        return redirect()->route('superadmin.akun.index')
            ->with('success', "Data pengguna {$pengguna->username} berhasil diperbarui.");
    }

    /**
     * Hapus pengguna.
     */
    public function destroy($id)
    {
        $pengguna = User::findOrFail($id);

        // Tidak boleh hapus diri sendiri
        if ($pengguna->id_user === Auth::user()->id_user) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $username = $pengguna->username;
        $pengguna->delete();

        $this->logActivity('Hapus Pengguna', "Menghapus pengguna: $username");

        return redirect()->route('superadmin.akun.index')
            ->with('success', "Pengguna $username berhasil dihapus.");
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function toggleStatus($id)
    {
        $pengguna = User::findOrFail($id);

        if ($pengguna->id_user === Auth::user()->id_user) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $statusBaru = $pengguna->status === 'aktif' ? 'nonaktif' : 'aktif';
        $pengguna->update(['status' => $statusBaru]);

        $aksi = $statusBaru === 'aktif' ? 'Aktifkan Akun' : 'Nonaktifkan Akun';
        $this->logActivity($aksi, "Mengubah status pengguna {$pengguna->username} menjadi $statusBaru");

        $label = $statusBaru === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$pengguna->username} berhasil $label.");
    }

    /**
     * Reset password pengguna.
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        $pengguna = User::findOrFail($id);
        $pengguna->update(['password' => Hash::make($request->password_baru)]);

        $this->logActivity('Reset Password', "Mereset password pengguna: {$pengguna->username}");

        return back()->with('success', "Password pengguna {$pengguna->username} berhasil direset.");
    }
}
