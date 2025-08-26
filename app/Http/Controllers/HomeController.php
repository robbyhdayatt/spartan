<?php

namespace App\Http\Controllers;

use App\Models\Laporan\StokRealTime;
use App\Models\Transaksi\Pembelian;
use App\Models\Transaksi\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('access', ['dashboard', 'read']);
        // 1. Data untuk Info Boxes
        $totalPenjualanHariIni = Penjualan::whereDate('tanggal_penjualan', today())->sum('total_amount');
        $jumlahPOPending = Pembelian::where('status_pembelian', 'pending_approval')->count();
        $jumlahStokMenipis = StokRealTime::where('status_stok', 'LOW_STOCK')->count();
        $jumlahStokHabis = StokRealTime::where('status_stok', 'OUT_OF_STOCK')->count();

        // 2. Data untuk Tabel Stok Menipis
        $stokMenipisItems = StokRealTime::whereIn('status_stok', ['LOW_STOCK', 'OUT_OF_STOCK'])
                                ->orderBy('stok_tersedia', 'asc')
                                ->limit(5)
                                ->get();
        
        // 3. Data untuk Grafik Penjualan Bulanan
        $salesData = Penjualan::select(
                DB::raw('MONTH(tanggal_penjualan) as bulan'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereYear('tanggal_penjualan', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();
        
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = date('F', mktime(0, 0, 0, $i, 1));
            $total = $salesData->firstWhere('bulan', $i)->total ?? 0;
            array_push($chartLabels, $bulan);
            array_push($chartData, $total);
        }

        return view('home', compact(
            'totalPenjualanHariIni',
            'jumlahPOPending',
            'jumlahStokMenipis',
            'jumlahStokHabis',
            'stokMenipisItems',
            'chartLabels',
            'chartData'
        ));
    }
}