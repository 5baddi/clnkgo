<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <h1 class="navbar-brand navbar-brand-autodark">
        <a href="{{ route('dashboard') }}">
          <img src="{{ asset('assets/img/logo-white.png') }}" width="110" height="32" alt="{{ config('app.name') }}" class="navbar-brand-image"/>
        </a>
      </h1>
      <div class="collapse navbar-collapse" id="navbar-menu">
        <ul class="navbar-nav pt-lg-3">
          <li class="nav-item {{ request()->routeIs('admin') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-bar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <rect x="3" y="12" width="6" height="8" rx="1"></rect>
                        <rect x="9" y="8" width="6" height="12" rx="1"></rect>
                        <rect x="15" y="4" width="6" height="16" rx="1"></rect>
                        <line x1="4" y1="20" x2="18" y2="20"></line>
                    </svg>
                </span>
                <span class="nav-link-title">Analytics</span>
            </a>
          </li>
        </ul>
        <div class="row mb-4">
            <div class="col-12">
                <div class="col-auto align-self-center mt-1 text-center">
                    <a href="{{ route('signout') }}" class="btn btn-icon w-100 mt-3">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-logout" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
                        <path d="M7 12h14l-3 -3m0 6l3 -3"></path>
                      </svg>
                      &nbsp;Logout
                    </a>
                </div>
            </div>
        </div>
      </div>
    </div>
</aside>