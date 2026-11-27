<a class="record-link" href="{{ $url }}">
    <span class="record-symbol" aria-hidden="true">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
            @if(isset($courseCode))
                <path d="M12 6c-3-2-6-2.5-10-2v14c4-.5 7 0 10 2 3-2 6-2.5 10-2V4c-4-.5-7 0-10 2Zm0 0v14"/>
            @else
                <circle cx="12" cy="8" r="3.5"/><path d="M5 20v-2a7 7 0 0 1 14 0v2"/>
            @endif
        </svg>
    </span>
    <span>{{ $label }}@isset($courseCode)<small class="course-meta">{{ $courseCode }} · {{ $credits }} SKS</small>@endisset</span>
    <span class="record-chevron" aria-hidden="true"></span>
</a>
