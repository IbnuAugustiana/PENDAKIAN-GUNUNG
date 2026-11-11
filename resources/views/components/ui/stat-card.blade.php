{{-- Kartu statistik dengan counter animasi.
     value  : teks yang tampil (tanpa JS) — mis. "15+"
     count  : angka target animasi (opsional) — mis. 15
     prefix/suffix : teks sebelum/sesudah angka saat animasi
     tone   : green | sun | info | danger --}}
@props(['icon' => 'fa-chart-simple', 'label', 'value', 'count' => null, 'prefix' => '', 'suffix' => '', 'tone' => 'green'])
<div {{ $attributes->merge(['class' => 'stat-tile stat-tile--' . $tone]) }}>
    <div class="stat-tile__icon"><i class="fa {{ $icon }}" aria-hidden="true"></i></div>
    <div class="stat-tile__value"
         @if(!is_null($count)) data-count="{{ $count }}" data-prefix="{{ $prefix }}" data-suffix="{{ $suffix }}" @endif>{{ $value }}</div>
    <div class="stat-tile__label">{{ $label }}</div>
</div>
