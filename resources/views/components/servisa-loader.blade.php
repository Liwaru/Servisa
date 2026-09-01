@props([
    'text' => 'Memuat data...',
    'overlay' => false,
])

@once
    <link rel="stylesheet" href="{{ asset('css/servisa-loader.css') }}">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script defer src="{{ asset('js/servisa-loader.js') }}"></script>
@endonce

@if ($overlay)
    <div
        id="servisa-loader-overlay"
        class="servisa-loader-overlay"
        data-servisa-loader-overlay
        data-animation-path="{{ asset('animations/wrench-loading.json') }}"
        aria-hidden="true"
        hidden
    >
        <div class="servisa-loader-content" role="status" aria-live="polite" aria-atomic="true">
            <div class="servisa-loader-animation" data-servisa-lottie aria-hidden="true"></div>
            <p class="servisa-loader-text" data-servisa-loader-text>{{ $text }}</p>
        </div>
    </div>
@else
    <div
        {{ $attributes->class(['servisa-loader-inline']) }}
        data-servisa-loader-inline
        data-animation-path="{{ asset('animations/wrench-loading.json') }}"
        role="status"
        aria-live="polite"
        aria-atomic="true"
    >
        <div class="servisa-loader-animation" data-servisa-lottie aria-hidden="true"></div>
        <p class="servisa-loader-text">{{ $text }}</p>
    </div>
@endif
