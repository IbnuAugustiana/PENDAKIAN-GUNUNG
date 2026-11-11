@extends('layouts.main')

@section('title', 'Pembayaran SIMAKSI')

@section('content')

<div class="app-content">

    {{-- ===================== Page Heading + Breadcrumb ===================== --}}
    <div class="page-heading">
        <div class="breadcrumb-simaksi">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="mx-2">/</span>
            <span>SIMAKSI</span>
            <span class="mx-2">/</span>
            <span class="fw-semibold">Pembayaran</span>
        </div>
        <h1 class="fw-bold">💳 Pembayaran SIMAKSI</h1>
        <p class="mb-0">Invoice modern untuk menyelesaikan pembayaran pendaftaran Anda.</p>
    </div>

    <div class="row g-3 align-items-start">

        <div class="col-12">
            <div class="card-simaksi overflow-hidden">
                <div class="card-header">
                    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary bg-opacity-10 text-success border border-success border-opacity-25">
                                <i class="fa fa-receipt me-1"></i>Invoice
                            </span>
                            <div class="fw-bold">#{{ $pendaftaran->id_pendaftaran }}</div>
                        </div>
                        <div>
                            <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- ===================== Invoice Header ===================== --}}
                    <div class="row g-3">
                        <div class="col-12 col-lg-7">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div>
                                        <div class="text-muted small">Nama Gunung</div>
                                        <div class="fw-bold fs-5">{{ $pendaftaran->rutePendakian->gunung->nama_gunung ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small">Tanggal</div>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pendakian)->format('d M Y') }}</div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div>
                                        <div class="text-muted small">Rute</div>
                                        <div class="fw-semibold">{{ $pendaftaran->rutePendakian->nama_rute ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small">Status Pembayaran</div>
                                        <div>
                                            @if(($pendaftaran->status_pembayaran ?? null) === 'lunas' || ($pendaftaran->status_pembayaran ?? null) === 'paid')
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                    <i class="fa fa-check me-1"></i>Lunas
                                                </span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">
                                                    <i class="fa fa-hourglass-half me-1"></i>Belum Lunas
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Midtrans / VA info (tampilan saja) --}}
                                <div class="alert alert-info border-0">
                                    <div class="fw-bold mb-1"><i class="fa fa-lock me-2"></i>Metode Pembayaran</div>
                                    <div class="small text-muted mb-1">VA / Midtrans akan diproses oleh sistem.</div>
                                    <div class="fw-semibold">Token SNAP: <span class="text-muted">{{ $snapToken ? 'tersedia' : '-' }}</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5">
                            <div class="card" style="border:1px solid var(--simaksi-border); border-radius:16px;">
                                <div class="card-body">
                                    <div class="text-muted small mb-1">Total Pembayaran</div>
                                    <div class="fw-bold" style="font-size:1.9rem; color:var(--simaksi-primary);">
                                        Rp{{ number_format($pendaftaran->total_biaya ?? ($pendaftaran->rutePendakian->harga ?? 0), 0, ',', '.') }}
                                    </div>

                                    <div class="d-grid gap-2 mt-4">
                                        <button id="pay-button" class="btn btn-simaksi-primary text-white fw-bold">
                                            <i class="fa fa-credit-card me-2"></i>Bayar
                                        </button>
                                        <button type="button" class="btn btn-outline-primary fw-bold" onclick="window.location='{{ route('pendaftaran.index') }}'">
                                            <i class="fa fa-arrow-left me-2"></i>Kembali
                                        </button>
                                    </div>

                                    <div class="text-center mt-3 small text-muted">
                                        <i class="fa fa-shield-halved me-1" style="color:var(--simaksi-primary);"></i>
                                        Pembayaran aman dan terenkripsi
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== Hidden values / placeholders for logic ===================== --}}
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ===== Keep Midtrans Snap logic identical ===== --}}
{{-- Midtrans Snap already loaded in layout? If not, we include script. --}}
<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script type="text/javascript">
  const payButton = document.getElementById('pay-button');
  payButton.addEventListener('click', function () {
    window.snap.pay('{{ $snapToken }}', {
      onSuccess: function(result){
        Swal.fire({
          icon: 'success',
          title: 'Pembayaran Berhasil',
          text: 'Silakan tunggu persetujuan admin untuk mencetak formulir SIMAKSI.',
          confirmButtonColor: '#28a745'
        }).then(() => { window.location.href = '/pendaftaran'; });
      },
      onPending: function(result){
        Swal.fire({
          icon: 'info',
          title: 'Menunggu Pembayaran',
          text: 'Transaksi kamu masih menunggu penyelesaian.',
        });
      },
      onError: function(result){
        Swal.fire({
          icon: 'error',
          title: 'Pembayaran Gagal',
          text: 'Terjadi kesalahan saat memproses pembayaran. Coba lagi ya.'
        });
      },
      onClose: function(){
        Swal.fire({
          icon: 'warning',
          title: 'Pembayaran Belum Selesai',
          text: 'Kamu menutup popup sebelum pembayaran selesai.'
        });
      }
    });
  });
</script>

@endsection

