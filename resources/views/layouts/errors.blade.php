
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{ config('app.name') }} &mdash; @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.mini.png') }}"/>
    <!-- CSS files -->
    <link href="{{ asset('assets/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/tabler-flags.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/tabler-payments.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/tabler-vendors.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/daterangepicker.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/bootstrap-tagsinput.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/baddi.services.css') }}" rel="stylesheet"/>
  </head>
  <body class="border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
      <div class="container-tight py-4">
        <div class="empty">
          <div class="empty-img">
            @yield('image')
          </div>
          <p class="empty-title">@yield('heading')</p>
          <p class="empty-subtitle text-muted">
            @yield('message')
          </p>
          <div class="empty-action">
            <a href="{{ url(env('SAAS_URL')) }}" class="btn btn-clnkgo">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="5" y1="12" x2="19" y2="12" /><line x1="5" y1="12" x2="11" y2="18" /><line x1="5" y1="12" x2="11" y2="6" /></svg>
              Go home
            </a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>