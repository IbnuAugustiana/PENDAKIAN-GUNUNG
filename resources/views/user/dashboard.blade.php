@extends('layouts.main')

@section('title', 'Dashboard SIMAKSI.COM')

@push('styles')
<style>
    /* ======================================================
       Dashboard User — scoped styles (halaman ini saja)
       Warna utama: #15803d | Radius: 16px | Shadow ringan
       ====================================================== */
    :root{
        --dash-primary:#15803d;
        --dash-primary-dark:#0f5c2c;
        --dash-primary-soft:rgba(21,128,61,.10);
        --dash-border:rgba(15,23,42,.08);
        --dash-muted:#64748b;
        --dash-text:#0f172a;
        --dash-radius:16px;
        --dash-shadow:0 6px 20px rgba(15,23,42,.06);
        --dash-shadow-hover:0 12px 28px rgba(15,23,42,.10);
    }

    .dash-wrap{ display:flex; flex-direction:column; gap:2.5rem; }

    /* ---------- Hero ---------- */
    .dash-hero{
        border-radius:var(--dash-radius);
        border:0;
        color:#fff;
        background:linear-gradient(135deg, var(--dash-primary) 0%, var(--dash-primary-dark) 100%);
        box-shadow:var(--dash-shadow);
        padding:2.25rem 2.5rem;
        position:relative;
        overflow:hidden;
    }
    .dash-hero::after{
        content:"";
        position:absolute; inset:0;
        background:radial-gradient(circle at 92% -20%, rgba(255,255,255,.16), transparent 55%);
        pointer-events:none;
    }
    .dash-hero .greeting{ font-size:clamp(1.3rem, 2vw, 1.9rem); font-weight:800; letter-spacing:-.01em; margin-bottom:.35rem; }
    .dash-hero .subtitle{ opacity:.92; font-size:.98rem; margin-bottom:0; }
    .dash-hero .btn-hero{
        background:#fff; color:var(--dash-primary); font-weight:700;
        border:0; border-radius:12px; padding:.75rem 1.4rem;
        box-shadow:0 6px 16px rgba(0,0,0,.12); white-space:nowrap;
    }
    .dash-hero .btn-hero:hover{ background:#f0fdf4; color:var(--dash-primary-dark); }

    /* ---------- Section title ---------- */
    .section-title{
        display:flex; align-items:center; gap:.65rem;
        font-weight:800; font-size:1.05rem; letter-spacing:-.01em;
        color:var(--dash-text); margin-bottom:1.1rem;
    }
    .section-title .icon-badge{
        width:36px; height:36px; border-radius:10px;
        display:flex; align-items:center; justify-content:center;
        background:var(--dash-primary-soft); color:var(--dash-primary); font-size:.95rem;
    }
    .section-head-row{ display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom:1.1rem; }
    .section-head-row .section-title{ margin-bottom:0; }

    /* ---------- Quick action ---------- */
    .quick-action-row{ display:flex; flex-wrap:wrap; gap:.7rem; }
    .quick-action{
        display:inline-flex; align-items:center; gap:.55rem;
        background:#fff; border:1px solid var(--dash-border);
        color:var(--dash-text); font-weight:600; font-size:.9rem;
        padding:.7rem 1.1rem; border-radius:999px;
        box-shadow:var(--dash-shadow);
        text-decoration:none; transition:transform .15s ease, box-shadow .15s ease, color .15s ease;
    }
    .quick-action i{ color:var(--dash-primary); }
    .quick-action:hover{ transform:translateY(-2px); box-shadow:var(--dash-shadow-hover); color:var(--dash-text); }

    /* ---------- Status / stat card ---------- */
    .status-card{
        background:#fff; border:1px solid var(--dash-border); border-radius:var(--dash-radius);
        box-shadow:var(--dash-shadow); padding:1.5rem; height:100%;
        display:flex; align-items:center; gap:1rem;
        transition:transform .18s ease, box-shadow .18s ease;
    }
    .status-card:hover{ transform:translateY(-3px); box-shadow:var(--dash-shadow-hover); }
    .status-card .status-icon{
        width:46px; height:46px; border-radius:12px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center; font-size:1.05rem;
    }
    .status-card .status-label{ font-size:.9rem; color:var(--dash-muted); font-weight:600; margin-bottom:.1rem; }
    .status-card .status-value{ font-size:1.6rem; font-weight:800; color:var(--dash-text); line-height:1.1; }

    /* ---------- Progress step ---------- */
    .progress-stepper{ display:flex; align-items:flex-start; }
    .progress-step{ flex:1; text-align:center; position:relative; }
    .progress-step .step-dot{
        width:36px; height:36px; border-radius:50%; margin:0 auto .55rem auto;
        display:flex; align-items:center; justify-content:center;
        background:#eef2f0; color:#94a3b8; font-size:.85rem; font-weight:700;
        border:2px solid #e2e8f0; transition:all .2s ease;
    }
    .progress-step .step-label{ font-size:.82rem; font-weight:600; color:#94a3b8; }
    .progress-step .step-bar{
        position:absolute; top:18px; left:-50%; width:100%; height:3px;
        background:#e2e8f0; z-index:0;
    }
    .progress-step:first-child .step-bar{ display:none; }
    .progress-step.is-done .step-dot{ background:var(--dash-primary); border-color:var(--dash-primary); color:#fff; }
    .progress-step.is-done .step-label{ color:var(--dash-primary); }
    .progress-step.is-done .step-bar{ background:var(--dash-primary); }
    .progress-step.is-current .step-dot{ background:#fff; border-color:var(--dash-primary); color:var(--dash-primary); box-shadow:0 0 0 4px var(--dash-primary-soft); }
    .progress-step.is-current .step-label{ color:var(--dash-primary); }
    .progress-step.is-rejected .step-dot{ background:#dc3545; border-color:#dc3545; color:#fff; }
    .progress-step.is-rejected .step-label{ color:#dc3545; }

    /* ---------- Gunung populer ---------- */
    .gunung-list{ display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.1rem; }
    .gunung-card{
        background:#fff; border:1px solid var(--dash-border);
        border-radius:var(--dash-radius); overflow:hidden;
        box-shadow:var(--dash-shadow);
        transition:transform .18s ease, box-shadow .18s ease;
    }
    .gunung-card:hover{ transform:translateY(-4px); box-shadow:var(--dash-shadow-hover); }
    .gunung-card .thumb{ width:100%; height:130px; object-fit:cover; display:block; }
    .gunung-card-body{ padding:1.1rem; }
    .gunung-card-body h5{ font-size:1rem; font-weight:700; margin-bottom:.3rem; color:var(--dash-text); }
    .gunung-card-body p{ font-size:.85rem; color:var(--dash-muted); margin-bottom:.3rem; }
    .gunung-card-body small{ color:var(--dash-primary); font-weight:600; }

    /* ---------- History table ---------- */
    .history-table-wrap{ background:#fff; border:1px solid var(--dash-border); border-radius:var(--dash-radius); box-shadow:var(--dash-shadow); overflow-x:auto; }
    .history-table{ width:100%; border-collapse:separate; border-spacing:0; margin-bottom:0; }
    .history-table thead th{
        font-size:.76rem; text-transform:uppercase; letter-spacing:.05em;
        color:var(--dash-muted); font-weight:700; padding:1rem 1.25rem;
        border-bottom:1px solid var(--dash-border); text-align:left; white-space:nowrap;
    }
    .history-table tbody td{
        padding:1rem 1.25rem; font-size:.92rem; border-bottom:1px solid rgba(15,23,42,.05);
        vertical-align:middle;
    }
    .history-table tbody tr:last-child td{ border-bottom:0; }
    .history-table tbody tr:hover{ background:var(--dash-primary-soft); }
    .history-table .btn-row-action{
        display:inline-flex; align-items:center; gap:.4rem;
        background:#fff; border:1px solid var(--dash-border); color:var(--dash-text);
        font-weight:600; font-size:.82rem; padding:.4rem .85rem; border-radius:999px;
        text-decoration:none; transition:all .15s ease;
    }
    .history-table .btn-row-action:hover{ border-color:var(--dash-primary); color:var(--dash-primary); }

    /* ---------- Timeline ---------- */
    .timeline{ position:relative; padding-left:2.1rem; }
    .timeline::before{
        content:""; position:absolute; left:12px; top:6px; bottom:6px; width:2px;
        background:rgba(15,23,42,.08);
    }
    .timeline-item{ position:relative; padding-bottom:1.5rem; }
    .timeline-item:last-child{ padding-bottom:0; }
    .timeline-item .timeline-marker{
        position:absolute; left:-2.1rem; top:2px; width:26px; height:26px; border-radius:50%;
        display:flex; align-items:center; justify-content:center; font-size:.75rem;
        background:#fff; border:2px solid var(--dash-primary); color:var(--dash-primary);
    }
    .timeline-item.is-rejected .timeline-marker{ border-color:#dc3545; color:#dc3545; }
    .timeline-item.is-approved .timeline-marker{ background:var(--dash-primary); color:#fff; }
    .timeline-item .timeline-title{ font-weight:700; font-size:.92rem; color:var(--dash-text); }
    .timeline-item .timeline-meta{ font-size:.8rem; color:var(--dash-muted); }

    /* ---------- Card wrapper generic (progress / timeline container) ---------- */
    .dash-card{
        background:#fff; border:1px solid var(--dash-border); border-radius:var(--dash-radius);
        box-shadow:var(--dash-shadow); padding:1.75rem;
    }

    /* ---------- Footer ---------- */
    .footer-dashboard{ text-align:center; color:#94a3b8; font-size:.85rem; padding-top:.5rem; }

    @media (max-width: 576px){
        .dash-hero{ padding:1.5rem; }
        .progress-stepper{ flex-wrap:wrap; }
        .progress-step .step-label{ font-size:.72rem; }
        .history-table thead th, .history-table tbody td{ padding:.75rem .9rem; }
    }
</style>
@endpush

@section('content')

<div class="dash-wrap">

  {{-- ===================== HERO + GREETING ===================== --}}
  <div class="dash-hero" data-reveal="fade-up">
    <div class="hero-sheen" aria-hidden="true"></div>
    <div class="hero-sparks" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i></div>
    <div class="dash-hero__ridge" aria-hidden="true">
      <svg viewBox="0 0 1440 80" preserveAspectRatio="none"><path fill="rgba(255,255,255,.07)" d="M0 80 L0 50 L140 22 L250 44 L380 8 L500 40 L640 12 L760 46 L900 18 L1040 48 L1180 14 L1320 40 L1440 24 L1440 80Z"/></svg>
    </div>
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 position-relative">
      <div>
        <div class="greeting">Selamat datang, {{ Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'Pendaki' }} 👋</div>
        <p class="subtitle">Kelola pendaftaran, pantau status, dan jelajahi gunung populer.</p>
      </div>
      <a href="{{ route('pendaftaran.create') }}" class="btn btn-hero fw-bold btn-shine">
        <i class="fa fa-file-pen me-2"></i> Isi Formulir SIMAKSI
      </a>
    </div>
  </div>

  {{-- ===================== QUICK ACTION ===================== --}}
  <div>
    <div class="quick-action-row" data-stagger="fade-left">
      <a href="{{ route('pendaftaran.create') }}" class="quick-action"><i class="fa fa-file-pen"></i> Formulir Baru</a>
      <a href="{{ route('pendaftaran.index') }}" class="quick-action"><i class="fa fa-clock-rotate-left"></i> Riwayat Pendaftaran</a>
      <a href="{{ route('gunung.index') }}" class="quick-action"><i class="fa fa-mountain"></i> Jelajahi Gunung</a>
      <a href="{{ route('panduan') }}" class="quick-action"><i class="fa fa-book"></i> Panduan Pendakian</a>
    </div>
  </div>

  {{-- ===================== STATISTIK ===================== --}}
  <div class="row g-3" data-stagger="scale">
    <div class="col-12 col-md-6 col-lg-3">
      <div class="status-card">
        <div class="status-icon" style="background:#e8f0fe;color:#1e5ddf;"><i class="fa fa-clipboard-list"></i></div>
        <div>
          <div class="status-label">Total Pendaftaran</div>
          <div class="status-value" data-count="{{ (int) ($totalPendaftaran ?? 0) }}">{{ $totalPendaftaran ?? 0 }}</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="status-card">
        <div class="status-icon" style="background:#fff3cd;color:#996c00;"><i class="fa fa-clock"></i></div>
        <div>
          <div class="status-label">Menunggu Persetujuan</div>
          <div class="status-value" data-count="{{ (int) ($menunggu ?? 0) }}">{{ $menunggu ?? 0 }}</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="status-card">
        <div class="status-icon" style="background:var(--dash-primary-soft);color:var(--dash-primary);"><i class="fa fa-check"></i></div>
        <div>
          <div class="status-label">Disetujui</div>
          <div class="status-value" data-count="{{ (int) ($disetujui ?? 0) }}">{{ $disetujui ?? 0 }}</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="status-card">
        <div class="status-icon" style="background:#fde2e1;color:#c0262d;"><i class="fa fa-wallet"></i></div>
        <div>
          <div class="status-label">Belum Bayar</div>
          <div class="status-value" data-count="{{ (int) ($belumBayar ?? 0) }}">{{ $belumBayar ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===================== PROGRESS STATUS ===================== --}}
  @php
    $pendaftaranAktif = $pendaftaranSaya->firstWhere('status', 'pending') ?? $pendaftaranSaya->first();
  @endphp
  @if($pendaftaranAktif)
    <div>
      <div class="section-title">
        <span class="icon-badge"><i class="fa fa-route"></i></span>
        Progress Status Pendaftaran Terbaru
      </div>
      <div class="dash-card" data-reveal="fade-up">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
          <div>
            <div class="fw-bold" style="font-size:1.05rem;">{{ $pendaftaranAktif->rutePendakian->gunung->nama_gunung ?? 'Gunung' }}</div>
            <small class="text-muted">Tanggal pendakian: {{ \Carbon\Carbon::parse($pendaftaranAktif->tanggal_pendakian)->format('d M Y') }}</small>
          </div>
          <a href="{{ route('pendaftaran.edit', $pendaftaranAktif->id_pendaftaran) }}" class="quick-action"><i class="fa fa-eye"></i> Lihat Detail</a>
        </div>

        @php
          $status = $pendaftaranAktif->status;
          $bayar  = $pendaftaranAktif->status_pembayaran;
          $isRejected = $status === 'ditolak';
        @endphp

        <div class="progress-stepper" data-stagger="scale">
          <div class="progress-step is-done">
            <div class="step-bar"></div>
            <div class="step-dot"><i class="fa fa-check"></i></div>
            <div class="step-label">Diajukan</div>
          </div>

          <div class="progress-step {{ $isRejected ? 'is-rejected' : ($status === 'disetujui' ? 'is-done' : 'is-current') }}">
            <div class="step-bar"></div>
            <div class="step-dot">{{ $isRejected ? '✕' : '2' }}</div>
            <div class="step-label">{{ $isRejected ? 'Ditolak' : 'Disetujui' }}</div>
          </div>

          <div class="progress-step {{ $status === 'disetujui' ? ($bayar === 'lunas' ? 'is-done' : 'is-current') : '' }}">
            <div class="step-bar"></div>
            <div class="step-dot">{{ $bayar === 'lunas' ? '✓' : '3' }}</div>
            <div class="step-label">Pembayaran</div>
          </div>

          <div class="progress-step {{ $status === 'disetujui' && $bayar === 'lunas' ? 'is-done' : '' }}">
            <div class="step-bar"></div>
            <div class="step-dot">{{ $status === 'disetujui' && $bayar === 'lunas' ? '✓' : '4' }}</div>
            <div class="step-label">Siap Mendaki</div>
          </div>
        </div>
      </div>
    </div>
  @endif

  {{-- ===================== STATUS PEMINJAMAN ALAT ===================== --}}
  @if($pendaftaranSaya->whereNotNull('peminjaman_id')->count() > 0)
    <div>
      <div class="section-title">
        <span class="icon-badge"><i class="fa fa-toolbox"></i></span>
        Status Peminjaman Alat
      </div>
      <div class="row g-3" data-stagger>
        @foreach($pendaftaranSaya->whereNotNull('peminjaman_id') as $pendaftaran)
          <div class="col-12 col-md-6 col-lg-4">
            <div class="dash-card text-center h-100">
              <div class="mb-3">
                @php $sp = $pendaftaran->status_peminjaman; @endphp
                @if($sp == 'disetujui')
                  <x-ui.status-badge status="disetujui" label="Disetujui" />
                @elseif($sp == 'menunggu')
                  <x-ui.status-badge status="menunggu" label="Menunggu" />
                @elseif($sp == 'ditolak')
                  <x-ui.status-badge status="ditolak" label="Ditolak" />
                @else
                  <x-ui.status-badge status="unknown" label="Unknown" />
                @endif
              </div>
              <div class="fw-bold" style="font-size:1.05rem;">{{ $pendaftaran->rutePendakian->gunung->nama_gunung ?? 'Unknown' }}</div>
              <small class="text-muted">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pendakian)->format('Y-m-d') }}</small>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  {{-- ===================== GUNUNG POPULER ===================== --}}
  @php
    use Illuminate\Support\Str;
    if (! function_exists('asset_gunung_img')) {
        function asset_gunung_img($name) {
            $slug = Str::slug($name);
            $base = public_path('images/gunung');
            $map = [
                'rinjani' => 'GunungRinjani.jpg',
                'semeru' => 'Gunung_semeru.jpg',
                'merapi'  => 'GunungMerapi.jpg',
                'kerinci' => 'Gunung_Kerinci.png',
                'lawu'    => 'GunungLawu.jpg',
                'merbabu' => 'GunungMerbabu.jpg',
                'sindoro' => 'GunungSindoro.jpg',
                'slamet'  => 'GunungSlamet.jpg',
                'prau'    => 'GunungPrau.jpg',
                'arjuno'  => 'arjuno.jpg',
                'bromo'   => 'GunungBromo.jpg',
                'ceremai' => 'GunungCeremai.jpg',
                'gede'    => 'gunung gede.jpg',
                'agung'   => 'GunungAgung.jpg',
                'sindoro-sumbing' => 'Sindoro_Sumbing.jpg',
            ];
            if (isset($map[$slug])) {
                return asset('images/gunung/'.$map[$slug]);
            }
            $candidates = [
                $slug.'.jpg', $slug.'.jpeg', $slug.'.png',
                'Gunung'.Str::studly($slug).'.jpg', ucfirst($slug).'.jpg'
            ];
            foreach ($candidates as $f) {
                if (file_exists($base.DIRECTORY_SEPARATOR.$f)) return asset('images/gunung/'.$f);
            }
            return asset('images/background.jpg');
        }
    }
  @endphp
  <div>
    <div class="section-title">
      <span class="icon-badge"><i class="fa fa-fire"></i></span>
      Gunung Populer Minggu Ini
    </div>
    <div class="gunung-list" data-stagger>
      @forelse($gunungPopuler as $gunung)
        <div class="gunung-card">
          {{-- Pakai kolom gambar dari database bila ada; helper lama sebagai cadangan --}}
          <div class="skeleton" style="overflow:hidden;">
            <img class="thumb" src="{{ !empty($gunung->gambar) ? asset('images/gunung/' . $gunung->gambar) : asset_gunung_img($gunung->nama_gunung) }}" alt="{{ $gunung->nama_gunung }}" loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-mountain.svg') }}';">
          </div>
          <div class="gunung-card-body">
            <h5>{{ $gunung->nama_gunung }}</h5>
            <p>Ketinggian: {{ number_format($gunung->ketinggian, 0, ',', '.') }} mdpl</p>
            <small>{{ $gunung->pendaki_minggu_ini ?? 0 }} pendaki minggu ini</small>
          </div>
        </div>
      @empty
        <p class="text-muted">Belum ada data gunung populer minggu ini.</p>
      @endforelse
    </div>
  </div>

  {{-- ===================== RIWAYAT PENDAFTARAN ===================== --}}
  <div>
    <div class="section-head-row">
      <div class="section-title">
        <span class="icon-badge"><i class="fa fa-clock-rotate-left"></i></span>
        Riwayat Pendaftaran
      </div>
      <a href="{{ route('pendaftaran.index') }}" class="quick-action"><i class="fa fa-list"></i> Lihat Semua</a>
    </div>

    <div class="history-table-wrap" data-reveal="fade-up">
      <table class="history-table">
        <thead>
          <tr>
            <th>Gunung</th>
            <th>Tanggal Pendakian</th>
            <th>Status</th>
            <th>Pembayaran</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendaftaranSaya->take(8) as $p)
            <tr>
              <td class="fw-semibold">{{ $p->rutePendakian->gunung->nama_gunung ?? '-' }}</td>
              <td>{{ \Carbon\Carbon::parse($p->tanggal_pendakian)->format('d M Y') }}</td>
              <td>
                @if($p->status == 'disetujui')
                  <x-ui.status-badge status="disetujui" label="Disetujui" />
                @elseif($p->status == 'ditolak')
                  <x-ui.status-badge status="ditolak" label="Ditolak" />
                @else
                  <x-ui.status-badge status="menunggu" label="Menunggu" />
                @endif
              </td>
              <td>
                @if($p->status_pembayaran == 'lunas')
                  <x-ui.status-badge status="lunas" label="Lunas" />
                @else
                  <x-ui.status-badge status="belum" label="Belum" />
                @endif
              </td>
              <td>
                @if($p->status == 'disetujui')
                  <a href="{{ route('pendaftaran.cetak', $p->id_pendaftaran) }}" class="btn-row-action"><i class="fa fa-download"></i> Cetak</a>
                @else
                  <a href="{{ route('pendaftaran.edit', $p->id_pendaftaran) }}" class="btn-row-action"><i class="fa fa-eye"></i> Detail</a>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada riwayat pendaftaran.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- ===================== ACTIVITY TIMELINE ===================== --}}
  <div>
    <div class="section-title">
      <span class="icon-badge"><i class="fa fa-timeline"></i></span>
      Aktivitas Terbaru
    </div>
    <div class="dash-card" data-reveal="fade-up">
      <div class="timeline" data-stagger="fade-left">
        @forelse($pendaftaranSaya->take(6) as $p)
          <div class="timeline-item {{ $p->status == 'disetujui' ? 'is-approved' : ($p->status == 'ditolak' ? 'is-rejected' : '') }}">
            <div class="timeline-marker">
              <i class="fa {{ $p->status == 'disetujui' ? 'fa-check' : ($p->status == 'ditolak' ? 'fa-xmark' : 'fa-clock') }}"></i>
            </div>
            <div class="timeline-title">
              Pendaftaran ke {{ $p->rutePendakian->gunung->nama_gunung ?? 'gunung' }}
              @if($p->status == 'disetujui') disetujui
              @elseif($p->status == 'ditolak') ditolak
              @else menunggu persetujuan
              @endif
            </div>
            <div class="timeline-meta">{{ optional($p->created_at)->diffForHumans() ?? '-' }}</div>
          </div>
        @empty
          <p class="text-muted mb-0">Belum ada aktivitas.</p>
        @endforelse
      </div>
    </div>
  </div>

  <div class="footer-dashboard">&copy; {{ date('Y') }} SIMAKSI.COM · Jelajahi Alam Indonesia dengan Aman</div>

</div>
@endsection
