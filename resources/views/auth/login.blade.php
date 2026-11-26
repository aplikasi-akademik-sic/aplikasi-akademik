<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Akademik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="academic-login">
    <main class="login-panel" aria-labelledby="login-title">
        <p class="product-name">SIAKAD <span>Portal Akademik</span></p>
        <h1 id="login-title">Login</h1>
        <p class="muted">Masuk dengan akun yang diberikan oleh admin.</p>
        @if(session('success'))
            <p class="notice notice-success" role="status">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p class="notice notice-error" role="alert">{{ session('error') }}</p>
        @endif
        @if($errors->any())
            <p class="notice notice-error" role="alert">Login belum berhasil. Periksa keterangan di bawah kolom isian.</p>
        @endif
        <form action="{{ route('login.post') }}" method="POST" class="login-form" data-submit-feedback>
            @csrf
            <div class="form-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    autocomplete="username" inputmode="email" autocapitalize="none" spellcheck="false"
                    data-required-message="Email wajib diisi." data-type-message="Masukkan alamat email yang valid."
                    @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email')
                    <p id="email-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                    data-required-message="Password wajib diisi."
                    @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                @error('password')
                    <p id="password-error" class="field-error">{{ $message }}</p>
                @enderror
            </div>
            <label class="flex min-h-11 cursor-pointer items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>
            <button type="submit" class="academic-button primary-button" data-busy-label="Memeriksa akun...">Login</button>
            <p class="submit-status muted" role="status" aria-live="polite"></p>
        </form>
        @env('local')
            <details class="demo-accounts">
                <summary>Coba akun demo</summary>
                <p class="muted">Data contoh untuk pengujian lokal. Pilih role untuk mengisi form, lalu tekan Login.</p>
                <div class="demo-actions">
                    <button type="button" class="academic-button" data-demo-email="admin@akademik.com">Admin</button>
                    <button type="button" class="academic-button" data-demo-email="budi.santoso@akademik.com">Dosen</button>
                    <button type="button" class="academic-button" data-demo-email="ahmad.fauzi@student.akademik.com">Mahasiswa</button>
                </div>
            </details>
        @endenv
        <p class="login-footer">Sistem Informasi Akademik</p>
    </main>
</body>
</html>
