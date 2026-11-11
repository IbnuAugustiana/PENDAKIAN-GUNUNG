@extends('layouts.main')

@section('title', 'Landing SIMAKSI')
@section('body_class', 'has-hero')

{{-- =========================================================
     HERO — full-width, parallax, Ken Burns, kabut, siluet berlapis,
     judul split-text, kartu foto tilt 3D + floating badge.
     Visual-only. Tidak ada route/controller/logic yang diubah.
     ========================================================= --}}
@section('hero')
<section class="hero" aria-label="Pembuka">
    <div class="hero__bg" aria-hidden="true">
        <img src="{{ asset('images/gunung/arjuno.jpg') }}" alt="" fetchpriority="high">
    </div>
    <div class="hero__overlay" aria-hidden="true"></div>
    <span class="hero__mist hero__mist--1" aria-hidden="true"></span>
    <span class="hero__mist hero__mist--2" aria-hidden="true"></span>
    <span class="hero__mist hero__mist--3" aria-hidden="true"></span>

    <div class="hero__container">
        <div class="hero__grid">
            <div>
                <span class="hero__badge" data-hero><span class="dot"><i class="fa fa-check"></i></span> SIMAKSI Gunung · Perizinan Online</span>

                <h1 class="hero__title" data-hero data-split>
                    Kelola perizinan pendakian Anda
                    <span class="text-gradient">lebih rapi dan <span class="text-highlight">terpercaya<svg class="hero__underline" viewBox="0 0 300 20" preserveAspectRatio="none" aria-hidden="true"><path pathLength="420" d="M4 14 C 70 4, 160 3, 296 11"/></svg></span></span>
                </h1>

                <p class="hero__sub" data-hero>
                    Temukan gunung populer, pilih rute resmi, dan selesaikan SIMAKSI dalam satu platform yang ringkas, modern, dan mudah dipantau statusnya.
                </p>

                <div class="hero__actions">
                    <a href="{{ route('register') }}" class="btn btn-ui--sun btn-lg" data-hero>
                        <i class="fa fa-file-signature me-2"></i> Daftar Sekarang
                    </a>
                    <a href="{{ route('gunung.index') }}" class="btn btn-ui--glass btn-lg" data-hero>
                        <i class="fa fa-mountain me-2"></i> Jelajahi Gunung
                    </a>
                </div>

                <div class="hero__trust">
                    <span class="chip" data-hero><i class="fa fa-circle-check"></i> Rute Resmi</span>
                    <span class="chip" data-hero><i class="fa fa-circle-check"></i> Data Terintegrasi</span>
                    <span class="chip" data-hero><i class="fa fa-circle-check"></i> Proses Cepat</span>
                    <span class="chip" data-hero><i class="fa fa-circle-check"></i> Aman</span>
                </div>
            </div>

            <div class="hero__visual" data-hero>
                <div class="tilt-card" data-tilt="9">
                    <div class="hero__photo">
                        <img src="{{ asset('images/gunung/GunungMerapi.jpg') }}" alt="Gunung Merapi saat golden hour">
                        <div class="hero__photo-caption">
                            <div class="name">Gunung Merapi</div>
                            <div class="meta"><i class="fa fa-location-dot me-1"></i> Sleman, Yogyakarta · 2.930 mdpl</div>
                        </div>
                        <span class="hero__glare" aria-hidden="true"></span>
                    </div>

                    <div class="float-badge float-badge--1 floating">
                        <div class="float-badge__box">
                            <span class="ic"><i class="fa fa-star"></i></span>
                            <span>Gunung Populer<small>★★★★★</small></span>
                        </div>
                    </div>
                    <div class="float-badge float-badge--2 floating">
                        <div class="float-badge__box">
                            <span class="ic"><i class="fa fa-route"></i></span>
                            <span>Rute Resmi<small>Jalur terkurasi</small></span>
                        </div>
                    </div>
                    <div class="float-badge float-badge--3 floating">
                        <div class="float-badge__box">
                            <span class="ic"><i class="fa fa-circle-check"></i></span>
                            <span>SIMAKSI Ready<small>Ajukan online</small></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero__layers" aria-hidden="true">
        @include('partials.ui.ridges', ['variant' => 'hero'])
    </div>

    <a href="#statistik" class="scroll-cue" aria-label="Gulir ke bawah">
        <span class="scroll-cue__mouse"></span>
        <i class="fa fa-chevron-down"></i>
    </a>
</section>
@endsection

@section('content')

{{-- ===================== STATISTIK (counter) ===================== --}}
<section class="stats-band" id="statistik" aria-label="Statistik">
    <div class="row g-3" data-stagger="scale">
        <div class="col-6 col-lg-3">
            <x-ui.stat-card icon="fa-mountain" label="Gunung Terdaftar" value="15+" :count="15" suffix="+" />
        </div>
        <div class="col-6 col-lg-3">
            <x-ui.stat-card icon="fa-route" label="Rute Resmi" value="100%" :count="100" suffix="%" tone="sun" />
        </div>
        <div class="col-6 col-lg-3">
            <x-ui.stat-card icon="fa-signal" label="Pantau Status" value="24/7" :count="24" suffix="/7" tone="info" />
        </div>
        <div class="col-6 col-lg-3">
            <x-ui.stat-card icon="fa-layer-group" label="Platform Terpadu" value="1" :count="1" />
        </div>
    </div>
</section>

{{-- ===================== KENAPA SIMAKSI ===================== --}}
<section class="section">
    <div class="row g-4 align-items-end mb-2">
        <div class="col-lg-7">
            <x-ui.section-heading eyebrow="Kenapa SIMAKSI" title="Dirancang agar proses pendakian lebih tertata" subtitle="Fokus pada kejelasan informasi dan alur yang ringkas, dari pendaftaran hingga hari pendakian." reveal="fade-left" />
        </div>
    </div>

    <div class="row g-3 g-lg-4" data-stagger>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-card__icon"><i class="fa fa-check-circle"></i></div>
                <h3>Pendaftaran Mudah</h3>
                <p>Form terstruktur untuk mempercepat proses dan mengurangi kesalahan input.</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-card__icon"><i class="fa fa-lock"></i></div>
                <h3>Aman &amp; Terpercaya</h3>
                <p>Status dipantau dari dashboard, sehingga Anda selalu tahu progresnya.</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="feature-card">
                <div class="feature-card__icon"><i class="fa fa-compass"></i></div>
                <h3>Panduan Lengkap</h3>
                <p>Panduan keselamatan dan alur pendakian yang praktis sebelum berangkat.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===================== GUNUNG POPULER ===================== --}}
<section class="section pt-0">
    <div class="section-heading-row">
        <x-ui.section-heading eyebrow="Destinasi" title="Gunung Populer" subtitle="Pilihan favorit pendaki — arahkan kursor untuk melihat detailnya." reveal="fade-right" />
        <a href="{{ route('gunung.index') }}" class="link-arrow" data-reveal="fade-left">
            Lihat semua gunung <i class="fa fa-arrow-right"></i>
        </a>
    </div>

    <div class="row g-3 g-lg-4" data-stagger>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.mountain-card :href="route('gunung.index')" :image="asset('images/gunung/GunungRinjani.jpg')"
                name="Gunung Rinjani" location="Nusa Tenggara Barat" :height="3726" tag="Populer"
                description="Gunung api tertinggi kedua di Indonesia dengan Danau Segara Anak di kalderanya." />
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.mountain-card :href="route('gunung.index')" :image="asset('images/gunung/GunungBromo.jpg')"
                name="Gunung Bromo" location="Jawa Timur" :height="2329"
                description="Kaldera lautan pasir yang ikonik, favorit pemburu sunrise dan pendaki pemula." />
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.mountain-card :href="route('gunung.index')" :image="asset('images/gunung/GunungMerbabu.jpg')"
                name="Gunung Merbabu" location="Jawa Tengah" :height="3145"
                description="Sabana luas dengan panorama Merapi dari dekat, jalurnya ramah untuk pemula." />
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <x-ui.mountain-card :href="route('gunung.index')" :image="asset('images/gunung/Gunung_Kerinci.png')"
                name="Gunung Kerinci" location="Jambi" :height="3805"
                description="Gunung api tertinggi di Indonesia, berada di kawasan Taman Nasional Kerinci Seblat." />
        </div>
    </div>
</section>

{{-- ===================== CARA MENDAFTAR (step) ===================== --}}
<section class="section pt-0">
    <x-ui.section-heading eyebrow="Alur" title="Cara Mendaftar" subtitle="Empat langkah sederhana dari memilih gunung sampai siap mendaki." align="center" reveal="scale" />

    <div class="steps" data-steps>
        <div class="steps__track" aria-hidden="true"><div class="steps__fill"></div></div>
        <div class="step" data-reveal="fade-up">
            <div class="step__num"><i class="fa fa-mountain"></i><b>1</b></div>
            <div>
                <h3>Pilih Gunung</h3>
                <p>Jelajahi daftar gunung dan pilih tujuan pendakian Anda.</p>
            </div>
        </div>
        <div class="step" data-reveal="fade-up" data-delay=".1">
            <div class="step__num"><i class="fa fa-route"></i><b>2</b></div>
            <div>
                <h3>Pilih Rute</h3>
                <p>Lihat detail rute resmi yang tersedia untuk gunung tersebut.</p>
            </div>
        </div>
        <div class="step" data-reveal="fade-up" data-delay=".2">
            <div class="step__num"><i class="fa fa-file-signature"></i><b>3</b></div>
            <div>
                <h3>Isi SIMAKSI</h3>
                <p>Lengkapi formulir pendaftaran dan data identitas dengan mudah.</p>
            </div>
        </div>
        <div class="step" data-reveal="fade-up" data-delay=".3">
            <div class="step__num"><i class="fa fa-signal"></i><b>4</b></div>
            <div>
                <h3>Pantau Status</h3>
                <p>Cek status pendaftaran langsung dari dashboard Anda.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===================== FAQ ===================== --}}
<section class="section pt-0">
    <div class="faq-grid">
        <div class="faq-aside">
            <x-ui.section-heading eyebrow="Bantuan" title="Pertanyaan yang Sering Diajukan" subtitle="Belum menemukan jawaban? Pelajari panduan lengkap sebelum berangkat." reveal="fade-left" />
            <div class="faq-aside__card" data-reveal="fade-up" data-delay=".15">
                <div class="fw-bold" style="font-family:var(--font-display); font-size:1.1rem;"><i class="fa fa-compass me-2 text-warning"></i>Panduan Pendakian</div>
                <p>Persiapan administrasi, peralatan, hingga etika di jalur resmi.</p>
                <a href="{{ route('panduan') }}" class="btn btn-ui--sun btn-sm">Baca panduan <i class="fa fa-arrow-right ms-1"></i></a>
            </div>
        </div>

        <div class="accordion landing-faq" id="landingFaqAccordion" data-stagger="fade-right">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqSatu">
                        Apa itu SIMAKSI?
                    </button>
                </h2>
                <div id="faqSatu" class="accordion-collapse collapse show" data-bs-parent="#landingFaqAccordion">
                    <div class="accordion-body">
                        SIMAKSI adalah platform untuk mengelola perizinan pendakian gunung secara digital, mulai dari pemilihan gunung dan rute hingga pemantauan status pendaftaran.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqDua">
                        Bagaimana cara mendaftar pendakian?
                    </button>
                </h2>
                <div id="faqDua" class="accordion-collapse collapse" data-bs-parent="#landingFaqAccordion">
                    <div class="accordion-body">
                        Buat akun terlebih dahulu, lalu pilih gunung dan rute yang tersedia, kemudian lengkapi formulir SIMAKSI melalui dashboard Anda.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqTiga">
                        Apakah saya bisa memantau status pendaftaran?
                    </button>
                </h2>
                <div id="faqTiga" class="accordion-collapse collapse" data-bs-parent="#landingFaqAccordion">
                    <div class="accordion-body">
                        Bisa. Setiap status pendaftaran dapat dipantau secara real-time melalui dashboard setelah Anda login.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqEmpat">
                        Apakah rute yang ditampilkan resmi?
                    </button>
                </h2>
                <div id="faqEmpat" class="accordion-collapse collapse" data-bs-parent="#landingFaqAccordion">
                    <div class="accordion-body">
                        Ya, seluruh rute yang ditampilkan merupakan rute resmi yang dikurasi untuk masing-masing gunung.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===================== CTA PENUTUP ===================== --}}
<section class="pt-0">
    <div class="cta" data-reveal="zoom-out">
        <div class="cta__bg" aria-hidden="true">
            <img src="{{ asset('images/gunung/GunungLawu.jpg') }}" alt="" data-parallax="16" loading="lazy">
        </div>
        <div class="eyebrow justify-content-center" style="color:var(--c-green-300);">Mulai Sekarang</div>
        <h2>Siap memulai pendakian Anda?</h2>
        <p>Daftarkan diri Anda sekarang dan nikmati proses SIMAKSI yang lebih rapi, jelas, dan mudah dipantau.</p>
        <div class="cta__actions">
            <a href="{{ route('register') }}" class="btn btn-ui--sun btn-lg">
                <i class="fa fa-file-signature me-2"></i> Daftar Sekarang
            </a>
            <a href="{{ route('panduan') }}" class="btn btn-ui--glass btn-lg">
                <i class="fa fa-compass me-2"></i> Baca Panduan
            </a>
        </div>
    </div>
</section>
@endsection
