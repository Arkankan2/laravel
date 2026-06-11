<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    use LogsActivity;

    public function edit()
    {
        $user = Auth::user();
        return view('superadmin.profil.edit', compact('user'));
    }

    /**
     * Update profil (nama, email).
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'  => 'required|string|max:100',
            'email' => 'nullable|email|max:150',
        ]);

        $user->update([
            'nama'  => $request->nama,
            'email' => $request->email,
        ]);

        $this->logActivity('Edit Profil', 'Super Admin memperbarui profil.');

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Upload foto profil.
     */
    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->foto_profil && Storage::disk('public')->exists('profil/' . $user->foto_profil)) {
            Storage::disk('public')->delete('profil/' . $user->foto_profil);
        }

        $namaFile = 'profil_' . $user->id_user . '_' . time() . '.' . $request->file('foto_profil')->getClientOriginalExtension();
        $request->file('foto_profil')->storeAs('profil', $namaFile, 'public');

        $user->update(['foto_profil' => $namaFile]);

        $this->logActivity('Upload Foto Profil', 'Super Admin mengganti foto profil.');

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Ganti password.
     */
    public function gantiPassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        $this->logActivity('Ganti Password', 'Super Admin mengganti password.');

        return back()->with('success', 'Password berhasil diubah.');
    }
}
