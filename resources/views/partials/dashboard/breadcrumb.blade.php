<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
      <div class="row align-items-center mb-4">
        <div class="col-md-10 d-flex justify-content-start">
          <!-- Page pre-title -->
          <div class="page-pretitle">
            &nbsp;
          </div>
          <h2 class="page-title">
              @yield('title')
          </h2>
          @if (! request()->routeIs(['dashboard', 'dashboard.*']) && ! request()->routeIs(['admin', 'admin.*']))
          <div class="mt-2">
            <ol class="breadcrumb breadcrumb-arrows" aria-label="breadcrumbs">
                <li class="breadcrumb-item">
                  @if(request()->routeIs(['admin', 'admin.*']))
                  <a href="{{ route('admin') }}">Admin</a>
                  @else
                  <a href="{{ route('dashboard') }}">Dashboard</a>
                  @endif
                </li>
                @foreach (request()->segments() as $key => $segment)
                    @if ($segment === 'dashboard')
                    @continue
                    @endif
                    @if(! is_numeric($segment) && (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $segment) !== 1))
                    <li class="breadcrumb-item {{ (sizeof(request()->segments()) - 1) === ($key) ? 'active' : '' }}"><a href="{{ url((request()->routeIs(['admin', 'admin.*']) ? 'admin/' : 'dashboard/') . $segment) }}">{{ ucfirst($segment) }}</a></li>
                    @endif
                @endforeach
              </ol>
          </div>
          @endif
        </div>
        <div class="col-md-2 d-flex justify-content-end">
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
              
              @if($featureService->isEnabled(\BADDIServices\SourceeApp\App::JOURNALIST_AREA_FEATURE))
              @if(! request()->routeIs(['dashboard', 'dashboard.*']))
              <a href="{{ route('dashboard') }}" class="dropdown-item">Source area</a>
              @elseif(! request()->routeIs(['journalist', 'journalist.*']))
              <a href="{{ route('journalist') }}" class="dropdown-item">Journalist area</a>
              @endif
              @endif

              @if(! $user->isSuperAdmin())
              <div class="dropdown-divider"></div>
              <a href="{{ route('dashboard.plan.upgrade') }}" class="dropdown-item {{ request()->routeIs('dashboard.plan.*') ? 'active' : '' }}">Upgrade</a>
              @endif
              <div class="dropdown-divider"></div>
              @if(! $user->isSuperAdmin())
              <a href="{{ env('SUPPORT_URL', '#') }}" class="dropdown-item">Support</a>
              @endif
              <a href="{{ route('signout') }}" class="dropdown-item">Logout</a>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>