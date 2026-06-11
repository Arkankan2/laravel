<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Lantai;
use App\Models\Ruangan;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $ruangans = Ruangan::with(['lantai.gedung'])->orderBy('nama_ruangan')->get();
        $lantais  = Lantai::with('gedung')->orderBy('id_gedung')->orderBy('nomor_lantai')->get();
        return view('superadmin.ruangan.index', compact('ruangans', 'lantais'));
    }

    public function create()
    {
        $lantais = Lantai::with('gedung')->orderBy('id_gedung')->orderBy('nomor_lantai')->get();
        return view('superadmin.ruangan.create', compact('lantais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_lantai'    => 'required|exists:lantais,id',
            'nama_ruangan' => 'required|string|max:100',
            'kode_ruangan' => 'nullable|string|max:30',
            'deskripsi'    => 'nullable|string',
        ]);

        $ruangan = Ruangan::create($request->only('id_lantai', 'nama_ruangan', 'kode_ruangan', 'deskripsi'));
        $this->logActivity('Tambah Ruangan', "Menambahkan ruangan: {$ruangan->nama_ruangan}");

        return redirect()->route('superadmin.ruangan.index')
            ->with('success', "Ruangan {$ruangan->nama_ruangan} berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $lantais = Lantai::with('gedung')->orderBy('id_gedung')->orderBy('nomor_lantai')->get();
        return view('superadmin.ruangan.edit', compact('ruangan', 'lantais'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);

        $request->validate([
            'id_lantai'    => 'required|exists:lantais,id',
            'nama_ruangan' => 'required|string|max:100',
            'kode_ruangan' => 'nullable|string|max:30',
            'deskripsi'    => 'nullable|string',
        ]);

        $ruangan->update($request->only('id_lantai', 'nama_ruangan', 'kode_ruangan', 'deskripsi'));
        $this->logActivity('Edit Ruangan', "Mengubah ruangan: {$ruangan->nama_ruangan}");

        return redirect()->route('superadmin.ruangan.index')
            ->with('success', "Ruangan {$ruangan->nama_ruangan} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        $nama    = $ruangan->nama_ruangan;
        $ruangan->delete();

        $this->logActivity('Hapus Ruangan', "Menghapus ruangan: $nama");

        return redirect()->route('superadmin.ruangan.index')
            ->with('success', "Ruangan $nama berhasil dihapus.");
    }
}
