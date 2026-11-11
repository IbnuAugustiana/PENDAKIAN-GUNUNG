<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SIMAKSI Admin</title>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root{
            --admin-bg:#f3f5f4;
            --admin-sidebar-bg:#0b241a;
            --admin-sidebar-bg-2:#0a2019;
            --admin-accent:#15803d;
            --admin-accent-soft:rgba(21,128,61,.16);
            --admin-accent-hover:#166534;
            --admin-card:#ffffff;
            --admin-border:#e6e9e8;
            --admin-text:#1c2521;
            --admin-muted:#6b756f;
            --admin-danger:#dc2626;
            --admin-warning:#d97706;
            --admin-info:#0284c7;
            --admin-radius-lg:16px;
            --admin-radius-md:12px;
            --admin-radius-sm:10px;
            --admin-shadow:0 1px 2px rgba(16,24,20,.04), 0 1px 8px rgba(16,24,20,.04);
            --admin-shadow-md:0 4px 16px rgba(16,24,20,.06);
            --sidebar-width:260px;
            --topbar-height:64px;
        }

        *{box-sizing:border-box;}

        html, body{height:100%;}

        body{
            background:var(--admin-bg);
            color:var(--admin-text);
            font-family:'Plus Jakarta Sans','Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h1,h2,h3,h4,h5,h6{font-weight:700; letter-spacing:-.01em;}

        a{ text-decoration:none; }

        /* ============================================================
           SHELL LAYOUT
        ============================================================ */
        .admin-shell{
            display:flex;
            align-items:flex-start;
            min-height:100vh;
        }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        .admin-sidebar{
            width:var(--sidebar-width);
            flex-shrink:0;
            background:linear-gradient(180deg,var(--admin-sidebar-bg),var(--admin-sidebar-bg-2));
            position:sticky;
            top:0;
            height:100vh;
            overflow-y:auto;
            display:flex;
            flex-direction:column;
            z-index:1040;
            border-right:1px solid rgba(255,255,255,.06);
        }
        .admin-sidebar::-webkit-scrollbar{width:6px;}
        .admin-sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12); border-radius:6px;}

        .sidebar-brand{
            display:flex;
            align-items:center;
            gap:.65rem;
            padding:1.1rem 1.25rem;
            border-bottom:1px solid rgba(255,255,255,.07);
        }
        .sidebar-brand-icon{
            width:38px;
            height:38px;
            border-radius:10px;
            background:var(--admin-accent);
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-size:1rem;
            flex-shrink:0;
            box-shadow:0 4px 10px rgba(21,128,61,.4);
        }
        .sidebar-brand-text{ line-height:1.2; }
        .sidebar-brand-text .name{ color:#fff; font-weight:800; font-size:.98rem; display:block; }
        .sidebar-brand-text .sub{ color:rgba(255,255,255,.45); font-size:.72rem; font-weight:500; }

        .sidebar-nav{
            padding:1rem .85rem;
            flex:1;
        }
        .sidebar-nav .nav-section-label{
            color:rgba(255,255,255,.32);
            font-size:.68rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.08em;
            padding:.5rem .6rem .4rem;
        }
        .sidebar-nav .nav-link{
            display:flex;
            align-items:center;
            gap:.75rem;
            color:rgba(255,255,255,.68);
            padding:.62rem .75rem;
            border-radius:var(--admin-radius-sm);
            font-weight:600;
            font-size:.885rem;
            margin-bottom:.15rem;
            transition:background .16s ease, color .16s ease;
        }
        .sidebar-nav .nav-link i{
            width:18px;
            text-align:center;
            font-size:.92rem;
            flex-shrink:0;
            opacity:.9;
        }
        .sidebar-nav .nav-link:hover{
            background:rgba(255,255,255,.06);
            color:#fff;
        }
        .sidebar-nav .nav-link.active{
            background:var(--admin-accent-soft);
            color:#fff;
            box-shadow:inset 3px 0 0 var(--admin-accent);
        }

        .sidebar-footer{
            padding:.85rem;
            border-top:1px solid rgba(255,255,255,.07);
        }
        .sidebar-footer .btn{
            background:rgba(255,255,255,.06);
            color:rgba(255,255,255,.85);
            border:1px solid rgba(255,255,255,.10);
            font-weight:600;
            font-size:.85rem;
        }
        .sidebar-footer .btn:hover{
            background:rgba(255,255,255,.12);
            color:#fff;
        }

        /* Mobile sidebar off-canvas */
        @media (max-width: 991.98px){
            .admin-sidebar{
                position:fixed;
                left:0;
                top:0;
                transform:translateX(-100%);
                transition:transform .25s ease;
                box-shadow:0 0 0 rgba(0,0,0,0);
            }
            .admin-sidebar.show{
                transform:translateX(0);
                box-shadow:12px 0 32px rgba(0,0,0,.25);
            }
            .sidebar-backdrop{
                display:none;
                position:fixed;
                inset:0;
                background:rgba(10,20,16,.45);
                z-index:1035;
            }
            .sidebar-backdrop.show{ display:block; }
        }

        /* ============================================================
           MAIN COLUMN
        ============================================================ */
        .admin-main{
            flex:1;
            min-width:0;
            display:flex;
            flex-direction:column;
        }

        /* Topbar */
        .admin-topbar{
            height:var(--topbar-height);
            background:#ffffff;
            border-bottom:1px solid var(--admin-border);
            box-shadow:0 1px 3px rgba(16,24,20,.03);
            position:sticky;
            top:0;
            z-index:1020;
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding:0 1.25rem;
            gap:1rem;
        }
        .topbar-left{ display:flex; align-items:center; gap:.85rem; min-width:0; }
        .sidebar-toggle-btn{
            display:none;
            width:38px;
            height:38px;
            border-radius:var(--admin-radius-sm);
            border:1px solid var(--admin-border);
            background:#fff;
            color:var(--admin-text);
            align-items:center;
            justify-content:center;
            flex-shrink:0;
        }
        @media (max-width: 991.98px){
            .sidebar-toggle-btn{ display:inline-flex; }
        }
        .breadcrumb-simaksi{
            display:flex;
            flex-direction:column;
            min-width:0;
        }
        .breadcrumb-simaksi .crumb-trail{
            font-size:.72rem;
            color:var(--admin-muted);
            font-weight:600;
            text-transform:uppercase;
            letter-spacing:.05em;
        }
        .breadcrumb-simaksi .crumb-title{
            font-size:1rem;
            font-weight:700;
            color:var(--admin-text);
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .topbar-right{ display:flex; align-items:center; gap:.85rem; flex-shrink:0; }
        .admin-user-pill{
            display:flex;
            align-items:center;
            gap:.6rem;
            padding:.35rem .6rem .35rem .35rem;
            border-radius:999px;
            border:1px solid var(--admin-border);
            background:#fbfcfb;
        }
        .admin-user-avatar{
            width:34px;
            height:34px;
            border-radius:50%;
            background:var(--admin-accent-soft);
            color:var(--admin-accent);
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            font-size:.85rem;
            flex-shrink:0;
        }
        .admin-user-meta{ line-height:1.15; }
        .admin-user-meta .u-name{ font-size:.85rem; font-weight:700; color:var(--admin-text); display:block; }
        .admin-user-meta .u-role{ font-size:.72rem; color:var(--admin-muted); }

        @media (max-width: 575.98px){
            .admin-user-meta{ display:none; }
        }

        /* Content wrapper */
        .admin-content-wrap{
            padding:1.75rem;
            max-width:1400px;
            width:100%;
        }
        @media (max-width: 767.98px){
            .admin-content-wrap{ padding:1.1rem; }
        }

        /* ============================================================
           GLOBAL COMPONENT SYSTEM (applies across all admin pages)
        ============================================================ */

        /* Cards */
        .card{
            border:1px solid var(--admin-border);
            border-radius:var(--admin-radius-lg);
            box-shadow:var(--admin-shadow);
        }
        .card-header{
            background:transparent;
            border-bottom:1px solid var(--admin-border);
            font-weight:700;
            padding:1rem 1.25rem;
        }
        .card-body{ padding:1.25rem; }

        /* Buttons */
        .btn{
            border-radius:var(--admin-radius-sm);
            font-weight:600;
            font-size:.875rem;
            padding:.5rem 1rem;
            transition:transform .12s ease, box-shadow .12s ease, filter .12s ease;
        }
        .btn:hover{ transform:translateY(-1px); }
        .btn-sm{ border-radius:8px; padding:.32rem .65rem; font-size:.8rem; }
        .btn-primary{ background:var(--admin-accent); border-color:var(--admin-accent); }
        .btn-primary:hover{ background:var(--admin-accent-hover); border-color:var(--admin-accent-hover); }
        .btn-success{ background:var(--admin-accent); border-color:var(--admin-accent); }
        .btn-success:hover{ background:var(--admin-accent-hover); border-color:var(--admin-accent-hover); }
        .btn-outline-primary{ color:var(--admin-accent); border-color:var(--admin-accent); }
        .btn-outline-primary:hover{ background:var(--admin-accent); border-color:var(--admin-accent); }
        .btn-danger{ background:var(--admin-danger); border-color:var(--admin-danger); }
        .btn-secondary{ background:#f1f3f2; border-color:var(--admin-border); color:var(--admin-text); }
        .btn-secondary:hover{ background:#e7eae8; color:var(--admin-text); }

        .btn-simaksi-primary{background:var(--admin-accent); border:0; font-weight:700; border-radius:var(--admin-radius-sm); color:#fff;}
        .btn-simaksi-primary:hover{background:var(--admin-accent-hover); color:#fff;}
        .btn-simaksi-secondary{background:var(--admin-accent-soft); color:var(--admin-accent); border:1px solid rgba(21,128,61,.25); font-weight:700; border-radius:var(--admin-radius-sm);}
        .btn-simaksi-secondary:hover{background:rgba(21,128,61,.22);}

        /* Forms */
        .form-label{ font-weight:600; font-size:.85rem; color:var(--admin-text); margin-bottom:.4rem; }
        .form-control, .form-select{
            border-radius:var(--admin-radius-sm);
            border:1px solid var(--admin-border);
            padding:.55rem .8rem;
            font-size:.9rem;
            min-height:42px;
        }
        .form-control:focus, .form-select:focus{
            border-color:var(--admin-accent);
            box-shadow:0 0 0 3px var(--admin-accent-soft);
        }
        .form-control.is-invalid, .form-select.is-invalid{
            border-color:#dc2626;
            box-shadow:0 0 0 3px rgba(220,38,38,.12);
        }
        .invalid-feedback.dynamic-feedback{ display:block; font-size:.82rem; margin-top:.35rem; }
        textarea.form-control{ min-height:auto; }

        .alert{
            border:1px solid transparent;
            border-radius:var(--admin-radius-md);
            font-size:.9rem;
            padding:.85rem 1.1rem;
        }
        .alert-success{ background:#ecfdf3; color:#166534; border-color:#bbf0cf; }
        .alert-danger{ background:#fef2f2; color:#b91c1c; border-color:#fecaca; }
        .alert-warning{ background:#fffbeb; color:#92400e; border-color:#fde68a; }
        .alert-info{ background:#f0f9ff; color:#075985; border-color:#bae6fd; }

        /* Tables */
        .table{
            margin-bottom:0;
            font-size:.875rem;
        }
        .table thead th{
            background:#fafbfa;
            color:var(--admin-muted);
            font-size:.72rem;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:.04em;
            border-bottom:1px solid var(--admin-border);
            padding:.85rem 1rem;
            white-space:nowrap;
        }
        .table tbody td{
            padding:.85rem 1rem;
            vertical-align:middle;
            border-bottom:1px solid var(--admin-border);
            color:var(--admin-text);
        }
        .table-striped tbody tr:nth-of-type(odd) td{
            background:#fafcfb;
        }
        .table-hover tbody tr:hover td{
            background:var(--admin-accent-soft);
        }
        .table-bordered, .table-bordered th, .table-bordered td{ border-color:var(--admin-border); }

        /* Badges (Bootstrap 4-style class names used across admin views, restyled for BS5) */
        .badge{ font-weight:700; border-radius:999px; padding:.4em .75em; font-size:.72rem; letter-spacing:.02em; }
        .badge-primary{ background:var(--admin-accent-soft); color:var(--admin-accent); }
        .badge-secondary{ background:#eef0ef; color:#4b544e; }
        .badge-success{ background:#dcfce7; color:#15803d; }
        .badge-danger{ background:#fee2e2; color:#b91c1c; }
        .badge-warning{ background:#fef3c7; color:#92400e; }
        .badge-info{ background:#e0f2fe; color:#075985; }

        /* Stat cards (used on dashboard) */
        .stat-card{
            background:var(--admin-card);
            border:1px solid var(--admin-border);
            border-radius:var(--admin-radius-lg);
            box-shadow:var(--admin-shadow);
            padding:1.25rem;
            display:flex;
            align-items:center;
            gap:1rem;
            height:100%;
            transition:box-shadow .18s ease, transform .18s ease;
        }
        .stat-card:hover{ box-shadow:var(--admin-shadow-md); transform:translateY(-2px); }
        .stat-icon{
            width:48px;
            height:48px;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:1.1rem;
            flex-shrink:0;
        }
        .stat-icon-green{ background:#dcfce7; color:#15803d; }
        .stat-icon-blue{ background:#dbeafe; color:#1d4ed8; }
        .stat-icon-amber{ background:#fef3c7; color:#b45309; }
        .stat-icon-sky{ background:#e0f2fe; color:#0369a1; }
        .stat-icon-slate{ background:#e2e8f0; color:#334155; }
        .stat-info{ min-width:0; display:flex; flex-direction:column; gap:.15rem; }
        .stat-label{ font-size:.78rem; color:var(--admin-muted); font-weight:600; }
        .stat-value{ font-size:1.5rem; font-weight:800; color:var(--admin-text); line-height:1.1; }

        /* Page section title used inside content (optional helper) */
        .page-heading{ margin-bottom:1.5rem; }
        .page-heading h1{ font-size:1.35rem; margin-bottom:.15rem; }
        .page-heading p{ color:var(--admin-muted); font-size:.88rem; margin-bottom:0; }

    </style>

    {{-- Tema bersama, font, ikon (FA 6.5) & animasi — dimuat setelah style admin agar token tema berlaku --}}
    @include('partials.ui.head')
</head>
<body class="ui admin-body is-app">
    @include('partials.ui.body-start')
    @include('partials.ui.app-bg')
    <div class="admin-shell">

        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- ================= SIDEBAR ================= -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon"><i class="fas fa-mountain"></i></div>
                <div class="sidebar-brand-text">
                    <span class="name">SIMAKSI</span>
                    <span class="sub">Admin Panel</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-label">Menu Utama</div>
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-gauge-high"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.gunung.*') ? 'active' : '' }}" href="{{ route('admin.gunung.index') }}">
                    <i class="fas fa-mountain"></i> Manajemen Gunung
                </a>
                <a class="nav-link {{ request()->routeIs('admin.rute-pendakian.*') ? 'active' : '' }}" href="{{ route('admin.rute-pendakian.index') }}">
                    <i class="fas fa-route"></i> Manajemen Rute
                </a>
                <a class="nav-link {{ request()->routeIs('admin.pendaftaran.*') ? 'active' : '' }}" href="{{ route('admin.pendaftaran.index') }}">
                    <i class="fas fa-clipboard-list"></i> Manajemen Pendaftaran
                </a>
                {{-- Menu Manajemen Pembayaran dihilangkan sesuai permintaan --}}
                {{-- <a class="nav-link" href="{{ route('admin.pembayaran.index') }}"><i class="fas fa-credit-card"></i> Manajemen Pembayaran</a> --}}
                {{-- Menu Verifikasi Peminjaman dihilangkan sesuai permintaan --}}
                {{-- <a class="nav-link" href="{{ route('admin.peminjaman.index') }}"><i class="fas fa-tools"></i> Verifikasi Peminjaman</a> --}}
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> Manajemen User
                </a>
                {{-- Menu Kembali ke User dihilangkan sesuai permintaan --}}
                {{-- <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-arrow-left"></i> Kembali ke User</a> --}}
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" data-confirm-message="Yakin ingin logout dari panel admin?" data-confirm-title="Konfirmasi Logout" data-confirm-ok="Ya, logout">
                    @csrf
                    <button type="submit" class="btn w-100 text-start">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- ================= MAIN ================= -->
        <div class="admin-main">

            <!-- Topbar -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="sidebar-toggle-btn" id="sidebarToggleBtn" type="button" aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    @php
                        $__pageTitle = trim(str_replace('- Admin', '', $__env->yieldContent('title', 'Dashboard')));
                        $__pageTitle = $__pageTitle !== '' ? $__pageTitle : 'Dashboard';
                    @endphp
                    <div class="breadcrumb-simaksi">
                        <span class="crumb-trail">Admin / {{ $__pageTitle }}</span>
                        <span class="crumb-title">{{ $__pageTitle }}</span>
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="admin-user-pill">
                        <div class="admin-user-avatar">{{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'A', 0, 1)) }}</div>
                        <div class="admin-user-meta">
                            <span class="u-name">{{ Auth::user()->nama_lengkap }}</span>
                            <span class="u-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="admin-content-wrap" id="main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        window.notify = function(type, title, text, options = {}) {
            const base = { icon: type, title: title || '', text: text || '', confirmButtonColor: '#28a745' };
            return Swal.fire(Object.assign(base, options));
        };
        window.toast = function(type, title, timer = 2600) {
            return Swal.fire({ toast: true, position: 'top-end', icon: type, title: title, showConfirmButton: false, timer: timer, timerProgressBar: true });
        };
        (function(){
            const originalAlert = window.alert;
            window.alert = function(message){
                try {
                    const msg = String(message || '');
                    const lower = msg.toLowerCase();
                    let icon = 'info';
                    if (lower.includes('berhasil') || lower.includes('success')) icon = 'success';
                    else if (lower.includes('gagal') || lower.includes('error') || lower.includes('kesalahan')) icon = 'error';
                    else if (lower.includes('tutup') || lower.includes('warning') || lower.includes('peringatan')) icon = 'warning';
                    Swal.fire({ icon, title: msg, confirmButtonColor: '#28a745' });
                } catch(e) { originalAlert(message); }
            };
        })();
    </script>

    <script>
        // Sidebar toggle (mobile off-canvas)
        (function(){
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const backdrop = document.getElementById('sidebarBackdrop');

            function openSidebar(){ sidebar.classList.add('show'); backdrop.classList.add('show'); }
            function closeSidebar(){ sidebar.classList.remove('show'); backdrop.classList.remove('show'); }

            if (toggleBtn){
                toggleBtn.addEventListener('click', function(){
                    sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
                });
            }
            if (backdrop){
                backdrop.addEventListener('click', closeSidebar);
            }
        })();
    </script>
    <script>
        (function () {
            function normalizeSpaces(value) {
                return String(value || '').replace(/\s+/g, ' ').trim();
            }

            function setInvalid(el, message) {
                if (!el) return;
                el.classList.add('is-invalid');
                el.setAttribute('aria-invalid', 'true');
                el.setCustomValidity(message || 'Input tidak valid');
                let feedback = el.parentElement ? el.parentElement.querySelector(`.dynamic-feedback[data-for="${el.name || el.id || ''}"]`) : null;
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback dynamic-feedback';
                    feedback.dataset.for = el.name || el.id || '';
                    el.insertAdjacentElement('afterend', feedback);
                }
                feedback.textContent = message || 'Input tidak valid';
            }

            function clearInvalid(el) {
                if (!el) return;
                el.classList.remove('is-invalid');
                el.removeAttribute('aria-invalid');
                el.setCustomValidity('');
                const feedback = el.parentElement ? el.parentElement.querySelector(`.dynamic-feedback[data-for="${el.name || el.id || ''}"]`) : null;
                if (feedback) feedback.remove();
            }

            function validateName(el) {
                const raw = String(el.value || '');
                const cleaned = raw.replace(/[^A-Za-z0-9\s'.,-]/g, '').replace(/\s{2,}/g, ' ');
                if (cleaned !== raw) el.value = cleaned;
                const value = normalizeSpaces(el.value);
                el.value = value;
                if (!value) return setInvalid(el, 'Field ini wajib diisi.'), false;
                if (value.length < 3) return setInvalid(el, 'Minimal 3 karakter.'), false;
                if (/^\d+$/.test(value)) return setInvalid(el, 'Tidak boleh hanya angka.'), false;
                clearInvalid(el);
                return true;
            }

            function validateEmail(el) {
                const value = String(el.value || '').replace(/\s+/g, '');
                el.value = value;
                if (!value) return setInvalid(el, 'Email wajib diisi.'), false;
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return setInvalid(el, 'Format email tidak valid.'), false;
                clearInvalid(el);
                return true;
            }

            function validateNumber(el, allowZero = true, max = null) {
                const digits = String(el.value || '').replace(/[^\d.-]/g, '');
                if (digits !== String(el.value || '')) el.value = digits;
                const value = Number(el.value);
                if (el.value === '') return setInvalid(el, 'Field ini wajib diisi.'), false;
                if (!Number.isFinite(value)) return setInvalid(el, 'Nilai harus berupa angka.'), false;
                if (value < 0) return setInvalid(el, 'Nilai tidak boleh negatif.'), false;
                if (!allowZero && value < 1) return setInvalid(el, 'Nilai minimal 1.'), false;
                if (max !== null && value > max) return setInvalid(el, `Nilai maksimal ${max}.`), false;
                clearInvalid(el);
                return true;
            }

            function validateFile(el) {
                if (!el.files || !el.files.length) return clearInvalid(el), true;
                const allowed = (el.dataset.allowedExt || '').split(',').map(v => v.trim().toLowerCase()).filter(Boolean);
                if (!allowed.length) return clearInvalid(el), true;
                const ext = (el.files[0].name.split('.').pop() || '').toLowerCase();
                if (!allowed.includes(ext)) return setInvalid(el, `File harus berformat: ${allowed.join(', ')}.`), false;
                clearInvalid(el);
                return true;
            }

            function validateField(el) {
                switch (el.dataset.validate) {
                    case 'name': return validateName(el);
                    case 'email': return validateEmail(el);
                    case 'price': return validateNumber(el, true, null);
                    case 'quantity': return validateNumber(el, false, null);
                    case 'age': return validateNumber(el, false, 100);
                    case 'file': return validateFile(el);
                    default: return true;
                }
            }

            document.addEventListener('input', function (e) {
                const el = e.target;
                if (!(el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement)) return;
                if (el.dataset.validate) validateField(el);
            });

            document.addEventListener('change', function (e) {
                const el = e.target;
                if (!(el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement || el instanceof HTMLSelectElement)) return;
                if (el.dataset.validate) validateField(el);
            });

            document.addEventListener('input', function (e) {
                const el = e.target;
                if (!(el instanceof HTMLInputElement)) return;
                if (el.type === 'search') {
                    el.value = normalizeSpaces(String(el.value || '').replace(/[<>{}[\]^`;$\\|]/g, ''));
                }
            });

            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;

                let invalid = false;
                form.querySelectorAll('[data-validate]').forEach((field) => {
                    if (!validateField(field) && !invalid) {
                        invalid = true;
                        field.focus();
                    }
                });
                if (invalid) {
                    e.preventDefault();
                    Swal.fire({ icon: 'warning', title: 'Periksa kembali input yang masih tidak valid.', confirmButtonColor: '#28a745' });
                    return;
                }

                if (form.dataset.confirmed === 'true') {
                    delete form.dataset.confirmed;
                    return;
                }

                const confirmMessage = form.dataset.confirmMessage;
                if (!confirmMessage) return;

                e.preventDefault();
                Swal.fire({
                    icon: form.dataset.confirmIcon || 'question',
                    title: form.dataset.confirmTitle || 'Konfirmasi',
                    text: confirmMessage,
                    showCancelButton: true,
                    confirmButtonText: form.dataset.confirmOk || 'Ya, lanjutkan',
                    cancelButtonText: form.dataset.confirmCancel || 'Batal',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        form.requestSubmit ? form.requestSubmit() : form.submit();
                    }
                });
            }, true);
        })();
    </script>

    {{-- Allow pages to push scripts --}}
    @stack('scripts')

    {{-- GSAP + animations.js --}}
    @include('partials.ui.scripts')
</body>
</html>
