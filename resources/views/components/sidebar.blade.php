
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    {{-- <li>
      <span class="menu-title">DASHBOARD</span>
    </li> --}}
      <li class="nav-item">
          <a class="nav-link" href="{{ route('dashboard') }}">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('diagnosa') }}">
              <i class="ti-support menu-icon"></i>
              <span class="menu-title">Diagnosa</span>
          </a>
      </li> 
      <li class="nav-item">
        <a class="nav-link" href="{{ route('riwayat') }}">
            <i class="ti-support menu-icon"></i>
            <span class="menu-title">Riwayat Diagnosa</span>
        </a>
    </li>
    <bdr>
      @can('view_admin')
      {{-- <li>
        <span class="menu-title">MANAJEMEN DATA</span>
      </li> --}}
      <hr><li class="nav-item">
          <a class="nav-link" href="{{ route('admin.penyakit') }}">
              <i class="ti-heart-broken menu-icon"></i>
              <span class="menu-title">Data Penyakit</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.gejala') }}">
              <i class="ti-pulse menu-icon"></i>
              <span class="menu-title">Data Gejala</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.pertanyaan') }}">
              <i class="ti-help menu-icon"></i>
              <span class="menu-title">Data Pertanyaan</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{  route('admin.solusi') }}">
              <i class="ti-info menu-icon"></i>
              <span class="menu-title">Data Solusi</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{  route('admin.aturan') }}">
              <i class="ti-settings menu-icon"></i>
              <span class="menu-title">Data Aturan</span>
          </a>
      </li>
      <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.index') }}">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Data Pengguna</span>
          </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.datariwayat.show') }}">
            <i class="ti-support menu-icon"></i>
            <span class="menu-title">Data Riwayat Diagnosa</span>
        </a>
    </li>
      @endcan
      
  </ul>
</nav>
