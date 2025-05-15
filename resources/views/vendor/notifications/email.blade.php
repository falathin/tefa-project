<x-mail::message>
{{-- Salam Pembuka --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# Whoops! Ada masalah 😕
@else
# Halo!
@endif
@endif

{{-- Paragraf Awal --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Tombol Aksi --}}
@isset($actionText)
<?php
    // Pakai warna merah untuk semua level (bisa disesuaikan kalau mau lebih dinamis)
    $color = 'red';
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Paragraf Penutup --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salam Penutup --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Salam hangat,<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Jika tombol "<strong>{{ $actionText }}</strong>" tidak bisa diklik, salin dan tempel URL berikut ke browser kamu:<br>
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
