<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Akademik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-indigo-600 items-center justify-center font-black text-white text-2xl shadow-xl shadow-indigo-600/30 mb-3">
                AK
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Sistem Informasi Akademik</h1>
            <p class="text-sm text-slate-500 mt-1">Silakan masuk menggunakan akun Anda</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-8">
            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl p-3">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl p-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="contoh: admin@akademik.com"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600">Kata Sandi</label>
                    </div>
                    <input type="password" id="password" name="password" required
                        placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mr-2">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-lg shadow-indigo-600/30 transition duration-150">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Login Helpers -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-500 text-center mb-3">Akun Demo (Klik untuk Isi Otomatis):</p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="setDemo('admin@akademik.com', 'password')"
                        class="px-2 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-[11px] font-medium rounded-lg text-center transition border border-indigo-200">
                        🔑 Admin
                    </button>
                    <button type="button" onclick="setDemo('budi.santoso@akademik.com', 'password')"
                        class="px-2 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[11px] font-medium rounded-lg text-center transition border border-emerald-200">
                        👨‍🏫 Dosen
                    </button>
                    <button type="button" onclick="setDemo('ahmad.fauzi@student.akademik.com', 'password')"
                        class="px-2 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 text-[11px] font-medium rounded-lg text-center transition border border-sky-200">
                        🎓 Mahasiswa
                    </button>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} Sistem Informasi Akademik. All rights reserved.
        </p>
    </div>

    <script>
        function setDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
