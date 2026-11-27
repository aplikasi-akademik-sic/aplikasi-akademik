@php
    $roleLabel = match (auth()->user()->role) {
        'admin' => 'Admin',
        'lecturer' => 'Dosen',
        'student' => 'Mahasiswa',
        default => 'Pengguna',
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Akademik') - SIAKAD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="academic-app">
    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>
    <header class="portal-header">
        <div class="app-header">
            <button type="button" class="menu-toggle academic-button" aria-expanded="false" aria-controls="module-drawer" hidden>Menu</button>
            <a href="{{ route('dashboard') }}" class="portal-brand" aria-label="SIAKAD, Dashboard">
                @include('layouts._mark')
                <span class="product-name">SIAKAD <span>Portal Akademik</span></span>
            </a>
            <div class="header-account">
                <details class="account-menu">
                    <summary class="account-name">
                        <span class="account-initial" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="account-identity">{{ auth()->user()->name }} <small>{{ $roleLabel }}</small></span>
                    </summary>
                    <div class="account-links">
                        <p class="account-mobile-caption">{{ auth()->user()->name }} · {{ $roleLabel }}</p>
                        <a href="{{ route('profile.edit') }}">Profil saya</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admins.index') }}">Kelola admin</a>
                        @endif
                    </div>
                </details>
                <form action="{{ route('logout') }}" method="POST" data-submit-feedback>
                    @csrf
                    <button type="submit" class="academic-button" data-busy-label="Keluar...">Logout</button>
                </form>
            </div>
        </div>
    </header>
    <div class="app-body">
        <aside class="app-sidebar" aria-label="Navigasi aplikasi">
            @include('layouts._navigation')
        </aside>
        <dialog id="module-drawer" aria-label="Navigasi aplikasi">
            <form method="dialog"><button class="academic-button" autofocus>Tutup menu</button></form>
        </dialog>
        <main id="main-content" class="app-content" tabindex="-1">
            <div class="page-heading">
                @unless(request()->routeIs('dashboard'))
                    <a class="dashboard-back" href="{{ route('dashboard') }}"><span aria-hidden="true">←</span> Dashboard</a>
                @endunless
                <h1>@yield('page_title', 'Dashboard')</h1>
                @hasSection('page_subtitle')
                    <p class="muted">@yield('page_subtitle')</p>
                @endif
            </div>
            @if(session('success'))
                <p class="notice notice-success" role="status">{{ session('success') }}</p>
            @endif
            @if(session('error'))
                <p class="notice notice-error" role="alert">{{ session('error') }}</p>
            @endif
            @if($errors->any())
                <div class="notice notice-error" role="alert">
                    <p>Data belum tersimpan. Periksa isian berikut:</p>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
