@extends('layouts.main')

@section('title', 'Tentang SIMAKSI - Sistem Manajemen Pendakian')

@push('styles')
<style>
    /* Tentang (khusus halaman ini) */
    .vm-box{ height:100%; padding:1.25rem; border-radius:var(--r-md); background:var(--c-green-50); border:1px solid rgba(21,128,61,.18); transition:transform var(--dur-base) var(--ease-out), box-shadow var(--dur-base) var(--ease-out); }
    .vm-box:hover{ transform:translateY(-4px); box-shadow:var(--sh-md); }
    .about-timeline{ position:relative; display:grid; gap:1.4rem; }
    .about-timeline::before{ content:""; position:absolute; left:21px; top:8px; bottom:8px; width:2px; background:var(--c-sand-200); }
    .about-step{ position:relative; display:flex; gap:1rem; }
    .about-step__num{ position:relative; z-index:1; flex-shrink:0; width:44px; height:44px; border-radius:14px; display:grid; place-items:center; font-weight:800; font-family:var(--font-display); background:#fff; color:var(--c-green-700); border:2px solid var(--c-green-600); transition:transform .6s var(--ease-spring), background-color .3s ease, color .3s ease; }
    .about-step:hover .about-step__num{ background:var(--c-green-700); color:#fff; transform:rotate(-8deg) scale(1.08); }
</style>
@endpush

@section('content')
<section class="container">
    {{-- Hero --}}
    <x-ui.page-header eyebrow="Tentang SIMAKSI" icon="fa-info-circle" title="Membuat pendakian lebih tertata dan aman"
        subtitle="SIMAKSI membantu pendaki mengakses informasi jalur resmi, panduan keselamatan, dan pengurusan surat izin dengan lebih mudah."
        :image="asset('images/gunung/arjuno.jpg')" :crumbs="['Tentang' => null]">
        <span class="chip"><i class="fa fa-mountain-sun"></i> Sistem Informasi Manajemen Pendakian Gunung Indonesia</span>
    </x-ui.page-header>

    <div class="row g-4">
        {{-- Visi & Misi --}}
        <div class="col-12 col-lg-7">
            <div class="card-simaksi p-4 p-md-5 h-100" data-reveal="fade-right">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="stat-tile__icon mb-0" style="width:40px;height:40px;"><i class="fa fa-bullseye"></i></span>
                    <h2 class="h4 fw-bold mb-0">Visi &amp; Misi</h2>
                </div>

                <div class="row g-3" data-stagger>
                    <div class="col-12 col-md-6">
                        <div class="vm-box">
                            <div class="fw-bold mb-2"><i class="fa fa-eye me-2 text-green"></i>Visi</div>
                            <p class="text-muted mb-0" style="line-height:1.75;">Meningkatkan akses informasi pendakian resmi agar pendaki dapat membuat keputusan yang lebih aman dan bertanggung jawab.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="vm-box">
                            <div class="fw-bold mb-2"><i class="fa fa-flag me-2 text-green"></i>Misi</div>
                            <ul class="text-muted mb-0" style="line-height:1.8; padding-left: 1.1rem;">
                                <li>Memberikan data jalur resmi dan panduan keselamatan.</li>
                                <li>Mempermudah pengurusan surat izin pendakian.</li>
                                <li>Menumbuhkan kesadaran konservasi dan keselamatan.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-muted" style="line-height:1.85;">
                    <p class="mb-3"><strong>SIMAKSI</strong> (Sistem Informasi Manajemen Pendakian Gunung) adalah platform digital yang dirancang untuk membantu pendaki dalam mengakses informasi jalur resmi, panduan keselamatan, serta pengurusan surat izin pendakian dengan mudah.</p>
                    <p class="mb-3">Dengan adanya SIMAKSI, proses perizinan pendakian menjadi lebih terstruktur, efisien, dan transparan. Pendaki dapat mengetahui informasi gunung, rute pendakian resmi, serta mempelajari panduan penting sebelum mendaki.</p>
                    <p class="mb-0">Website ini juga bertujuan untuk meningkatkan kesadaran pendaki terhadap pentingnya konservasi alam dan keselamatan dalam aktivitas pendakian. Kami bekerja sama dengan taman nasional dan lembaga terkait untuk menyediakan data resmi dan akurat.</p>
                </div>
            </div>
        </div>

        {{-- Timeline --}}
        <div class="col-12 col-lg-5">
            <div class="card-simaksi p-4 p-md-5 h-100" data-reveal="fade-left">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="stat-tile__icon mb-0" style="width:40px;height:40px;"><i class="fa fa-history"></i></span>
                    <h2 class="h4 fw-bold mb-0">Timeline</h2>
                </div>

                <div class="about-timeline" data-stagger="fade-left">
                    <div class="about-step">
                        <div class="about-step__num">1</div>
                        <div>
                            <div class="fw-bold">Koleksi Data</div>
                            <div class="text-muted" style="line-height:1.7;">Mengumpulkan informasi jalur resmi & detail pendakian.</div>
                        </div>
                    </div>
                    <div class="about-step">
                        <div class="about-step__num">2</div>
                        <div>
                            <div class="fw-bold">Validasi & Integrasi</div>
                            <div class="text-muted" style="line-height:1.7;">Menyatukan data ke dalam alur sistem SIMAKSI.</div>
                        </div>
                    </div>
                    <div class="about-step">
                        <div class="about-step__num">3</div>
                        <div>
                            <div class="fw-bold">Publik & Pemantauan</div>
                            <div class="text-muted" style="line-height:1.7;">Mengoptimalkan pengalaman pengguna dan pembaruan informasi.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection
