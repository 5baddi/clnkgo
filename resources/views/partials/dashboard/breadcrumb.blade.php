<div class="container-xl">
    <!-- Page title -->
    <div class="page-header d-print-none">
      <div class="row align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <div class="page-pretitle">
            &nbsp;
          </div>
          <h2 class="page-title">
              @yield('title')
          </h2>
          @if (! request()->routeIs('dashboard'))
          <div class="mt-2">
            <ol class="breadcrumb breadcrumb-arrows" aria-label="breadcrumbs">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @foreach (request()->segments() as $key => $segment)
                    @if ($segment === 'dashboard')
                    @continue
                    @endif
                    @if(! is_numeric($segment) && (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $segment) !== 1))
                    <li class="breadcrumb-item {{ (sizeof(request()->segments()) - 1) === ($key) ? 'active' : '' }}"><a href="{{ url('dashboard/' . $segment) }}">{{ ucfirst($segment) }}</a></li>
                    @endif
                @endforeach
              </ol>
          </div>
          @endif
        </div>
    </div>
</div>