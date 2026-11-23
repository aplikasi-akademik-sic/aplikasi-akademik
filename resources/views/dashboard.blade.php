@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Ringkasan informasi dan aktivitas akademik')

@section('content')
<div class="space-y-6">

    <!-- Welcome Hero Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-600 p-6 md:p-8 text-white shadow-xl">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-white/20 text-white backdrop-blur-sm mb-3">
                @if(Auth::user()->isAdmin())
                    Role: Administrator Sistem
                @elseif(Auth::user()->isLecturer())
                    Role: Dosen Pengajar
                @else
                    Role: Mahasiswa Aktif
                @endif
            </span>
            <h2 class="text-2xl md:text-3xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h2>
            <p class="mt-2 text-indigo-100 text-sm leading-relaxed">
                @if(Auth::user()->isAdmin())
                    Anda memiliki akses penuh untuk mengelola master data Mahasiswa, Dosen, Jadwal Kuliah, dan Penilaian Akademik.
                @elseif(Auth::user()->isLecturer())
                    Anda dapat melihat jadwal mengajar Anda serta menginput dan mengelola nilai mahasiswa untuk mata kuliah yang Anda ampu.
                @else
                    Anda dapat memantau jadwal kuliah perkuliahan dan mengecek riwayat nilai serta Kartu Hasil Studi (KHS) Anda.
                @endif
            </p>
        </div>
        <div class="absolute -right-6 -bottom-10 opacity-15 pointer-events-none">
            <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
        </div>
    </div>

    @if(Auth::user()->isAdmin())
    <!-- Admin Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Mahasiswa</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalStudents }}</p>
                <a href="{{ route('students.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua &rarr;</a>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Dosen</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalLecturers }}</p>
                <a href="{{ route('lecturers.index') }}" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">Lihat Semua &rarr;</a>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mata Kuliah</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalCourses }}</p>
                <span class="text-xs text-slate-400">Aktif Semester Ini</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jadwal Kuliah</p>
                <p class="text-2xl font-bold text-slate-800">{{ $totalSchedules }}</p>
                <a href="{{ route('schedules.index') }}" class="text-xs text-amber-600 hover:text-amber-800 font-medium">Lihat Semua &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Recent Schedules Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">Jadwal Perkuliahan Terkini</h3>
                <p class="text-xs text-slate-500">Daftar jadwal perkuliahan semester berjalan</p>
            </div>
            <a href="{{ route('schedules.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Semua Jadwal &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Hari & Jam</th>
                        <th class="px-6 py-3">Mata Kuliah</th>
                        <th class="px-6 py-3">Dosen Pengampu</th>
                        <th class="px-6 py-3">Ruang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentSchedules as $item)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-6 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-800 mr-2">{{ $item->day }}</span>
                            <span class="text-xs text-slate-500 font-mono">{{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="font-semibold text-slate-800">{{ $item->course->name ?? '-' }}</div>
                            <div class="text-xs text-slate-400 font-mono">{{ $item->course->code ?? '' }} &bull; {{ $item->course->credits ?? 0 }} SKS</div>
                        </td>
                        <td class="px-6 py-3.5 text-slate-700">{{ $item->lecturer->name ?? '-' }}</td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $item->room }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-slate-400">Belum ada data jadwal kuliah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if(Auth::user()->isLecturer())
    <!-- Lecturer Profile & Schedules -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm md:col-span-1">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Profil Dosen</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block">NIDN</span>
                    <span class="font-mono font-semibold text-slate-700">{{ $lecturer->nidn ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Nama Lengkap</span>
                    <span class="font-semibold text-slate-800">{{ $lecturer->name ?? Auth::user()->name }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Email Institusi</span>
                    <span class="text-slate-700">{{ $lecturer->email ?? Auth::user()->email }}</span>
                </div>
                <div class="pt-2 border-t border-slate-100">
                    <span class="text-xs text-slate-400 block">Status Akun Terhubung</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800 mt-1">✓ Terhubung ke User Akun</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm md:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Jadwal Mengajar Saya</h3>
                <a href="{{ route('grades.create') }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow">
                    + Input Nilai Mahasiswa
                </a>
            </div>

            <div class="space-y-3">
                @forelse($mySchedules as $schedule)
                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="font-bold text-slate-800">{{ $schedule->course->name }}</div>
                        <div class="text-xs text-slate-500 font-mono mt-0.5">
                            {{ $schedule->day }} &bull; {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }} &bull; {{ $schedule->room }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('schedules.show', $schedule) }}" class="px-2.5 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-100">
                            Lihat Mahasiswa
                        </a>
                        <a href="{{ route('grades.index', ['schedule_id' => $schedule->id]) }}" class="px-2.5 py-1.5 bg-indigo-50 border border-indigo-200 rounded-lg text-xs font-medium text-indigo-700 hover:bg-indigo-100">
                            Kelola Nilai
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-6">Anda belum memiliki jadwal mengajar aktif.</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    @if(Auth::user()->isStudent())
    <!-- Student Overview & Grades -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Biodata Mahasiswa</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block">NIM (Nomor Induk Mahasiswa)</span>
                    <span class="font-mono font-semibold text-slate-700">{{ $student->student_number ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Nama Mahasiswa</span>
                    <span class="font-semibold text-slate-800">{{ $student->name ?? Auth::user()->name }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Semester Saat Ini</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-sky-100 text-sky-800">Semester {{ $student->semester ?? 1 }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Email Mahasiswa</span>
                    <span class="text-slate-700">{{ $student->email ?? Auth::user()->email }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm md:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Kartu Hasil Studi (KHS)</h3>
                    <p class="text-xs text-slate-400">Rangkuman nilai mata kuliah yang telah dinilai</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-slate-400 block">Rata-rata Nilai</span>
                    <span class="text-xl font-black text-indigo-600">{{ number_format($averageScore, 2) }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-2.5">Mata Kuliah</th>
                            <th class="px-4 py-2.5">Dosen</th>
                            <th class="px-4 py-2.5 text-center">Nilai Angka</th>
                            <th class="px-4 py-2.5 text-center">Huruf</th>
                            <th class="px-4 py-2.5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($grades as $g)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-800">
                                {{ $g->schedule->course->name ?? '-' }}
                                <div class="text-xs text-slate-400 font-mono">{{ $g->schedule->course->code ?? '' }} &bull; {{ $g->schedule->course->credits ?? 0 }} SKS</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-600">{{ $g->schedule->lecturer->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-center font-mono font-semibold">{{ number_format($g->score, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ in_array($g->grade_letter, ['A', 'A-']) ? 'bg-emerald-100 text-emerald-800' : (in_array($g->grade_letter, ['B+', 'B', 'C+', 'C']) ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $g->grade_letter }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $g->remarks }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400 text-xs">Belum ada data nilai perkuliahan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
