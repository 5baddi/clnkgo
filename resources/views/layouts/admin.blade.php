<!DOCTYPE html>
<!--
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
-->
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
  <body class="antialiased">
    <div class="wrapper">
      @include('partials.admin.menu')
      <div class="page-wrapper mt-2">
        {{-- @include('partials.dashboard.breadcrumb') --}}
        <div class="page-body">
          <div class="container-xl mt-2">
            @include('partials.dashboard.alert')

            @yield('content')
          </div>
        </div>
        @include('partials.footer')
      </div>
    </div>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('assets/js/tabler.min.js') }}"></script>
    @if (config('baddi.zendesk_key'))
    <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key={{ config('baddi.zendesk_key') }}"></script>
    @endif
    <script type="text/javascript">
    @yield('script')
    </script>
  </body>
</html>