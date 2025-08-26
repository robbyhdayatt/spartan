<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SPARTAN') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
        </ul>
        <ul class="navbar-nav ml-auto">
            @guest
                @if (Route::has('login'))<li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>@endif
                @if (Route::has('register'))<li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>@endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                </li>
            @endguest
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('home') }}" class="brand-link">
            <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">SPARTAN</span>
        </a>

        <div class="sidebar">
            @auth
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image"><img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image"></div>
                <div class="info"><a href="#" class="d-block">{{ Auth::user()->name }}</a></div>
            </div>
            @endauth

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                @can('access', ['dashboard', 'read'])
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('home*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
                    </a>
                </li>
                @endcan

                @can('access', ['approvals', 'read'])
                <li class="nav-item">
                    <a href="{{ route('approvals.index') }}" class="nav-link {{ request()->is('approvals*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i><p>Persetujuan Saya</p>
                    </a>
                </li>
                @endcan

                @auth
                    @if (Auth::user()->can('access', ['suppliers', 'read']) || Auth::user()->can('access', ['brands', 'read']) || Auth::user()->can('access', ['parts', 'read']))
                    <li class="nav-header">MASTER DATA</li>
                    <li class="nav-item {{ request()->is('suppliers*', 'brands*', 'categories*', 'jabatan*', 'gudang*', 'karyawan*', 'konsumen*', 'parts*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('suppliers*', 'brands*', 'categories*', 'jabatan*', 'gudang*', 'karyawan*', 'konsumen*', 'parts*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i><p>Master<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('access', ['suppliers', 'read'])<li class="nav-item"><a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Supplier</p></a></li>@endcan
                            @can('access', ['brands', 'read'])<li class="nav-item"><a href="{{ route('brands.index') }}" class="nav-link {{ request()->is('brands*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Brand</p></a></li>@endcan
                            @can('access', ['categories', 'read'])<li class="nav-item"><a href="{{ route('categories.index') }}" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Kategori</p></a></li>@endcan
                            @can('access', ['jabatan', 'read'])<li class="nav-item"><a href="{{ route('jabatan.index') }}" class="nav-link {{ request()->is('jabatan*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Jabatan</p></a></li>@endcan
                            @can('access', ['gudang', 'read'])<li class="nav-item"><a href="{{ route('gudang.index') }}" class="nav-link {{ request()->is('gudang*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Gudang</p></a></li>@endcan
                            @can('access', ['karyawan', 'read'])<li class="nav-item"><a href="{{ route('karyawan.index') }}" class="nav-link {{ request()->is('karyawan*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Karyawan</p></a></li>@endcan
                            @can('access', ['konsumen', 'read'])<li class="nav-item"><a href="{{ route('konsumen.index') }}" class="nav-link {{ request()->is('konsumen*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Konsumen</p></a></li>@endcan
                            @can('access', ['parts', 'read'])<li class="nav-item"><a href="{{ route('parts.index') }}" class="nav-link {{ request()->is('parts*') ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i><p>Part</p></a></li>@endcan
                        </ul>
                    </li>
                    @endif
                @endauth

                @if (Auth::user()->can('access', ['pembelian', 'read']) || Auth::user()->can('access', ['penerimaan', 'read']) || Auth::user()->can('access', ['penjualan', 'read']))
                <li class="nav-header">TRANSAKSI</li>
                @can('access', ['pembelian', 'read'])<li class="nav-item"><a href="{{ route('pembelian.index') }}" class="nav-link {{ request()->is('transaksi/pembelian*') ? 'active' : '' }}"><i class="nav-icon fas fa-shopping-cart"></i><p>Pembelian (PO)</p></a></li>@endcan
                @can('access', ['penerimaan', 'read'])<li class="nav-item"><a href="{{ route('penerimaan.index') }}" class="nav-link {{ request()->is('transaksi/penerimaan*') ? 'active' : '' }}"><i class="nav-icon fas fa-box-open"></i><p>Penerimaan Barang</p></a></li>@endcan
                @can('access', ['penjualan', 'read'])<li class="nav-item"><a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->is('transaksi/penjualan*') ? 'active' : '' }}"><i class="nav-icon fas fa-file-invoice-dollar"></i><p>Penjualan</p></a></li>@endcan
                @endif

                @if (Auth::user()->can('access', ['adjustment', 'read']) || Auth::user()->can('access', ['retur', 'read']))
                <li class="nav-header">INVENTARIS</li>
                @can('access', ['adjustment', 'read'])<li class="nav-item"><a href="{{ route('adjustment.index') }}" class="nav-link {{ request()->is('transaksi/adjustment*') ? 'active' : '' }}"><i class="nav-icon fas fa-clipboard-check"></i><p>Stock Adjustment</p></a></li>@endcan
                @can('access', ['retur', 'read'])<li class="nav-item"><a href="{{ route('retur.index') }}" class="nav-link {{ request()->is('transaksi/retur*') ? 'active' : '' }}"><i class="nav-icon fas fa-undo-alt"></i><p>Retur</p></a></li>@endcan
                @endif

                @auth
                    @if (Auth::user()->can('access', ['laporan.stok', 'read']) || Auth::user()->can('access', ['laporan.insentif', 'read']))
                    <li class="nav-header">LAPORAN</li>
                    
                    @can('access', ['laporan.stok', 'read'])
                    <li class="nav-item">
                        <a href="{{ route('laporan.stok.index') }}" class="nav-link {{ request()->is('laporan/stok*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i><p>Laporan Stok</p>
                        </a>
                    </li>
                    @endcan
                    
                    @can('access', ['laporan.insentif', 'read'])
                    <li class="nav-item">
                        <a href="{{ route('laporan.insentif.index') }}" class="nav-link {{ request()->is('laporan/insentif*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-award"></i><p>Laporan Insentif</p>
                        </a>
                    </li>
                    @endcan
                    
                    @endif
                @endauth
                
                @auth
                    {{-- PERBAIKAN DI SINI: Tambahkan pengecekan untuk semua menu pengaturan --}}
                    @if (Auth::user()->can('access', ['settings.approval-levels', 'read']) || Auth::user()->can('access', ['settings.users', 'read']) || Auth::user()->can('access', ['settings.permissions', 'read']) || Auth::user()->can('access', ['settings.harga-jual', 'read']) || Auth::user()->can('access', ['settings.insentif', 'read']) || Auth::user()->can('access', ['settings.campaign', 'read']))
                    
                    <li class="nav-header">PENGATURAN</li>
                    
                    @can('access', ['settings.approval-levels', 'read'])
                    <li class="nav-item"><a href="{{ route('approval-levels.index') }}" class="nav-link {{ request()->is('settings/approval-levels*') ? 'active' : '' }}"><i class="nav-icon fas fa-cog"></i><p>Aturan Approval</p></a></li>
                    @endcan

                    @can('access', ['settings.users', 'read'])
                    <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link {{ request()->is('settings/users*') ? 'active' : '' }}"><i class="nav-icon fas fa-users-cog"></i><p>Manajemen User</p></a></li>
                    @endcan

                    @can('access', ['settings.permissions', 'read'])
                    <li class="nav-item"><a href="{{ route('permissions.index') }}" class="nav-link {{ request()->is('settings/permissions*') ? 'active' : '' }}"><i class="nav-icon fas fa-user-shield"></i><p>Hak Akses User</p></a></li>
                    @endcan

                    @can('access', ['settings.harga-jual', 'read'])
                    <li class="nav-item"><a href="{{ route('harga-jual.index') }}" class="nav-link {{ request()->is('settings/harga-jual*') ? 'active' : '' }}"><i class="nav-icon fas fa-tags"></i><p>Manajemen Harga</p></a></li>
                    @endcan

                    @can('access', ['settings.insentif', 'read'])
                    <li class="nav-item"><a href="{{ route('insentif.index') }}" class="nav-link {{ request()->is('settings/insentif*') ? 'active' : '' }}"><i class="nav-icon fas fa-gift"></i><p>Manajemen Insentif</p></a></li>
                    @endcan

                    @can('access', ['settings.campaign', 'read'])
                    <li class="nav-item"><a href="{{ route('campaign.index') }}" class="nav-link {{ request()->is('settings/campaign*') ? 'active' : '' }}"><i class="nav-icon fas fa-bullhorn"></i><p>Manajemen Kampanye</p></a></li>
                    @endcan
                    @endif
                @endauth
            </ul>
        </nav>
            </div>
        </aside>

    <div class="content-wrapper">
        <div class="content">
            <div class="container-fluid pt-3">
                @yield('content')
            </div>
        </div>
    </div>
    <footer class="main-footer">
        <strong>Copyright &copy; 2024-{{ date('Y') }} <a href="#">SPARTAN</a>.</strong> All rights reserved.
    </footer>
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
@stack('scripts')
</body>
</html>