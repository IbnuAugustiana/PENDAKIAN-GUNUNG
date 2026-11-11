<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SIMAKSI.COM</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- Tema, font, ikon & animasi (public/css/simaksi-theme.css) --}}
    @include('partials.ui.head')
</head>
<body class="ui auth-page">
    @include('partials.ui.body-start')

    {{-- Panel visual (kiri) --}}
    <aside class="auth-visual" aria-hidden="true">
        <div class="auth-visual__bg"><img src="{{ asset('images/gunung/arjuno.jpg') }}" alt=""></div>
        <a href="{{ url('/') }}" class="auth-visual__brand" tabindex="-1" data-reveal="fade-down">
            <img src="{{ asset('images/logo.png') }}" alt=""> SIMAKSI.COM
        </a>
        <div class="auth-visual__quote">
            <h2 data-reveal="fade-up">Puncak menanti, izinnya cukup dari sini.</h2>
            <p data-reveal="fade-up" data-delay=".1">Pantau status SIMAKSI, pilih rute resmi, dan siapkan pendakian dengan tenang.</p>
            <div class="auth-visual__chips" data-stagger>
                <span class="chip"><i class="fa fa-route"></i> Rute Resmi</span>
                <span class="chip"><i class="fa fa-signal"></i> Status Real-time</span>
                <span class="chip"><i class="fa fa-shield-halved"></i> Aman</span>
            </div>
        </div>
    </aside>

    {{-- Panel form (kanan) --}}
    <main class="auth-panel" id="main-content">
        <div class="auth-shell">
            <a href="{{ url('/') }}" class="auth-back" data-reveal="fade-right"><i class="fa fa-arrow-left"></i> Kembali ke Beranda</a>

            <div class="auth-card" data-reveal="fade-up" data-delay=".05">
                <div class="auth-brand">
                    <span class="auth-brand-icon"><img src="{{ asset('images/logo.png') }}" alt="Logo SIMAKSI"></span>
                    <div>
                        <div class="auth-brand-name">SIMAKSI.COM</div>
                        <div class="auth-brand-sub">Sistem Informasi Manajemen Pendakian</div>
                    </div>
                </div>

                <h1 class="auth-title">Masuk ke akun Anda</h1>
                <p class="auth-subtitle">Kelola pendaftaran pendakian Anda dengan mudah.</p>

                {{-- Alert Error --}}
                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="fa fa-circle-exclamation mt-1"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fa fa-circle-check mt-1"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Alert Warning (dari middleware redirect) --}}
                @if(session('warning'))
                    <div class="alert alert-error" id="warningAlert">
                        <i class="fa fa-triangle-exclamation mt-1"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" id="loginForm" novalidate>
                    @csrf

                    <div class="form-group">
                        <div class="field-float">
                            <input type="email" name="email" id="loginEmail" class="f-input form-input" placeholder="masukkan@email.com" required value="{{ old('email') }}" maxlength="100" autocomplete="email">
                            <label class="f-label" for="loginEmail">Email</label>
                            <i class="fa fa-envelope field-icon" aria-hidden="true"></i>
                            <svg class="valid-check" viewBox="0 0 20 20" aria-hidden="true"><circle cx="10" cy="10" r="10"/><path d="M5.5 10.5l3 3 6-6.5"/></svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="field-float has-toggle password-wrapper">
                            <input type="password" name="password" id="password" class="f-input form-input" placeholder="••••••••" required autocomplete="current-password">
                            <label class="f-label" for="password">Password</label>
                            <i class="fa fa-lock field-icon" aria-hidden="true"></i>
                            <i class="fa fa-eye toggle-password" onclick="togglePassword()" role="button" aria-label="Tampilkan password"></i>
                        </div>
                    </div>

                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Ingat Saya</span>
                        </label>
                        <a href="#" class="forgot-link">Lupa Password?</a>
                    </div>

                    <button type="submit" class="btn btn-simaksi-primary btn-login"><i class="fa fa-right-to-bracket"></i> <span>Masuk</span></button>
                </form>

                <div class="divider"><span>atau</span></div>

                {{-- Google Login Button --}}
                <a href="{{ route('auth.google') }}" class="btn-google">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Masuk dengan Google
                </a>

                <div class="auth-footer-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');

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

        // Tutup otomatis alert setelah 4 detik
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) {
                alert.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-6px)';
                setTimeout(() => alert.remove(), 600);
            }
        }, 4000);

        document.getElementById('loginForm')?.addEventListener('submit', function (e) {
            const email = this.querySelector('input[name="email"]');
            if (!email) return;
            email.value = String(email.value || '').replace(/\s+/g, '');
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Format email tidak valid.', confirmButtonColor: '#15803d' });
                email.focus();
            }
        });
    </script>

    @include('partials.ui.scripts')
</body>
</html>
