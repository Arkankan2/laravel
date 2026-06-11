<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use App\Models\Lantai;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class LantaiController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $lantais = Lantai::with('gedung')->orderBy('id_gedung')->orderBy('nomor_lantai')->get();
        $gedungs = Gedung::orderBy('nama_gedung')->get();
        return view('superadmin.lantai.index', compact('lantais', 'gedungs'));
    }

    public function create()
    {
        $gedungs = Gedung::orderBy('nama_gedung')->get();
        return view('superadmin.lantai.create', compact('gedungs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gedung'    => 'required|exists:gedungs,id',
            'nama_lantai'  => 'required|string|max:100',
            'nomor_lantai' => 'required|integer|min:1',
        ]);

        $lantai = Lantai::create($request->only('id_gedung', 'nama_lantai', 'nomor_lantai'));
        $this->logActivity('Tambah Lantai', "Menambahkan lantai: {$lantai->nama_lantai}");

        return redirect()->route('superadmin.lantai.index')
            ->with('success', "Lantai {$lantai->nama_lantai} berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $lantai  = Lantai::findOrFail($id);
        $gedungs = Gedung::orderBy('nama_gedung')->get();
        return view('superadmin.lantai.edit', compact('lantai', 'gedungs'));
    }

    public function update(Request $request, $id)
    {
        $lantai = Lantai::findOrFail($id);

        $request->validate([
            'id_gedung'    => 'required|exists:gedungs,id',
            'nama_lantai'  => 'required|string|max:100',
            'nomor_lantai' => 'required|integer|min:1',
        ]);

        $lantai->update($request->only('id_gedung', 'nama_lantai', 'nomor_lantai'));
        $this->logActivity('Edit Lantai', "Mengubah lantai: {$lantai->nama_lantai}");

        return redirect()->route('superadmin.lantai.index')
            ->with('success', "Lantai {$lantai->nama_lantai} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $lantai = Lantai::findOrFail($id);
        $nama   = $lantai->nama_lantai;
        $lantai->delete();

        $this->logActivity('Hapus Lantai', "Menghapus lantai: $nama");

        return redirect()->route('superadmin.lantai.index')
            ->with('success', "Lantai $nama berhasil dihapus.");
    }
}
