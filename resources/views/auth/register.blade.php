<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SIMAKSI.COM</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- Tema, font, ikon & animasi (public/css/simaksi-theme.css) --}}
    @include('partials.ui.head')
</head>
<body class="ui auth-page">
    @include('partials.ui.body-start')

    {{-- Panel visual (kiri) --}}
    <aside class="auth-visual" aria-hidden="true">
        <div class="auth-visual__bg"><img src="{{ asset('images/gunung/GunungMerapi.jpg') }}" alt=""></div>
        <a href="{{ url('/') }}" class="auth-visual__brand" tabindex="-1" data-reveal="fade-down">
            <img src="{{ asset('images/logo.png') }}" alt=""> SIMAKSI.COM
        </a>
        <div class="auth-visual__quote">
            <h2 data-reveal="fade-up">Satu akun untuk semua pendakian Anda.</h2>
            <p data-reveal="fade-up" data-delay=".1">Daftar sekali, lalu ajukan SIMAKSI ke berbagai gunung dan pantau statusnya dari dashboard.</p>
            <div class="auth-visual__chips" data-stagger>
                <span class="chip"><i class="fa fa-mountain"></i> 15+ Gunung</span>
                <span class="chip"><i class="fa fa-file-signature"></i> Formulir Online</span>
                <span class="chip"><i class="fa fa-credit-card"></i> Bayar Mudah</span>
            </div>
        </div>
    </aside>

    {{-- Panel form (kanan) --}}
    <main class="auth-panel" id="main-content">
        <div class="auth-shell auth-shell--wide">
            <a href="{{ url('/') }}" class="auth-back" data-reveal="fade-right"><i class="fa fa-arrow-left"></i> Kembali ke Beranda</a>

            <div class="auth-card" data-reveal="fade-up" data-delay=".05">
                <div class="auth-brand">
                    <span class="auth-brand-icon"><img src="{{ asset('images/logo.png') }}" alt="Logo SIMAKSI"></span>
                    <div>
                        <div class="auth-brand-name">SIMAKSI.COM</div>
                        <div class="auth-brand-sub">Sistem Informasi Manajemen Pendakian</div>
                    </div>
                </div>

                <h1 class="auth-title">Buat akun baru</h1>
                <p class="auth-subtitle">Daftar untuk mulai mengajukan izin pendakian.</p>

                <div class="auth-body">
                    {{-- Alert Info untuk Google OAuth --}}
                    @if(session('info'))
                        <div class="alert alert-success">
                            <i class="fa fa-circle-info mt-1"></i>
                            <span>{{ session('info') }}</span>
                        </div>
                    @endif

                    {{-- Alert Error --}}
                    @if($errors->any())
                        <div class="alert alert-error">
                            <i class="fa fa-circle-exclamation mt-1"></i>
                            <div>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST" id="registerForm" novalidate>
                        @csrf

                        <div class="form-group">
                            <div class="field-float input-wrap">
                                <input type="text" name="nama_lengkap" id="regNama" class="f-input form-input @error('nama_lengkap') error @enderror"
                                       placeholder="Masukkan nama lengkap"
                                       required
                                       minlength="3"
                                       maxlength="100"
                                       autocomplete="name"
                                       value="{{ old('nama_lengkap') }}">
                                <label class="f-label" for="regNama">Nama Lengkap <span class="required">*</span></label>
                                <i class="fa fa-user field-icon" aria-hidden="true"></i>
                                <svg class="valid-check" viewBox="0 0 20 20" aria-hidden="true"><circle cx="10" cy="10" r="10"/><path d="M5.5 10.5l3 3 6-6.5"/></svg>
                            </div>
                            @error('nama_lengkap')
                                <div class="error-message"><i class="fa fa-triangle-exclamation"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <div class="field-float input-wrap">
                                    <input type="email" name="email" id="regEmail" class="f-input form-input @error('email') error @enderror"
                                           placeholder="contoh@email.com"
                                           required
                                           maxlength="100"
                                           autocomplete="email"
                                           value="{{ old('email', session('google_user.email') ?? '') }}">
                                    <label class="f-label" for="regEmail">Email <span class="required">*</span></label>
                                    <i class="fa fa-envelope field-icon" aria-hidden="true"></i>
                                    <svg class="valid-check" viewBox="0 0 20 20" aria-hidden="true"><circle cx="10" cy="10" r="10"/><path d="M5.5 10.5l3 3 6-6.5"/></svg>
                                </div>
                                @error('email')
                                    <div class="error-message"><i class="fa fa-triangle-exclamation"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="field-float input-wrap">
                                    <input type="tel" name="no_telepon" id="regTelp" class="f-input form-input @error('no_telepon') error @enderror"
                                           placeholder="08123456789"
                                           required
                                           minlength="10"
                                           maxlength="15"
                                           inputmode="numeric"
                                           pattern="[0-9]{10,15}"
                                           autocomplete="tel"
                                           value="{{ old('no_telepon') }}">
                                    <label class="f-label" for="regTelp">No. Telepon <span class="required">*</span></label>
                                    <i class="fa fa-phone field-icon" aria-hidden="true"></i>
                                    <svg class="valid-check" viewBox="0 0 20 20" aria-hidden="true"><circle cx="10" cy="10" r="10"/><path d="M5.5 10.5l3 3 6-6.5"/></svg>
                                </div>
                                @error('no_telepon')
                                    <div class="error-message"><i class="fa fa-triangle-exclamation"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="field-float input-wrap">
                                <textarea name="alamat" id="regAlamat" class="f-input form-input"
                                          placeholder="Masukkan alamat lengkap"
                                          maxlength="255"
                                          required>{{ old('alamat') }}</textarea>
                                <label class="f-label" for="regAlamat">Alamat <span class="required">*</span></label>
                                <i class="fa fa-location-dot field-icon" aria-hidden="true"></i>
                                <svg class="valid-check" viewBox="0 0 20 20" aria-hidden="true"><circle cx="10" cy="10" r="10"/><path d="M5.5 10.5l3 3 6-6.5"/></svg>
                            </div>
                            @error('alamat')
                                <div class="error-message"><i class="fa fa-triangle-exclamation"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <div class="field-float has-toggle input-wrap password-wrapper">
                                    <input type="password" name="password" id="password"
                                           class="f-input form-input"
                                           placeholder="Minimal 6 karakter"
                                           required
                                           onkeyup="checkPasswordStrength()">
                                    <label class="f-label" for="password">Password <span class="required">*</span></label>
                                    <i class="fa fa-lock field-icon" aria-hidden="true"></i>
                                    <i class="fa fa-eye toggle-password" onclick="togglePassword('password')" role="button" aria-label="Tampilkan password"></i>
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="strengthBar"></div>
                                </div>
                                <div class="password-hint" id="strengthText">Masukkan password</div>
                                @error('password')
                                    <div class="error-message"><i class="fa fa-triangle-exclamation"></i> {{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="field-float has-toggle input-wrap password-wrapper">
                                    <input type="password" name="password_confirmation"
                                           id="password_confirmation"
                                           class="f-input form-input"
                                           placeholder="Ulangi password"
                                           required>
                                    <label class="f-label" for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                                    <i class="fa fa-lock field-icon" aria-hidden="true"></i>
                                    <i class="fa fa-eye toggle-password" onclick="togglePassword('password_confirmation')" role="button" aria-label="Tampilkan password"></i>
                                </div>
                                @error('password_confirmation')
                                    <div class="error-message"><i class="fa fa-triangle-exclamation"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="terms-checkbox">
                            <input type="checkbox" name="terms" id="terms" required>
                            <label for="terms">
                                Saya menyetujui <a href="#">Syarat &amp; Ketentuan</a> dan
                                <a href="#">Kebijakan Privasi</a> SIMAKSI.COM
                            </label>
                        </div>

                        <button type="submit" class="btn btn-simaksi-primary btn-register" id="btnSubmit">
                            <i class="fa fa-user-plus me-1"></i> Daftar Sekarang
                        </button>
                    </form>

                    <div class="divider"><span>atau</span></div>

                    <div class="auth-footer-link">
                        Sudah punya akun? <a href="{{ route('login') }}">Masuk Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = event.target.closest('.toggle-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            // Reset
            strengthBar.className = 'password-strength-bar';

            if (password.length === 0) {
                strengthText.textContent = 'Masukkan password';
                return;
            }

            let strength = 0;

            // Check length
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;

            // Check for numbers
            if (/\d/.test(password)) strength++;

            // Check for uppercase
            if (/[A-Z]/.test(password)) strength++;

            // Check for special characters
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Set strength
            if (strength <= 2) {
                strengthBar.classList.add('weak');
                strengthText.textContent = 'Password lemah';
                strengthText.style.color = '#dc2626';
            } else if (strength <= 4) {
                strengthBar.classList.add('medium');
                strengthText.textContent = 'Password sedang';
                strengthText.style.color = '#d97706';
            } else {
                strengthBar.classList.add('strong');
                strengthText.textContent = 'Password kuat';
                strengthText.style.color = '#15803d';
            }
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const terms = document.getElementById('terms').checked;

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Validasi Gagal', text: 'Password dan konfirmasi password tidak sama!' });
                return false;
            }

            if (!terms) {
                e.preventDefault();
                Swal.fire({ icon: 'info', title: 'Perlu Persetujuan', text: 'Anda harus menyetujui Syarat & Ketentuan!' });
                return false;
            }

            // Disable button to prevent double submit
            document.getElementById('btnSubmit').disabled = true;
            document.getElementById('btnSubmit').innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Mendaftar...';
        });
        // Fallback: override alert() menjadi SweetAlert jika dipanggil
        (function(){
            const originalAlert = window.alert;
            window.alert = function(message){
                try { Swal.fire({ icon: 'info', title: String(message || '') }); } catch(e) { originalAlert(message); }
            };
        })();
    </script>
    <script>
        (function () {
            const form = document.getElementById('registerForm');
            if (!form) return;

            function setFieldError(field, message) {
                field.classList.add('is-invalid', 'error');
                field.setCustomValidity(message);
                let error = field.parentElement.parentElement.querySelector('.client-error');
                if (!error) {
                    error = document.createElement('div');
                    error.className = 'error-message client-error';
                    field.parentElement.parentElement.appendChild(error);
                }
                error.innerHTML = '<i class="fa fa-triangle-exclamation"></i> ' + message;
            }

            function clearFieldError(field) {
                field.classList.remove('is-invalid', 'error');
                field.setCustomValidity('');
                const error = field.parentElement.parentElement.querySelector('.client-error');
                if (error) error.remove();
            }

            function normalizeName(value) {
                return String(value || '').replace(/[^A-Za-z\s'.-]/g, '').replace(/\s+/g, ' ').trim();
            }

            function validateField(field) {
                if (field.name === 'nama_lengkap') {
                    field.value = normalizeName(field.value);
                    if (field.value.length < 3) return setFieldError(field, 'Nama lengkap minimal 3 karakter.'), false;
                    if (/^\d+$/.test(field.value) || !/[A-Za-z]/.test(field.value)) return setFieldError(field, 'Nama lengkap harus mengandung huruf.'), false;
                }

                if (field.name === 'email') {
                    field.value = String(field.value || '').replace(/\s+/g, '');
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) return setFieldError(field, 'Format email tidak valid.'), false;
                }

                if (field.name === 'no_telepon') {
                    field.value = String(field.value || '').replace(/\D/g, '').slice(0, 15);
                    if (!/^\d{10,15}$/.test(field.value)) return setFieldError(field, 'Nomor HP harus 10-15 digit angka.'), false;
                }

                clearFieldError(field);
                return true;
            }

            ['nama_lengkap', 'email', 'no_telepon'].forEach((name) => {
                const field = form.querySelector(`[name="${name}"]`);
                if (!field) return;
                field.addEventListener('input', () => validateField(field));
                field.addEventListener('blur', () => validateField(field));
            });

            form.addEventListener('submit', function (e) {
                if (form.dataset.confirmed === 'true') {
                    delete form.dataset.confirmed;
                    return;
                }

                const fields = ['nama_lengkap', 'email', 'no_telepon']
                    .map((name) => form.querySelector(`[name="${name}"]`))
                    .filter(Boolean);

                for (const field of fields) {
                    if (!validateField(field)) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        field.focus();
                        Swal.fire({ icon: 'warning', title: 'Validasi Gagal', text: 'Periksa kembali data yang masih tidak valid.' });
                        return;
                    }
                }

                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                const terms = document.getElementById('terms').checked;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    Swal.fire({ icon: 'warning', title: 'Validasi Gagal', text: 'Password dan konfirmasi password tidak sama!' });
                    return;
                }

                if (!terms) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    Swal.fire({ icon: 'info', title: 'Perlu Persetujuan', text: 'Anda harus menyetujui Syarat & Ketentuan!' });
                    return;
                }

                e.preventDefault();
                e.stopImmediatePropagation();
                Swal.fire({
                    icon: 'question',
                    title: 'Konfirmasi Pendaftaran',
                    text: 'Pastikan data akun yang Anda isi sudah benar.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, daftar',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#15803d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        document.getElementById('btnSubmit').disabled = true;
                        document.getElementById('btnSubmit').innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> Mendaftar...';
                        form.requestSubmit ? form.requestSubmit() : form.submit();
                    }
                });
            }, true);
        })();
    </script>

    @include('partials.ui.scripts')
</body>
</html>
