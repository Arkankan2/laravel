<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman status laporan.
     */
    public function status()
    {
        $user = Auth::user();
        return view('laporan.status', compact('user'));
    }
}
