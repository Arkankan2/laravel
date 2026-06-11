<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class GedungController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $gedungs = Gedung::withCount('lantais')->orderBy('nama_gedung')->get();
        return view('superadmin.gedung.index', compact('gedungs'));
    }

    public function create()
    {
        return view('superadmin.gedung.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gedung' => 'required|string|max:100|unique:gedungs,nama_gedung',
            'kode_gedung' => 'nullable|string|max:20',
            'deskripsi'   => 'nullable|string',
        ]);

        $gedung = Gedung::create($request->only('nama_gedung', 'kode_gedung', 'deskripsi'));
        $this->logActivity('Tambah Gedung', "Menambahkan gedung: {$gedung->nama_gedung}");

        return redirect()->route('superadmin.gedung.index')
            ->with('success', "Gedung {$gedung->nama_gedung} berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $gedung = Gedung::findOrFail($id);
        return view('superadmin.gedung.edit', compact('gedung'));
    }

    public function update(Request $request, $id)
    {
        $gedung = Gedung::findOrFail($id);

        $request->validate([
            'nama_gedung' => "required|string|max:100|unique:gedungs,nama_gedung,$id",
            'kode_gedung' => 'nullable|string|max:20',
            'deskripsi'   => 'nullable|string',
        ]);

        $gedung->update($request->only('nama_gedung', 'kode_gedung', 'deskripsi'));
        $this->logActivity('Edit Gedung', "Mengubah gedung: {$gedung->nama_gedung}");

        return redirect()->route('superadmin.gedung.index')
            ->with('success', "Gedung {$gedung->nama_gedung} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $gedung = Gedung::findOrFail($id);
        $nama = $gedung->nama_gedung;
        $gedung->delete();

        $this->logActivity('Hapus Gedung', "Menghapus gedung: $nama");

        return redirect()->route('superadmin.gedung.index')
            ->with('success', "Gedung $nama berhasil dihapus.");
    }
}
