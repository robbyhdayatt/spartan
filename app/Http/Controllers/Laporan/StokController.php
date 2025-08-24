<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Laporan\StokRealTime;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = StokRealTime::query();

        // Logika untuk pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('kode_part', 'like', "%{$search}%")
                  ->orWhere('nama_part', 'like', "%{$search}%");
            });
        }

        $stoks = $query->paginate(15);

        return view('laporan.stok.index', compact('stoks'));
    }
}