<div class="page-header d-print-none">
  <div class="container-xl">
    <div class="row g-2 align-items-center">
      <div class="col">
        <h2 class="page-title">
          @yield('page-title', 'Dashboard')
        </h2>
      </div>
      <!-- Page title actions -->
      <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
          <span class="d-none d-sm-inline">
            <a href="#" class="btn">
              Benvenuto, {{ auth()->user()->name }}
            </a>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>