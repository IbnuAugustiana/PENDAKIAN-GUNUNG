<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMAKSI.COM')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
        }

        /* ================= NAVBAR ================= */
        .navbar {
            background: linear-gradient(90deg, #0e3d2c, #155c3b);
            padding: 15px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 999;
        }

        .navbar .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            font-weight: 700;
            font-size: 1.4rem;
        }

        .navbar-brand img {
            height: 50px;
            margin-right: 10px;
            border-radius: 10px;
        }

        .navbar-center {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .nav-list {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-link {
            text-decoration: none;
            color: #e8f5e9;
            font-weight: 500;
            transition: 0.3s;
            padding: 6px 12px;
            border-radius: 20px;
        }

        .nav-link:hover {
            color: #a8ffb0;
        }

        .nav-link.active {
            background: #28a745;
            color: white;
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .btn-login {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #1e7e34, #198754);
            color: white;
        }

        .btn-login.logout {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .btn-login.logout:hover {
            background: linear-gradient(135deg, #b02a37, #a71d2a);
        }

        /* ================= MAIN CONTENT ================= */
        main {
            padding: 40px;
            max-width: 1200px;
            margin: 100px auto 60px auto;
        }

        /* ================= FOOTER ================= */
        footer {
            background: #0e3d2c;
            color: #e8f5e9;
            text-align: center;
            padding: 20px 0;
        }

        @media (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar-center {
                margin-top: 10px;
            }

            .nav-list {
                flex-wrap: wrap;
                gap: 15px;
            }

            main {
                padding: 20px;
                margin-top: 120px;
            }
        }
    </style>
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">
        <div class="container">
            <!-- LEFT -->
            <div class="navbar-left">
                <a href="{{ url('/') }}" class="navbar-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                    SIMAKSI.COM
                </a>
            </div>

            <!-- CENTER -->
            <div class="navbar-center">
                <ul class="nav-list">
                    <li><a href="{{ route('landing') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('gunung.index') }}" class="nav-link {{ request()->is('gunung') ? 'active' : '' }}">Gunung</a></li>
                    <li><a href="{{ route('rute.index') }}" class="nav-link {{ request()->is('rute') ? 'active' : '' }}">Rute</a></li>
                    <li><a href="{{ route('panduan') }}" class="nav-link {{ request()->is('panduan') ? 'active' : '' }}">Panduan</a></li>
                    <li><a href="{{ route('tentang') }}" class="nav-link {{ request()->is('tentang') ? 'active' : '' }}">Tentang</a></li>
                    @auth
                        <li><a href="{{ route('pendaftaran.index') }}" class="nav-link {{ request()->is('pendaftaran') ? 'active' : '' }}"> Isi Formulir SIMAKSI</a></li>
                    @endauth
                </ul>
            </div>

            <!-- RIGHT -->
            <div class="navbar-right">
                @auth
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin logout?')">
                        @csrf
                        <button type="submit" class="btn-login logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ================= CONTENT ================= -->
    <main>
        @yield('content')
    </main>

    <!-- ================= FOOTER ================= -->
    <footer>
        &copy; {{ date('Y') }} SIMAKSI.COM — Jelajahi Alam Indonesia dengan Aman 🌲
    </footer>

</body>
</html>
