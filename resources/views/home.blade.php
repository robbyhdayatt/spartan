@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-dollar-sign"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Penjualan Hari Ini</span>
                    <span class="info-box-number">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-invoice"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">PO Perlu Persetujuan</span>
                    <span class="info-box-number">{{ $jumlahPOPending }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Item Stok Menipis</span>
                    <span class="info-box-number">{{ $jumlahStokMenipis }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
             <div class="info-box mb-3">
                <span class="info-box-icon bg-dark elevation-1"><i class="fas fa-times-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Item Stok Habis</span>
                    <span class="info-box-number">{{ $jumlahStokHabis }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Grafik Penjualan Tahun {{ date('Y') }}</h3>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="sales-chart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Part Dengan Stok Kritis</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="products-list product-list-in-card pl-2 pr-2">
                        @forelse ($stokMenipisItems as $item)
                        <li class="item">
                            <div class="product-info">
                                <a href="#" class="product-title">{{ $item->nama_part }}</a>
                                <span class="badge {{ $item->status_stok == 'LOW_STOCK' ? 'badge-warning' : 'badge-danger' }} float-right">
                                    Stok: {{ $item->stok_tersedia }}
                                </span>
                                <span class="product-description">{{ $item->kode_part }}</span>
                            </div>
                        </li>
                        @empty
                        <li class="item text-center p-4">Semua stok dalam kondisi aman.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('laporan.stok.index') }}" class="uppercase">Lihat Semua Laporan Stok</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library untuk Grafik --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(function () {
  'use strict'

  var salesChartCanvas = $('#sales-chart').get(0).getContext('2d')

  var salesChartData = {
    labels: {!! json_encode($chartLabels) !!},
    datasets: [
      {
        label: 'Penjualan',
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: {!! json_encode($chartData) !!}
      },
    ]
  }

  var salesChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: { display: false },
    scales: {
      xAxes: [{ gridLines: { display: false } }],
      yAxes: [{
        gridLines: { display: true },
        ticks: {
          beginAtZero: true,
          // Format angka menjadi Rupiah
          callback: function(value, index, values) {
            return 'Rp ' + value.toLocaleString('id-ID');
          }
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart(salesChartCanvas, {
    type: 'bar',
    data: salesChartData,
    options: salesChartOptions
  })
})
</script>
@endpush