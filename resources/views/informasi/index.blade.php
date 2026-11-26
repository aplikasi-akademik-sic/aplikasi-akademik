@extends('layouts.app')

@section('title', 'Informasi Akademik')
@section('page_title', 'Informasi Akademik')

@section('content')
    @if($informasi->isEmpty())
        <p class="student-list p-5 muted">Belum ada informasi</p>
    @else
        @php($selected = $selectedInformasi ?? $informasi->first())
        <div class="grid items-start gap-5 md:grid-cols-[minmax(14rem,1fr)_minmax(0,2fr)]">
            <nav class="student-list min-w-0" aria-label="Daftar pengumuman">
                <h2 class="p-4 font-semibold">Daftar pengumuman</h2>
                <ul>
                    @foreach($informasi as $item)
                        <li class="border-t border-slate-200">
                            <a href="{{ route('informasi.show', $item->id) }}"
                                class="block px-4 py-3 hover:bg-slate-50 aria-[current=page]:bg-indigo-50"
                                @if($item->id === $selected->id) aria-current="page" @endif>
                                <span class="block font-semibold text-indigo-800 underline wrap-anywhere">{{ $item->judul }}</span>
                                <span class="muted text-sm">@include('informasi._tanggal')</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @if($informasi instanceof \Illuminate\Contracts\Pagination\Paginator && $informasi->hasPages())
                    <div class="border-t border-slate-200 p-4">{{ $informasi->links() }}</div>
                @endif
            </nav>
            <article class="student-list min-w-0 p-5 sm:p-7" aria-labelledby="judul-pengumuman">
                <h2 class="text-xl font-bold wrap-anywhere" id="judul-pengumuman">{{ $selected->judul }}</h2>
                <p class="mt-1 muted text-sm">Terbit @include('informasi._tanggal', ['item' => $selected])</p>
                <div class="mt-5 whitespace-pre-wrap leading-relaxed wrap-anywhere">{{ $selected->isi }}</div>
            </article>
        </div>
    @endif
@endsection
