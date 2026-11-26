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
    <header class="app-header">
        <a href="{{ route('dashboard') }}" class="product-name" aria-label="SIAKAD, Dashboard">SIAKAD <span>Portal Akademik</span></a>
        <div class="header-account">
            <details class="account-menu">
                <summary class="account-name">{{ auth()->user()->name }} <span class="muted">({{ $roleLabel }})</span></summary>
                <div class="account-links">
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
    </header>
    <div class="app-body">
        <aside class="app-sidebar" aria-label="Navigasi aplikasi">
            <button type="button" class="menu-toggle academic-button" aria-expanded="true" aria-controls="role-navigation" hidden>Menu</button>
            @include('layouts._navigation')
        </aside>
        <main id="main-content" class="app-content" tabindex="-1">
            <div class="page-heading">
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
