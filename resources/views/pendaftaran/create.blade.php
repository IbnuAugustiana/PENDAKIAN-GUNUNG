@extends('layouts.main')

@section('title', 'Tambah Pendaftaran SIMAKSI')

@section('content')

<div class="app-content">
    {{-- ===================== Page Heading + Breadcrumb ===================== --}}
    <div class="page-heading">
        <div class="breadcrumb-simaksi">
            <a href="{{ route('dashboard') }}">Home</a>
            <span class="mx-2">/</span>
            <span>SIMAKSI</span>
            <span class="mx-2">/</span>
            <span class="fw-semibold">Tambah Pendaftaran</span>
        </div>
        <h1 class="fw-bold">📝 Tambah Pendaftaran</h1>
        <p class="mb-0">Lengkapi data pendakian, anggota, dan persetujuan dalam satu alur.</p>
    </div>

    <div class="card card-simaksi" style="padding:0;">
        <form id="formPendaftaran" action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- Preserve existing JS structure: all ids & name fields stay untouched. --}}

            <div class="p-4 p-md-5">

                {{-- ===================== Section card 1: Informasi Pendaki ===================== --}}
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center gap-2 fw-bold">
                            <i class="fa fa-user-tie" style="color:var(--simaksi-primary);"></i>
                            Informasi Pendaki
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Jika user datang dari tombol "Daftar SIMAKSI" (rute spesifik) --}}
                        <div class="mb-3">
                            <label for="gunung_id">Gunung Tujuan</label>
                            @if(isset($gunung) && isset($rute))
                                <input type="text" class="form-control" value="{{ $gunung->nama_gunung }}" readonly>
                                <input type="hidden" name="gunung_id" value="{{ $gunung->id_gunung }}">
                            @else
                                <select name="gunung_id" id="gunung_id" class="form-select" required>
                                    <option value="">-- Pilih Gunung --</option>
                                    @foreach($gunungs as $g)
                                        <option value="{{ $g->id_gunung }}">{{ $g->nama_gunung }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="rute_pendakian_id">Rute Pendakian</label>
                            @if(isset($gunung) && isset($rute))
                                <input type="text" class="form-control" value="{{ $rute->nama_rute }}" readonly>
                                <input type="hidden" name="rute_pendakian_id" value="{{ $rute->id_rute }}">
                                <input type="hidden" id="harga_raw" value="{{ $rute->harga }}">
                            @else
                                <select name="rute_pendakian_id" id="rute_pendakian_id" class="form-select" required>
                                    <option value="">-- Pilih Rute --</option>
                                </select>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pendakian" class="form-label">Tanggal Naik</label>
                                <input type="date" name="tanggal_pendakian" id="tanggal_pendakian" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_turun" class="form-label">Tanggal Turun</label>
                                <input type="date" name="tanggal_turun" id="tanggal_turun" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Foto Identitas (KTP/SIM/KK)</label>
                            <input type="file" name="foto_identitas" class="form-control" accept=".jpg,.jpeg,.png" data-validate="file" data-allowed-ext="jpg,jpeg,png" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Identitas</label>
                                <select name="jenis_identitas" id="jenis_identitas" class="form-select" data-identity-type-source='[name="no_identitas"]' required>
                                    <option value="">-- Pilih Jenis Identitas --</option>
                                    <option value="KTP">KTP</option>
                                    <option value="SIM">SIM</option>
                                    <option value="KK">KK</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Identitas</label>
                                <input type="text" name="no_identitas" class="form-control" placeholder="Masukkan Nomor Identitas" data-validate="identity" data-identity-type-target="#jenis_identitas" maxlength="20" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin Pendaki Utama</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Usia Pendaki Utama</label>
                                <input type="number" name="usia" class="form-control" min="1" max="100" data-validate="age" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== Section card 2: Data Pendakian ===================== --}}
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center gap-2 fw-bold">
                            <i class="fa fa-route" style="color:var(--simaksi-primary);"></i>
                            Data Pendakian
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jumlah Pendaki</label>
                                <input type="number" name="jumlah_pendaki" id="jumlah_pendaki" class="form-control" min="1" max="100" value="1" data-validate="quantity" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga per Pendaki (Rp)</label>
                                <input type="text" id="harga" readonly class="form-control"
                                    @if(isset($rute)) value="{{ number_format($rute->harga, 0, ',', '.') }}" @endif>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total Biaya (Rp)</label>
                                <input type="text" id="total_biaya" readonly class="form-control"
                                    @if(isset($rute)) value="{{ number_format($rute->harga, 0, ',', '.') }}" @endif>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 mt-3">
                            <i class="fa fa-info-circle me-2"></i>
                            Total akan dihitung otomatis berdasarkan jumlah pendaki dan rute.
                        </div>
                    </div>
                </div>

                {{-- ===================== Section card 3: Anggota Pendakian ===================== --}}
                <div class="card mb-4" id="anggotaContainer" style="display:none;">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center gap-2 fw-bold">
                            <i class="fa fa-users" style="color:var(--simaksi-primary);"></i>
                            Anggota Pendakian
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="anggotaList"></div>
                    </div>
                </div>

                {{-- ===================== Section card 4: Peminjaman Alat ===================== --}}
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center gap-2 fw-bold">
                            <i class="fa fa-toolbox" style="color:var(--simaksi-primary);"></i>
                            Peminjaman Alat
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="alatContainer" class="mb-3">
                            <p class="text-muted mb-0">Memuat daftar alat...</p>
                        </div>

                        <div class="alert alert-info border-0">
                            <strong>Total Biaya Peminjaman Alat: Rp<span id="totalBiayaAlat">0</span></strong>
                        </div>
                    </div>
                </div>

                {{-- ===================== Section card 5: Ringkasan Biaya ===================== --}}
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center gap-2 fw-bold">
                            <i class="fa fa-receipt" style="color:var(--simaksi-primary);"></i>
                            Ringkasan Biaya
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-12 col-md-8">
                                <div class="small text-muted">(Biaya SIMAKSI + Biaya Peminjaman Alat)</div>
                            </div>
                            <div class="col-12 col-md-4 text-md-end">
                                <div class="fw-bold" style="font-size:1.15rem; color:var(--simaksi-primary);">
                                    💳 Total Pembayaran: Rp<span id="totalBiayaKeseluruhan">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== Section card 6: Persetujuan ===================== --}}
                <div class="card mb-4">
                    <div class="card-header bg-transparent">
                        <div class="d-flex align-items-center gap-2 fw-bold">
                            <i class="fa fa-circle-check" style="color:var(--simaksi-primary);"></i>
                            Persetujuan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning border-0">
                            <i class="fa fa-triangle-exclamation me-2"></i>
                            Pastikan semua data anggota benar sebelum menyimpan & bayar.
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="agreeDummy" required>
                            <label class="form-check-label" for="agreeDummy">Saya menyetujui pengisian data ini.</label>
                        </div>
                    </div>
                </div>

                {{-- ===================== Final Submit ===================== --}}
                <div class="d-flex flex-column flex-md-row gap-2 justify-content-between align-items-stretch">
                    <a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-primary fw-bold">Kembali</a>
                    <button type="submit" class="btn btn-simaksi-primary text-white fw-bold">
                        <i class="fa fa-file-signature me-2"></i>Simpan & Bayar Sekarang
                    </button>
                </div>
            </div>
        </form>

    </div>

</div>

{{-- ===================== Midtrans Script tetap utuh ===================== --}}
<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

{{-- ===================== JS business logic tetap ada & DOM ids tetap sama ===================== --}}
<script>
$(document).ready(function(){
    // no-op placeholder to preserve existing build if jQuery exists.
});
</script>

{{-- NOTE: Business logic script dipertahankan dari file lama. Pastikan id/name tetap identik. --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const gunungSelect = document.getElementById('gunung_id');
    const ruteSelect = document.getElementById('rute_pendakian_id');
    const hargaInput = document.getElementById('harga');
    const totalInput = document.getElementById('total_biaya');
    const jumlahInput = document.getElementById('jumlah_pendaki');
    const tglNaik = document.getElementById('tanggal_pendakian');
    const tglTurun = document.getElementById('tanggal_turun');
    const anggotaContainer = document.getElementById('anggotaContainer');
    const anggotaList = document.getElementById('anggotaList');
    const alatContainer = document.getElementById('alatContainer');
    const form = document.getElementById('formPendaftaran');
    const hargaRawInput = document.getElementById('harga_raw');
    const submitButton = form ? form.querySelector('button[type="submit"]') : null;
    const submitButtonDefaultHtml = submitButton ? submitButton.innerHTML : '';
    let isSubmittingPendaftaran = false;

    // ==========================================================
    // 🔹 FETCH DAFTAR ALAT PEMINJAMAN
    // ==========================================================
    function loadAlatPeminjaman() {
        fetch(@json(rtrim((string) config('services.peminjaman_api.url'), '/') . '/alats'))
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat daftar alat');
                return res.json();
            })
            .then(data => {
                if (data && data.length > 0) {
                    renderAlatForm(data);
                } else {
                    alatContainer.innerHTML = '<p class="text-muted">Tidak ada alat tersedia untuk dipinjam.</p>';
                }
            })
            .catch(err => {
                console.error('Error loading alat:', err);
                alatContainer.innerHTML = '<p class="text-danger">Gagal memuat daftar alat. Sistem peminjaman mungkin sedang offline.</p>';
            });
    }

    // ==========================================================
    // 🔹 RENDER FORM ALAT PEMINJAMAN
    // ==========================================================
    function renderAlatForm(alats) {
        // Remove duplicates based on id or name
        const uniqueAlats = alats.filter((alat, index, self) =>
            index === self.findIndex(a => a.id === alat.id || (a.nama || a.nama_alat) === (alat.nama || alat.nama_alat))
        );

        let html = '<div class="row">';

        uniqueAlats.forEach(alat => {
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card" style="border: 1px solid #e0e0e0; border-radius: 8px;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="card-title mb-0">${alat.nama || alat.nama_alat || 'Alat pendakian'}</h6>
                                <span class="badge bg-success">Rp${new Intl.NumberFormat('id-ID').format(alat.harga || alat.harga_sewa || 0)}</span>
                            </div>
                            <p class="card-text small text-muted mb-2">${alat.deskripsi || 'Alat pendakian'}</p>
                            <div class="d-flex align-items-center gap-2">
                                <label class="form-label mb-0 small">Jumlah:</label>
                                <input type="number" name="alat_peminjaman[${alat.id}]" class="form-control form-control-sm alat-input"
                                       min="0" max="${alat.stok}" value="0" style="width: 80px;" data-harga="${alat.harga || alat.harga_sewa || 0}">
                                <small class="text-muted">Stok: ${alat.stok}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        html += '<div class="alert alert-info small mt-3">';
        html += '<i class="fas fa-info-circle"></i> Pilih jumlah alat yang ingin dipinjam. Kosongkan jika tidak ingin meminjam.';
        html += '</div>';

        alatContainer.innerHTML = html;

        // Add event listeners to calculate total
        document.querySelectorAll('.alat-input').forEach(input => {
            input.addEventListener('input', updateTotalBiayaAlat);
        });
    }

    // Load alat saat halaman load
    loadAlatPeminjaman();

    // ==========================================================
    // 🔹 UPDATE TOTAL BIAYA ALAT PEMINJAMAN
    // ==========================================================
    function updateTotalBiayaAlat() {
        let total = 0;
        document.querySelectorAll('.alat-input').forEach(input => {
            const quantity = parseInt(input.value) || 0;
            const harga = parseInt(input.getAttribute('data-harga')) || 0;
            total += quantity * harga;
        });
        const totalElement = document.getElementById('totalBiayaAlat');
        if (totalElement) {
            totalElement.textContent = new Intl.NumberFormat('id-ID').format(total);
        }
        updateTotalKeseluruhan();
    }

    // ==========================================================
    // 🔹 UPDATE TOTAL BIAYA KESELURUHAN
    // ==========================================================
    function updateTotalKeseluruhan() {
        const biayaSimaksi = parseInt(totalInput.value.replace(/\D/g, '')) || 0;
        const biayaAlat = parseInt(document.getElementById('totalBiayaAlat').textContent.replace(/\D/g, '')) || 0;
        const totalKeseluruhan = biayaSimaksi + biayaAlat;

        const totalElement = document.getElementById('totalBiayaKeseluruhan');
        if (totalElement) {
            totalElement.textContent = new Intl.NumberFormat('id-ID').format(totalKeseluruhan);
        }
    }

    function setSubmitButtonState(isLoading) {
        if (!submitButton) {
            return;
        }

        submitButton.disabled = isLoading;
        submitButton.innerHTML = isLoading
            ? '<i class="fa fa-spinner fa-spin me-2"></i>Menyimpan...'
            : submitButtonDefaultHtml;
    }

    function launchSnapPayment(snapToken, modalInstance = null) {
        if (!window.snap || typeof window.snap.pay !== 'function') {
            alert("Layanan pembayaran tidak tersedia saat ini.");
            return;
        }

        if (modalInstance && typeof modalInstance.hide === 'function') {
            modalInstance.hide();
        }

        window.snap.pay(snapToken, {
            onSuccess: function() {
                alert("Pembayaran berhasil. Silakan tunggu persetujuan admin untuk mencetak formulir SIMAKSI.");
                window.location.href = "/pendaftaran";
            },
            onPending: function() {
                alert("Pembayaran menunggu konfirmasi.");
                window.location.href = "/pendaftaran";
            },
            onError: function() {
                alert("Terjadi kesalahan saat pembayaran.");
            },
            onClose: function() {
                alert("Anda menutup popup sebelum pembayaran selesai.");
            }
        });
    }

    function submitPendaftaranViaAjax() {
        const formData = new FormData(form);
        const total = parseInt(totalInput.value.replace(/\D/g, '')) || 0;

        if (total <= 0) {
            alert("Harga pendakian belum tersedia. Silakan pilih rute atau periksa data.");
            setSubmitButtonState(false);
            isSubmittingPendaftaran = false;
            return;
        }

        fetch(form.action, {
            method: form.method || 'POST',
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name=\"_token\"]').value,
                "Accept": "application/json"
            }
        })
        .then(res => {
            if (!res.ok) {
                return res.text().then(text => {
                    let msg = text || res.statusText || 'Unknown error';
                    try {
                        const j = JSON.parse(text);
                        if (j.message) {
                            msg = j.message;
                        } else if (j.errors) {
                            msg = JSON.stringify(j.errors);
                        }
                    } catch (error) {
                        // Keep raw error text when response is not JSON.
                    }
                    throw new Error(res.status + ' - ' + msg);
                });
            }
            return res.json();
        })
        .then(data => {
            if (!(data.success && data.snap_token)) {
                alert("Gagal membuat transaksi: " + (data.message || "Terjadi kesalahan."));
                return;
            }

            let gunungText = "";
            let ruteText = "";

            if (isManualForm) {
                gunungText = gunungSelect?.selectedOptions[0]?.text || '';
                ruteText = ruteSelect?.selectedOptions[0]?.text || '';
            } else {
                const gunungInput = document.querySelector('input[name="gunung_id"]');
                const ruteInput = document.querySelector('input[name="rute_pendakian_id"]');

                if (gunungInput && gunungInput.previousElementSibling) {
                    gunungText = gunungInput.previousElementSibling.value;
                }
                if (ruteInput && ruteInput.previousElementSibling) {
                    ruteText = ruteInput.previousElementSibling.value;
                }
            }

            const biayaSimaksi = parseInt(totalInput.value.replace(/\D/g, '')) || 0;
            const biayaAlat = parseInt(document.getElementById('totalBiayaAlat').textContent.replace(/\D/g, '')) || 0;
            const totalKeseluruhan = biayaSimaksi + biayaAlat;

            document.getElementById('modalGunung').textContent = gunungText;
            document.getElementById('modalRute').textContent = ruteText;
            document.getElementById('modalTanggal').textContent = tglNaik.value;
            document.getElementById('modalBiayaSimaksi').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(biayaSimaksi);
            document.getElementById('modalTotal').textContent = new Intl.NumberFormat('id-ID').format(totalKeseluruhan);

            const modalBiayaAlatContainer = document.getElementById('modalBiayaAlatContainer');
            const modalAlatDetail = document.getElementById('modalAlatDetail');

            if (biayaAlat > 0) {
                modalBiayaAlatContainer.style.display = 'block';
                document.getElementById('modalBiayaAlat').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(biayaAlat);

                let alatDetailHtml = '<strong>Alat yang dipinjam:</strong><br>';
                let hasAlat = false;

                document.querySelectorAll('.alat-input').forEach(input => {
                    const quantity = parseInt(input.value) || 0;
                    if (quantity > 0) {
                        hasAlat = true;
                        const harga = parseInt(input.getAttribute('data-harga')) || 0;
                        const namaAlat = input.closest('.card-body').querySelector('.card-title').textContent;
                        const totalHarga = quantity * harga;

                        alatDetailHtml += `• ${namaAlat} (${quantity}x) - Rp${new Intl.NumberFormat('id-ID').format(totalHarga)}<br>`;
                    }
                });

                modalAlatDetail.innerHTML = hasAlat
                    ? alatDetailHtml
                    : '<em>Tidak ada alat yang dipinjam</em>';
            } else {
                modalBiayaAlatContainer.style.display = 'none';
                modalAlatDetail.innerHTML = '';
            }

            const btnBayarSekarang = document.getElementById('btnBayarSekarang');
            if (btnBayarSekarang) {
                btnBayarSekarang.onclick = null;
            }

            if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
                const modal = new bootstrap.Modal(document.getElementById('modalPembayaran'));
                modal.show();

                if (btnBayarSekarang) {
                    btnBayarSekarang.onclick = function() {
                        launchSnapPayment(data.snap_token, modal);
                    };
                }
            } else {
                launchSnapPayment(data.snap_token);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert("Terjadi kesalahan: " + err.message + "\n(Periksa console/network & storage/logs/laravel.log untuk detail)");
        })
        .finally(() => {
            setSubmitButtonState(false);
            isSubmittingPendaftaran = false;
        });
    }

    function setFieldError(field, message) {
        field.classList.add('is-invalid');
        field.setCustomValidity(message);
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        field.setCustomValidity('');
    }

    function validateDynamicIdentityInput(input) {
        if (!input) return true;
        const wrapper = input.closest('.anggota-item');
        const typeInput = wrapper ? wrapper.querySelector('.anggota-jenis-identitas') : document.getElementById('jenis_identitas');
        const identityType = (typeInput?.value || '').toUpperCase();
        let value = String(input.value || '').toUpperCase().replace(/\s+/g, '');

        if (identityType === 'KTP' || identityType === 'KK') {
            value = value.replace(/\D/g, '').slice(0, 16);
            input.value = value;
            if (!/^\d{16}$/.test(value)) {
                setFieldError(input, `Nomor identitas untuk ${identityType} wajib tepat 16 digit angka.`);
                return false;
            }
        } else if (identityType === 'SIM') {
            value = value.replace(/[^A-Z0-9]/g, '').slice(0, 20);
            input.value = value;
            if (!/^[A-Z0-9]{5,20}$/.test(value)) {
                setFieldError(input, 'Nomor SIM hanya boleh huruf/angka dengan panjang 5-20 karakter.');
                return false;
            }
        } else {
            value = value.replace(/[^A-Z0-9]/g, '').slice(0, 20);
            input.value = value;
            if (!value) {
                setFieldError(input, 'Nomor identitas wajib diisi.');
                return false;
            }
        }

        clearFieldError(input);
        return true;
    }

    function validatePendaftaranForm() {
        let invalidField = null;

        const identityInputs = form.querySelectorAll('input[name="no_identitas"], .anggota-no-identitas');
        identityInputs.forEach((input) => {
            if (!validateDynamicIdentityInput(input) && !invalidField) invalidField = input;
        });

        form.querySelectorAll('[data-validate="phone"]').forEach((input) => {
            input.value = String(input.value || '').replace(/\D/g, '').slice(0, 15);
            if (!/^\d{10,15}$/.test(input.value) && !invalidField) {
                setFieldError(input, 'Nomor HP harus 10-15 digit angka.');
                invalidField = input;
            } else if (/^\d{10,15}$/.test(input.value)) {
                clearFieldError(input);
            }
        });

        form.querySelectorAll('[data-validate="name"]').forEach((input) => {
            input.value = String(input.value || '').replace(/[^A-Za-z\s'.-]/g, '').replace(/\s+/g, ' ').trim();
            if ((input.value.length < 3 || /^\d+$/.test(input.value) || !/[A-Za-z]/.test(input.value)) && !invalidField) {
                setFieldError(input, 'Nama minimal 3 karakter dan harus mengandung huruf.');
                invalidField = input;
            } else if (input.value.length >= 3 && !/^\d+$/.test(input.value) && /[A-Za-z]/.test(input.value)) {
                clearFieldError(input);
            }
        });

        form.querySelectorAll('[data-validate="age"]').forEach((input) => {
            input.value = String(input.value || '').replace(/\D/g, '');
            const age = parseInt(input.value || '0', 10);
            if ((age < 1 || age > 100) && !invalidField) {
                setFieldError(input, 'Usia harus antara 1 sampai 100 tahun.');
                invalidField = input;
            } else if (age >= 1 && age <= 100) {
                clearFieldError(input);
            }
        });

        form.querySelectorAll('[data-validate="file"]').forEach((input) => {
            if (!input.files?.length) return;
            const ext = (input.files[0].name.split('.').pop() || '').toLowerCase();
            if (!['jpg', 'jpeg', 'png'].includes(ext) && !invalidField) {
                setFieldError(input, 'File identitas harus berformat jpg, jpeg, atau png.');
                invalidField = input;
            } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
                clearFieldError(input);
            }
        });

        return invalidField;
    }

    // ==========================================================
    // 🔹 SET MINIMAL TANGGAL HARI INI
    // ==========================================================
    function setMinDate() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const todayString = today.toISOString().split('T')[0];

        if (tglNaik) {
            tglNaik.min = todayString;
            tglNaik.setAttribute('min', todayString);
        }
        if (tglTurun) {
            tglTurun.min = todayString;
            tglTurun.setAttribute('min', todayString);
        }
    }

    setMinDate();

    // ==========================================================
    // 🔹 FUNGSI UPDATE TOTAL BIAYA
    // ==========================================================
    function updateTotal() {
        let harga = 0;

        // Prioritas ambil dari hidden input (untuk preset)
        if (hargaRawInput && hargaRawInput.value) {
            harga = parseInt(hargaRawInput.value) || 0;
        } else {
            harga = parseInt(hargaInput.value.replace(/\D/g, '')) || 0;
        }

        const jumlah = parseInt(jumlahInput.value) || 1;
        const total = harga * jumlah;

        hargaInput.value = new Intl.NumberFormat('id-ID').format(harga);
        totalInput.value = new Intl.NumberFormat('id-ID').format(total);
        updateTotalKeseluruhan();
    }

    // ==========================================================
    // 🔹 CEK MODE FORM (MANUAL vs PRESET)
    // ==========================================================
    const isManualForm = gunungSelect && gunungSelect.tagName === 'SELECT';
    const isPresetForm = !isManualForm && hargaRawInput && hargaRawInput.value;

    // ==========================================================
    // 🔹 JIKA FORM PRESET (DARI HALAMAN GUNUNG)
    // ==========================================================
    if (isPresetForm) {
        updateTotal();
    }

    // ==========================================================
    // 🔹 FETCH RUTE BERDASARKAN GUNUNG (UNTUK FORM MANUAL)
    // ==========================================================
    if (isManualForm && ruteSelect) {
        gunungSelect.addEventListener('change', function() {
            const gunungId = this.value;

            ruteSelect.innerHTML = '<option value="">Memuat...</option>';
            hargaInput.value = '0';
            totalInput.value = '0';
            updateTotalKeseluruhan();

            if (!gunungId) {
                ruteSelect.innerHTML = '<option value="">-- Pilih Rute --</option>';
                ruteSelect.disabled = true;
                return;
            }

            fetch(`/api/rute-by-gunung/${gunungId}`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal memuat rute');
                    return res.json();
                })
                .then(data => {
                    ruteSelect.innerHTML = '<option value="">-- Pilih Rute --</option>';

                    if (data.length === 0) {
                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.textContent = 'Tidak ada rute tersedia';
                        ruteSelect.appendChild(opt);
                        ruteSelect.disabled = true;
                    } else {
                        data.forEach(r => {
                            const opt = document.createElement('option');
                            opt.value = r.id_rute;
                            opt.textContent = r.nama_rute;
                            opt.setAttribute('data-harga', r.harga);
                            ruteSelect.appendChild(opt);
                        });
                        ruteSelect.disabled = false;
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    ruteSelect.innerHTML = '<option value="">Gagal memuat rute</option>';
                    alert('⚠️ Gagal memuat data rute. Silakan coba lagi.');
                });
        });

        ruteSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga') || 0;

            hargaInput.value = new Intl.NumberFormat('id-ID').format(harga);
            updateTotal();
        });
    }

    if (jumlahInput) {
        jumlahInput.addEventListener('input', function() {
            updateTotal();
            updateAnggotaForms();
        });
    }

    document.addEventListener('input', function(e) {
        if (e.target.matches('input[name="no_identitas"], .anggota-no-identitas')) {
            validateDynamicIdentityInput(e.target);
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.matches('#jenis_identitas, .anggota-jenis-identitas')) {
            const relatedInput = e.target.matches('#jenis_identitas')
                ? form.querySelector('input[name="no_identitas"]')
                : e.target.closest('.anggota-item')?.querySelector('.anggota-no-identitas');
            if (relatedInput) {
                validateDynamicIdentityInput(relatedInput);
            }
        }
    });

    // ==========================================================
    // 🔹 VALIDASI TANGGAL
    // ==========================================================
    if (tglNaik) {
        tglNaik.addEventListener('change', function() {
            const naik = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (naik < today) {
                alert("⚠️ Tanggal naik tidak boleh sebelum hari ini!");
                this.value = '';
                return;
            }

            if (tglTurun) {
                tglTurun.min = this.value;

                if (tglTurun.value) {
                    const turun = new Date(tglTurun.value);
                    if (turun <= naik) {
                        alert("⚠️ Tanggal turun harus setelah tanggal naik!");
                        tglTurun.value = '';
                    }
                }
            }
        });
    }

    if (tglTurun) {
        tglTurun.addEventListener('change', function() {
            const turun = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (turun < today) {
                alert("⚠️ Tanggal turun tidak boleh sebelum hari ini!");
                this.value = '';
                return;
            }

            if (tglNaik.value) {
                const naik = new Date(tglNaik.value);
                if (turun <= naik) {
                    alert("⚠️ Tanggal turun harus setelah tanggal naik!");
                    this.value = '';
                }
            }
        });
    }

    // ==========================================================
    // 🔹 UPDATE FORM ANGGOTA
    // ==========================================================
    function updateAnggotaForms() {
        const jumlah = parseInt(jumlahInput.value) || 1;
        anggotaList.innerHTML = '';

        if (jumlah > 1) {
            anggotaContainer.style.display = 'block';

            for (let i = 0; i < jumlah - 1; i++) {
                const div = document.createElement('div');
                div.classList.add('anggota-item');

                div.innerHTML = `
                    <h6>🧍 Anggota ${i + 2}</h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="anggota[${i}][nama]" class="form-control" data-validate="name" minlength="3" maxlength="100" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Jenis Kelamin</label>
                            <select name="anggota[${i}][jenis_kelamin]" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Umur</label>
                            <input type="number" name="anggota[${i}][usia]" class="form-control" min="1" max="100" data-validate="age" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>No. Telepon</label>
                            <input type="text" name="anggota[${i}][no_telepon]" class="form-control" placeholder="08xxxxxxxxxx" data-validate="phone" minlength="10" maxlength="15" inputmode="numeric" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Jenis Identitas</label>
                            <select name="anggota[${i}][jenis_identitas]" class="form-select anggota-jenis-identitas" required>
                                <option value="">-- Pilih --</option>
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="KK">KK</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>No. Identitas</label>
                            <input type="text" name="anggota[${i}][no_identitas]" class="form-control anggota-no-identitas" maxlength="20" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Foto Identitas</label>
                            <input type="file" name="anggota[${i}][foto_identitas]" class="form-control" accept=".jpg,.jpeg,.png" data-validate="file" data-allowed-ext="jpg,jpeg,png" required>
                        </div>
                    </div>
                `;

                anggotaList.appendChild(div);
            }
        } else {
            anggotaContainer.style.display = 'none';
        }
    }

    // ==========================================================
    // 🔹 SUBMIT FORM
    // ==========================================================
    form.addEventListener('submit', function(e) {
        const canUseEnhancedSubmit =
            typeof window.fetch === 'function' &&
            typeof window.FormData === 'function' &&
            typeof window.Swal !== 'undefined';

        if (!canUseEnhancedSubmit) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        if (isSubmittingPendaftaran) {
            return;
        }

        setSubmitButtonState(true);

        const invalidField = validatePendaftaranForm();
        if (invalidField) {
            invalidField.focus();
            setSubmitButtonState(false);
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                text: invalidField.validationMessage || 'Periksa kembali data yang masih tidak valid.',
                confirmButtonColor: '#28a745'
            });
            return;
        }

        const total = parseInt(totalInput.value.replace(/\D/g, '')) || 0;
        if (total <= 0) {
            alert("Harga pendakian belum tersedia. Silakan pilih rute atau periksa data.");
            setSubmitButtonState(false);
            return;
        }

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Pendaftaran',
            text: 'Pastikan data pendakian dan ringkasan biaya sudah benar sebelum melanjutkan.',
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (!result.isConfirmed) {
                setSubmitButtonState(false);
                return;
            }

            isSubmittingPendaftaran = true;
            submitPendaftaranViaAjax();
        });
    }, true);

    form.addEventListener('submit', function(e) {
        if (!(typeof window.fetch === 'function' && typeof window.FormData === 'function' && typeof window.Swal !== 'undefined')) {
            return;
        }

        e.preventDefault();

        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = "⏳ Menyimpan...";

        const invalidField = validatePendaftaranForm();
        if (invalidField) {
            invalidField.focus();
            btn.disabled = false;
            btn.textContent = "Simpan & Bayar Sekarang";
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal',
                text: invalidField.validationMessage || 'Periksa kembali data yang masih tidak valid.',
                confirmButtonColor: '#28a745'
            });
            return;
        }

        if (form.dataset.confirmed !== 'true') {
            btn.disabled = false;
            btn.textContent = "Simpan & Bayar Sekarang";
            Swal.fire({
                icon: 'question',
                title: 'Konfirmasi Pendaftaran',
                text: 'Pastikan data pendakian dan ringkasan biaya sudah benar sebelum melanjutkan.',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.requestSubmit ? form.requestSubmit() : form.submit();
                }
            });
            return;
        }

        delete form.dataset.confirmed;

        const formData = new FormData(form);

        const total = parseInt(totalInput.value.replace(/\D/g, '')) || 0;
        if (total <= 0) {
            alert("⚠️ Harga pendakian belum tersedia. Silakan pilih rute atau periksa data.");
            btn.disabled = false;
            btn.textContent = "Simpan & Bayar Sekarang";
            return;
        }

        fetch("/pendaftaran", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                "Accept": "application/json"
            }
        })
        .then(res => {
            if (!res.ok) {
                return res.text().then(text => {
                    let msg = text || res.statusText || 'Unknown error';
                    try {
                        const j = JSON.parse(text);
                        if (j.message) msg = j.message;
                        else if (j.errors) msg = JSON.stringify(j.errors);
                    } catch (e) {}
                    throw new Error(res.status + ' - ' + msg);
                });
            }
            return res.json();
        })
        .then(data => {
            if (data.success && data.snap_token) {
                let gunungText = "";
                let ruteText = "";

                if (isManualForm) {
                    gunungText = gunungSelect.selectedOptions[0]?.text || '';
                    ruteText = ruteSelect.selectedOptions[0]?.text || '';
                } else {
                    const gunungInput = document.querySelector('input[name="gunung_id"]');
                    const ruteInput = document.querySelector('input[name="rute_pendakian_id"]');

                    if (gunungInput && gunungInput.previousElementSibling) {
                        gunungText = gunungInput.previousElementSibling.value;
                    }
                    if (ruteInput && ruteInput.previousElementSibling) {
                        ruteText = ruteInput.previousElementSibling.value;
                    }
                }

                const biayaSimaksi = parseInt(totalInput.value.replace(/\D/g, '')) || 0;
                const biayaAlat = parseInt(document.getElementById('totalBiayaAlat').textContent.replace(/\D/g, '')) || 0;
                const totalKeseluruhan = biayaSimaksi + biayaAlat;

                document.getElementById('modalGunung').textContent = gunungText;
                document.getElementById('modalRute').textContent = ruteText;
                document.getElementById('modalTanggal').textContent = tglNaik.value;
                document.getElementById('modalBiayaSimaksi').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(biayaSimaksi);
                document.getElementById('modalTotal').textContent = new Intl.NumberFormat('id-ID').format(totalKeseluruhan);

                const modalBiayaAlatContainer = document.getElementById('modalBiayaAlatContainer');
                const modalAlatDetail = document.getElementById('modalAlatDetail');

                if (biayaAlat > 0) {
                    modalBiayaAlatContainer.style.display = 'block';
                    document.getElementById('modalBiayaAlat').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(biayaAlat);

                    let alatDetailHtml = '<strong>Alat yang dipinjam:</strong><br>';
                    let hasAlat = false;

                    document.querySelectorAll('.alat-input').forEach(input => {
                        const quantity = parseInt(input.value) || 0;
                        if (quantity > 0) {
                            hasAlat = true;
                            const harga = parseInt(input.getAttribute('data-harga')) || 0;
                            const namaAlat = input.closest('.card-body').querySelector('.card-title').textContent;
                            const totalHarga = quantity * harga;

                            alatDetailHtml += `• ${namaAlat} (${quantity}x) - Rp${new Intl.NumberFormat('id-ID').format(totalHarga)}<br>`;
                        }
                    });

                    if (hasAlat) {
                        modalAlatDetail.innerHTML = alatDetailHtml;
                    } else {
                        modalAlatDetail.innerHTML = '<em>Tidak ada alat yang dipinjam</em>';
                    }
                } else {
                    modalBiayaAlatContainer.style.display = 'none';
                }

                const modal = new bootstrap.Modal(document.getElementById('modalPembayaran'));
                modal.show();

                document.getElementById('btnBayarSekarang').onclick = function() {
                    if (!window.snap || typeof window.snap.pay !== 'function') {
                        alert("âŒ Layanan pembayaran tidak tersedia saat ini.");
                        return;
                    }

                    modal.hide();
                    window.snap.pay(data.snap_token, {
                        onSuccess: function() {
                            alert("✅ Pembayaran Berhasil! Silakan tunggu persetujuan admin untuk mencetak formulir SIMAKSI.");
                            window.location.href = "/pendaftaran";
                        },
                        onPending: function() {
                            alert("🕓 Pembayaran menunggu konfirmasi.");
                            window.location.href = "/pendaftaran";
                        },
                        onError: function() {
                            alert("❌ Terjadi kesalahan saat pembayaran.");
                        },
                        onClose: function() {
                            alert("⚠️ Kamu menutup popup sebelum pembayaran selesai.");
                        }
                    });
                };
            } else {
                alert("❌ Gagal membuat transaksi: " + (data.message || "Terjadi kesalahan."));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert("❌ Terjadi kesalahan: " + err.message + "\n(Periksa console/network & storage/logs/laravel.log untuk detail)");
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = "Simpan & Bayar Sekarang";
        });
    });

    const btnCetak = document.getElementById('btnCetakPdf');
    if (btnCetak) {
        btnCetak.addEventListener('click', function() {
            const id = this.dataset.id;
            if (id) {
                window.open(`/pendaftaran/${id}/cetak`, '_blank');
            } else {
                alert("ID pendaftaran tidak ditemukan.");
            }
        });
    }
});
</script>

{{-- ===================== Payment Modal (keep ids used by JS) ===================== --}}
<div class="modal fade" id="modalPembayaran" tabindex="-1" aria-labelledby="modalPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:20px; overflow:hidden; border:none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #104734 0%, #228B22 100%); border:none; padding:24px 32px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <span style="font-size:2rem;">💳</span>
                    <h5 class="modal-title" id="modalPembayaranLabel" style="color:#fff; font-weight:700; margin:0; font-size:1.5rem;">Pembayaran SIMAKSI</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body" style="padding:32px;">
                <div style="text-align:center; margin-bottom:24px;">
                    <p style="font-size:1.1rem; margin:0;">Halo, <strong style="color:#104734;">{{ Auth::user()->name }}</strong> 👋</p>
                    <p style="color:#6c757d; font-size:0.9rem; margin-top:4px;">Silakan cek detail pembayaran Anda di bawah ini</p>
                </div>

                <div style="background: linear-gradient(135deg, #f8fffe 0%, #e8f5e9 100%); border-radius:16px; padding:24px; margin-bottom:24px; border:2px solid #c8e6c9;">
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #d4edda;">
                        <span style="color:#6c757d; font-weight:600; display:flex; align-items:center; gap:8px;"><span style="font-size:1.2rem;">🏔️</span> Gunung</span>
                        <span id="modalGunung" style="color:#104734; font-weight:700; font-size:1.05rem;"></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #d4edda;">
                        <span style="color:#6c757d; font-weight:600; display:flex; align-items:center; gap:8px;"><span style="font-size:1.2rem;">🚶</span> Rute</span>
                        <span id="modalRute" style="color:#104734; font-weight:700; font-size:1.05rem;"></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #d4edda;">
                        <span style="color:#6c757d; font-weight:600; display:flex; align-items:center; gap:8px;"><span style="font-size:1.2rem;">📅</span> Tanggal Pendakian</span>
                        <span id="modalTanggal" style="color:#104734; font-weight:700; font-size:1.05rem;"></span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #d4edda;">
                        <span style="color:#6c757d; font-weight:600; display:flex; align-items:center; gap:8px;"><span style="font-size:1.2rem;">💰</span> Biaya SIMAKSI</span>
                        <span id="modalBiayaSimaksi" style="color:#104734; font-weight:700; font-size:1.05rem;"></span>
                    </div>

                    <div id="modalBiayaAlatContainer" style="display:none; padding:12px 0; border-bottom:1px solid #d4edda;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span style="color:#6c757d; font-weight:600; display:flex; align-items:center; gap:8px;"><span style="font-size:1.2rem;">🛠️</span> Biaya Peminjaman Alat</span>
                            <span id="modalBiayaAlat" style="color:#104734; font-weight:700; font-size:1.05rem;"></span>
                        </div>
                        <div id="modalAlatDetail" style="margin-top:12px; padding:12px; background: rgba(255,255,255,0.7); border-radius:8px; font-size:0.9rem;"></div>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:16px 0; margin-top:8px;">
                        <span style="color:#104734; font-weight:700; font-size:1.2rem; display:flex; align-items:center; gap:8px;"><span style="font-size:1.4rem;">💰</span> Total Bayar</span>
                        <span style="color:#28a745; font-weight:700; font-size:1.5rem;">Rp<span id="modalTotal"></span></span>
                    </div>
                </div>

                <div style="display:flex; flex-direction:column; gap:12px;">
                    <button type="button" id="btnBayarSekarang" class="btn btn-success w-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border:none; padding:16px; border-radius:12px; font-weight:700; font-size:1.1rem; display:flex; align-items:center; justify-content:center; gap:10px; transition:all 0.3s ease;">
                        <span style="font-size:1.3rem;">💳</span><span>Bayar Sekarang</span>
                    </button>
                </div>

                <div style="text-align:center; margin-top:20px;">
                    <p style="color:#6c757d; font-size:0.85rem; margin:0;"><span style="color:#28a745;">🔒</span> Pembayaran aman dan terenkripsi</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

