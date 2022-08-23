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
        <li class="nav-item {{ request()->routeIs(['admin.clients', 'admin.clients.*']) ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.clients') }}">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <circle cx="9" cy="7" r="4"></circle>
                  <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                  <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                  <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                </svg>
              </span>
              <span class="nav-link-title">Clients</span>
          </a>
        </li>
        <li class="nav-item {{ request()->routeIs(['admin.tweets', 'admin.tweets.*']) ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.tweets') }}">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-messages" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M21 14l-3 -3h-7a1 1 0 0 1 -1 -1v-6a1 1 0 0 1 1 -1h9a1 1 0 0 1 1 1v10"></path>
                  <path d="M14 15v2a1 1 0 0 1 -1 1h-7l-3 3v-10a1 1 0 0 1 1 -1h2"></path>
                </svg>
              </span>
              <span class="nav-link-title">Queries</span>
          </a>
        </li>
        <li class="nav-item {{ request()->routeIs(['admin.emails', 'admin.emails.*']) ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('admin.emails') }}">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mailbox" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M10 21v-6.5a3.5 3.5 0 0 0 -7 0v6.5h18v-6a4 4 0 0 0 -4 -4h-10.5"></path>
                  <path d="M12 11v-8h4l2 2l-2 2h-4"></path>
                  <path d="M6 15h1"></path>
                </svg>
              </span>
              <span class="nav-link-title">Emails</span>
          </a>
        </li>
      </ul>
      <div class="row mb-4">
          <div class="col-12">
              <div class="col-auto align-self-center mt-1 text-center">
                  <a href="{{ route('signout') }}" class="btn btn-clnkgo btn-icon w-100 mt-3">
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

<header class="navbar navbar-expand-md navbar-light d-none d-lg-flex d-print-none">
<div class="container-xl">
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="navbar-nav flex-row order-md-last">
    {{-- <div class="d-none d-md-flex">
      <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
          <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg>
          <span class="badge bg-red"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Last updates</h3>
            </div>
            <div class="list-group list-group-flush list-group-hoverable">
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-body d-block">Example 1</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      Change deprecated html tags to text decoration classes (#29604)
                    </div>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions">
                      <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    </a>
                  </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-body d-block">Example 2</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      justify-content:between ⇒ justify-content:space-between (#29734)
                    </div>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions show">
                      <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    </a>
                  </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-body d-block">Example 3</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      Update change-version.js (#29736)
                    </div>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions">
                      <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    </a>
                  </div>
                </div>
              </div>
              <div class="list-group-item">
                <div class="row align-items-center">
                  <div class="col-auto"><span class="status-dot status-dot-animated bg-green d-block"></span></div>
                  <div class="col text-truncate">
                    <a href="#" class="text-body d-block">Example 4</a>
                    <div class="d-block text-muted text-truncate mt-n1">
                      Regenerate package-lock.json (#29730)
                    </div>
                  </div>
                  <div class="col-auto">
                    <a href="#" class="list-group-item-actions">
                      <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> --}}
    <div class="nav-item dropdown">
      <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu" aria-expanded="false">
        <span class="avatar avatar-sm" style="background-image: url({{ $avatar }})"></span>
        <div class="d-none d-xl-block ps-2">
          <div>{{ $user->getFullName() }}</div>
          <div class="mt-1 small text-muted">{{ $user->isSuperAdmin() ? 'Super Admin' : 'Journalist' }}</div>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
        <a href="{{ route('dashboard.account') }}" class="dropdown-item {{ request()->routeIs('dashboard.account') ? 'active' : '' }}">Account</a>
        @if($user->isSuperAdmin() && ! request()->routeIs(['admin', 'admin.*']))
        <a href="{{ route('admin') }}" class="dropdown-item">Admin area</a>
        <div class="dropdown-divider"></div>
        @endif
        
        @if($featureService->isEnabled(\BADDIServices\ClnkGO\App::JOURNALIST_AREA_FEATURE))
        @if(! request()->routeIs(['dashboard', 'dashboard.*']))
        <a href="{{ route('dashboard') }}" class="dropdown-item">Source area</a>
        @elseif(! request()->routeIs(['journalist', 'journalist.*']))
        <a href="{{ route('journalist') }}" class="dropdown-item">Journalist area</a>
        @endif
        @endif
        <a href="{{ route('signout') }}" class="dropdown-item">Logout</a>
      </div>
    </div>
  </div>
  <div class="collapse navbar-collapse" id="navbar-menu">
    <ol class="breadcrumb breadcrumb-arrows" aria-label="breadcrumbs">
      <li class="breadcrumb-item">
        @if(request()->routeIs(['admin', 'admin.*']))
        <a href="{{ route('admin') }}">Admin</a>
        @endif
      </li>
      @foreach (request()->segments() as $key => $segment)
          @if ($segment === 'admin')
          @continue
          @endif
          @if(! is_numeric($segment) && (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $segment) !== 1))
          <li class="breadcrumb-item {{ (sizeof(request()->segments()) - 1) === ($key) ? 'active' : '' }}"><a href="{{ url((request()->routeIs(['admin', 'admin.*']) ? 'admin/' : 'dashboard/') . $segment) }}">{{ ucfirst($segment) }}</a></li>
          @endif
      @endforeach
    </ol>
  </div>
</div>
</header>