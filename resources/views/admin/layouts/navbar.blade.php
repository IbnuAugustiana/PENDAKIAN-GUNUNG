<nav class="main-header navbar navbar-expand navbar-light bg-white border-bottom">
  <ul class="navbar-nav ms-auto me-3">
    <li class="nav-item">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger btn-sm">Logout</button>
      </form>
    </li>
  </ul>
</nav>
