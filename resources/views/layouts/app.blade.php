<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Asal Muasal Laundry') }}</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @auth
    <header>
        <a href="/" class="brand">Asal Muasal Laundry</a>
        <nav class="nav-links">
            <a href="/">Beranda</a>
            @if(Auth::user()->id_level == 1)
                <!-- Super Admin -->
                <a href="/master/users">Pengguna</a>
                <a href="/master/services">Layanan</a>
            @endif
            @if(in_array(Auth::user()->id_level, [1, 2]))
                <!-- Admin & Operator -->
                <a href="/master/customers">Pelanggan</a>
                <a href="/master/vouchers">Voucher</a>
                <a href="/transactions">Transaksi</a>
                <a href="/pickups">Pengambilan</a>
            @endif
            @if(in_array(Auth::user()->id_level, [1, 3]))
                <!-- Admin & Pimpinan -->
                <a href="/reports">Laporan Penjualan</a>
            @endif

            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="color: #ef4444; margin-left: 1.5rem;">Keluar</button>
            </form>
        </nav>
    </header>
    @endauth

    <main class="{{ request()->is('login') ? '' : 'container mt-4' }}">
        @yield('content')
    </main>

</body>
</html>
