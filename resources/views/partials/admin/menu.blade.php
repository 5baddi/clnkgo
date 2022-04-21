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
          <li class="nav-item {{ request()->routeIs('dashboard.keywords') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.keywords') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-key" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <circle cx="8" cy="15" r="4"></circle>
                    <line x1="10.85" y1="12.15" x2="19" y2="4"></line>
                    <line x1="18" y1="5" x2="20" y2="7"></line>
                    <line x1="15" y1="8" x2="17" y2="10"></line>
                  </svg>
                </span>
                <span class="nav-link-title">Keywords</span>
            </a>
          </li>
          <li class="nav-item {{ request()->routeIs(['dashboard.responses', 'dashboard.responses.*']) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.responses') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <polyline points="3 7 12 13 21 7"></polyline>
                  </svg>
                </span>
                <span class="nav-link-title">Templates</span>
            </a>
          </li>
          <li class="nav-item {{ request()->routeIs('dashboard.plan.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.plan.upgrade') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-rocket" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3"></path>
                    <path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3"></path>
                    <circle cx="15" cy="9" r="1"></circle>
                  </svg>
                </span>
                <span class="nav-link-title">Upgrade</span>
            </a>
          </li>
          <li class="nav-item {{ request()->routeIs('dashboard.account') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard.account') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </span>
                <span class="nav-link-title">Account</span>
            </a>
          </li>
        </ul>
        <div class="row mb-4">
            <div class="col-12">
                <div class="col-auto align-self-center mt-1 text-center">
                    <a href="{{ env('SUPPORT_URL', '#') }}" target="_blank" class="btn btn-icon btn-without-bg w-100">
                      Report A Bug
                    </a>
                    <a href="{{ env('SUPPORT_URL', '#') }}" target="_blank" class="btn btn-icon btn-without-bg w-100">
                      Support
                    </a>
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