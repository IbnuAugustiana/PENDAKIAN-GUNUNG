@extends('layouts.main')

@section('title', 'Panduan Pendakian - SIMAKSI')

@push('styles')
<style>
    /* Timeline panduan (khusus halaman ini) */
    .guide-timeline{ position:relative; display:grid; gap:1.25rem; }
    .guide-timeline::before{ content:""; position:absolute; left:31px; top:12px; bottom:12px; width:2px; background:linear-gradient(180deg,var(--c-green-600),var(--c-sun-500)); opacity:.35; border-radius:2px; }
    .guide-item{ position:relative; display:grid; grid-template-columns:64px 1fr; gap:1.25rem; align-items:start; }
    .guide-item__marker{ position:relative; z-index:1; width:64px; height:64px; border-radius:20px; display:grid; place-items:center; font-size:1.3rem; color:#fff; background:linear-gradient(135deg,var(--c-green-600),var(--c-forest-800)); box-shadow:var(--sh-green); transition:transform .6s var(--ease-spring); }
    .guide-item__marker b{ position:absolute; top:-8px; right:-8px; width:24px; height:24px; border-radius:50%; display:grid; place-items:center; font-size:.72rem; background:var(--c-sun-500); color:var(--c-forest-950); font-family:var(--font-display); }
    .guide-item:hover .guide-item__marker{ transform:rotate(-8deg) scale(1.06); }
    .guide-item .card-simaksi h2{ font-size:1.2rem; }
    .guide-tips{ display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1rem; }
    @media (max-width:767.98px){ .guide-tips{ grid-template-columns:1fr; } .guide-item{ grid-template-columns:52px 1fr; gap:1rem; } .guide-item__marker{ width:52px; height:52px; border-radius:16px; font-size:1.05rem; } .guide-timeline::before{ left:25px; } }
</style>
@endpush

@section('content')
<section class="container">
    <x-ui.page-header eyebrow="Panduan" icon="fa-compass" title="Panduan Pendakian Gunung"
        subtitle="Ikuti langkah-langkah berikut agar pendakianmu aman, nyaman, dan sesuai prosedur resmi."
        :image="asset('images/gunung/GunungMerbabu.jpg')" :crumbs="['Panduan' => null]">
        <span class="chip"><i class="fa fa-list-check"></i> 5 langkah</span>
        <span class="chip"><i class="fa fa-shield-heart"></i> Keselamatan utama</span>
    </x-ui.page-header>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="guide-timeline">
                <div class="guide-item" data-reveal="fade-left">
                    <div class="guide-item__marker"><i class="fa fa-clipboard-check"></i><b>1</b></div>
                    <div class="card-simaksi p-4 hover-simaksi">
                        <h2 class="fw-bold mb-2">Persiapan Administrasi</h2>
                        <p class="text-muted mb-0" style="line-height:1.75;">Lengkapi identitas diri, surat izin pendakian (SIMAKSI), serta dokumen pendukung seperti surat keterangan sehat dari puskesmas atau dokter. Semua dokumen ini wajib sebelum memulai pendakian.</p>
                    </div>
                </div>

                <div class="guide-item" data-reveal="fade-left">
                    <div class="guide-item__marker"><i class="fa fa-suitcase-rolling"></i><b>2</b></div>
                    <div class="card-simaksi p-4">
                        <h2 class="fw-bold mb-2">Persiapan Peralatan</h2>
                        <p class="text-muted mb-0" style="line-height:1.75;">Bawa perlengkapan sesuai kebutuhan: tenda, matras, sleeping bag, pakaian hangat, jas hujan, obat pribadi, dan peralatan masak. Jika perlu, pinjam alat pendakian melalui sistem peminjaman alat yang tersedia di web ini. Hindari membawa barang berlebihan yang tidak diperlukan.</p>
                    </div>
                </div>

                <div class="guide-item" data-reveal="fade-left">
                    <div class="guide-item__marker"><i class="fa fa-route"></i><b>3</b></div>
                    <div class="card-simaksi p-4">
                        <h2 class="fw-bold mb-2">Mengikuti Jalur Resmi</h2>
                        <p class="text-muted mb-0" style="line-height:1.75;">Gunakan jalur pendakian resmi yang sudah ditetapkan oleh pengelola taman nasional. Hindari jalur ilegal untuk menjaga keselamatan dan kelestarian alam sekitar.</p>
                    </div>
                </div>

                <div class="guide-item" data-reveal="fade-left">
                    <div class="guide-item__marker"><i class="fa fa-leaf"></i><b>4</b></div>
                    <div class="card-simaksi p-4">
                        <h2 class="fw-bold mb-2">Jaga Kebersihan &amp; Alam</h2>
                        <p class="text-muted mb-0" style="line-height:1.75;">Bawa kembali semua sampah. Jangan meninggalkan jejak, termasuk sisa makanan, plastik, dan benda berbahaya lainnya. Ingat, pendaki bertanggung jawab menjaga keindahan alam.</p>
                    </div>
                </div>

                <div class="guide-item" data-reveal="fade-left">
                    <div class="guide-item__marker"><i class="fa fa-flag"></i><b>5</b></div>
                    <div class="card-simaksi p-4">
                        <h2 class="fw-bold mb-2">Lapor Sebelum &amp; Sesudah Pendakian</h2>
                        <p class="text-muted mb-0" style="line-height:1.75;">Lapor ke pos pendakian saat naik dan turun gunung. Petugas akan mencatat data pendaki untuk keamanan dan pemantauan cuaca atau kondisi gunung. Jika meminjam alat, pastikan untuk mengkonfirmasi peminjaman di pos sebelum masuk kawasan konservasi.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="faq-aside" style="top:calc(var(--nav-h) + 16px);">
                <div class="faq-aside__card mt-0" data-reveal="fade-right">
                    <div class="fw-bold" style="font-family:var(--font-display); font-size:1.15rem;"><i class="fa fa-file-signature me-2 text-warning"></i>Siap mengajukan izin?</div>
                    <p>Setelah memahami panduan, pilih gunung dan rute resmi lalu isi formulir SIMAKSI.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('gunung.index') }}" class="btn btn-ui--sun btn-sm"><i class="fa fa-mountain me-1"></i> Pilih Gunung</a>
                        <a href="{{ route('rute_pendakian.index') }}" class="btn btn-ui--glass btn-sm"><i class="fa fa-route me-1"></i> Lihat Rute Resmi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
