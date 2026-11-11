<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
    <span class="brand-text fw-bold">SIMAKSI Admin</span>
  </a>

  <div class="sidebar">
    <nav>
      <ul>
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.gunung.index') }}">Manajemen Gunung</a></li>
        <li><a href="{{ route('admin.rute-pendakian.index') }}">Manajemen Rute</a></li>
        <li><a href="{{ route('admin.pendaftaran.index') }}">Manajemen Pendaftaran</a></li>
        {{-- Hapus menu Manajemen Pembayaran --}}
        {{-- <li><a href="{{ route('admin.pembayaran.index') }}">Manajemen Pembayaran</a></li> --}}
        <li><a href="{{ route('admin.users.index') }}">Manajemen User</a></li>
        {{-- Hapus menu Kembali ke User --}}
        {{-- <li><a href="{{ route('user.dashboard') }}">Kembali ke User</a></li> --}}
      </ul>
    </nav>
  </div
</aside>