<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\KategoriFasilitas;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $kategoris = KategoriFasilitas::orderBy('nama_kategori')->get();
        return view('superadmin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('superadmin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_fasilitas,nama_kategori',
            'deskripsi'     => 'nullable|string',
        ]);

        $kategori = KategoriFasilitas::create($request->only('nama_kategori', 'deskripsi'));
        $this->logActivity('Tambah Kategori', "Menambahkan kategori fasilitas: {$kategori->nama_kategori}");

        return redirect()->route('superadmin.kategori.index')
            ->with('success', "Kategori {$kategori->nama_kategori} berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $kategori = KategoriFasilitas::findOrFail($id);
        return view('superadmin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriFasilitas::findOrFail($id);

        $request->validate([
            'nama_kategori' => "required|string|max:100|unique:kategori_fasilitas,nama_kategori,$id,id_kategori",
            'deskripsi'     => 'nullable|string',
        ]);

        $kategori->update($request->only('nama_kategori', 'deskripsi'));
        $this->logActivity('Edit Kategori', "Mengubah kategori fasilitas: {$kategori->nama_kategori}");

        return redirect()->route('superadmin.kategori.index')
            ->with('success', "Kategori {$kategori->nama_kategori} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $kategori = KategoriFasilitas::findOrFail($id);
        $nama = $kategori->nama_kategori;
        $kategori->delete();

        $this->logActivity('Hapus Kategori', "Menghapus kategori fasilitas: $nama");

        return redirect()->route('superadmin.kategori.index')
            ->with('success', "Kategori $nama berhasil dihapus.");
    }
}
