<div id="anggota-container" class="mb-4">
    <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
        <div>
            <h5 class="fw-bold mb-1" style="color:var(--simaksi-primary);">
                <i class="fa fa-users me-2"></i>Anggota Pendakian
            </h5>
            <p class="text-muted mb-0 small">Isi data anggota sesuai jumlah pendaki. Jika hanya Anda sendiri, biarkan jumlah tetap 1.</p>
        </div>
        <span class="badge badge-primary bg-primary bg-opacity-10 text-success border border-success border-opacity-25" style="font-size:.75rem;">
            <i class="fa fa-shield-halved me-1"></i>Validasi otomatis
        </span>
    </div>

    <div id="anggota-list" class="row g-3">
        <!-- Form anggota akan dibuat otomatis lewat JavaScript -->
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const jumlahInput = document.getElementById('jumlah_pendaki');
    const anggotaList = document.getElementById('anggota-list');

    function createAnggotaForm(index) {
        return `
        <div class="col-12">
            <div class="card" style="border:1px solid var(--simaksi-border); border-radius:var(--simaksi-radius); box-shadow:var(--simaksi-shadow);">

                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <div class="fw-bold" style="color:var(--simaksi-primary);"><i class="fa fa-user me-2"></i>Anggota ${index}</div>
                    <span class="badge bg-primary bg-opacity-10 text-success border border-success border-opacity-25"><i class="fa fa-circle-check me-1"></i>Data</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="anggota[${index}][nama]" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="anggota[${index}][jenis_kelamin]" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Usia</label>
                            <input type="number" name="anggota[${index}][usia]" class="form-control" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="anggota[${index}][no_telepon]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Identitas</label>
                            <select name="anggota[${index}][jenis_identitas]" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="KTP">KTP</option>
                                <option value="SIM">SIM</option>
                                <option value="KK">KK</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Identitas</label>
                            <input type="text" name="anggota[${index}][no_identitas]" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Upload Foto Identitas (KTP/SIM/KK)</label>
                            <input type="file" name="anggota[${index}][foto_identitas]" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }

    function updateAnggotaForms() {
        const jumlah = parseInt(jumlahInput.value) || 1;
        anggotaList.innerHTML = '';
        for (let i = 1; i <= jumlah; i++) {
            anggotaList.innerHTML += createAnggotaForm(i);
        }
    }

    jumlahInput.addEventListener('change', updateAnggotaForms);
    updateAnggotaForms(); // tampilkan minimal 1 anggota saat halaman dibuka
});
</script>

