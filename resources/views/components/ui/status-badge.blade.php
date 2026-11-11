{{-- Badge status izin berwarna. Tidak peka huruf besar/kecil sehingga
     nilai seperti "Menunggu", "pending", "disetujui", "ditolak" semuanya terbaca.
     Status menunggu mendapat titik "pulse".
     <x-ui.status-badge :status="$p->status" />  (label opsional: label="...") --}}
@props(['status' => null, 'label' => null])
@php
    $s = strtolower(trim((string) $status));
    $map = [
        'pending'  => ['menunggu', 'pending', 'menunggu persetujuan', 'proses', 'diproses', 'belum_verifikasi', 'sedang_verifikasi', ''],
        'approved' => ['disetujui', 'approved', 'diterima', 'lunas', 'paid', 'settlement', 'success', 'selesai', 'terverifikasi'],
        'rejected' => ['ditolak', 'rejected', 'gagal', 'failed', 'batal', 'dibatalkan', 'expire', 'expired', 'belum', 'cancel'],
    ];
    $tone = 'neutral';
    foreach ($map as $key => $values) { if (in_array($s, $values, true)) { $tone = $key; break; } }
    $defaults = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'neutral' => ucfirst($s)];
    $text = $label ?? $defaults[$tone];
@endphp
<span {{ $attributes->merge(['class' => 'status-badge status-badge--' . $tone]) }}>
    <span class="sb-dot" aria-hidden="true"></span>{{ $text }}
</span>
