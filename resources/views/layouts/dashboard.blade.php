<!DOCTYPE html>
<!--
 * Presspitch.io
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

    @if($featureService->isEnabled(\BADDIServices\SourceeApp\App::REPORT_BUGS_WITH_GLEAP_FEATURE))
    <script>
      !function(Gleap,t,i){if(!(Gleap=window.Gleap=window.Gleap||[]).invoked){for(window.GleapActions=[],Gleap.invoked=!0,Gleap.methods=["identify","clearIdentity","attachCustomData","setCustomData","removeCustomData","clearCustomData","registerCustomAction","logEvent","sendSilentCrashReport","startFeedbackFlow","setAppBuildNumber","setAppVersionCode","preFillForm","setApiUrl","setFrameUrl","isOpened","open","hide","on","setLanguage","setOfflineMode","initialize"],Gleap.f=function(e){return function(){var t=Array.prototype.slice.call(arguments);window.GleapActions.push({e:e,a:t})}},t=0;t<Gleap.methods.length;t++)Gleap[i=Gleap.methods[t]]=Gleap.f(i);Gleap.load=function(){var t=document.getElementsByTagName("head")[0],i=document.createElement("script");i.type="text/javascript",i.async=!0,i.src="https://js.gleap.io/latest/index.js",t.appendChild(i)},Gleap.load(),
          Gleap.initialize("erUGwHefPvAYu9v3IRRdgI9ao5GcC9zX"),

          Gleap.identify("{{ hash('sha256', $user->getId()) }}", {
            name: "{{ $user->getFirstName() }}",
            email: "{{ $user->getEmail() }}",
          })
      }}();
    </script>
    @endif
  </head>
  <body class="antialiased">
    <div class="wrapper">
      @include('partials.dashboard.menu')
      <div class="page-wrapper">
        @include('partials.dashboard.breadcrumb')
        <div class="page-body">
          <div class="container-xl">
            @include('partials.dashboard.alert')

            @yield('content')
          </div>
        </div>
        <footer class="footer footer-transparent d-print-none">
          <div class="container">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">&nbsp;</div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; {{ date('Y') }} All rights reserved
                  </li>
                  <li class="list-inline-item">
                    <a href="https://baddi.info" class="link-secondary" rel="noopener">v1.0.0</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>

      <button class="btn btn-clnkgo btn-icon back-top" aria-label="Back to top">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-up" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
          <polyline points="6 15 12 9 18 15"></polyline>
        </svg>
      </button>
    </div>

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('assets/js/tabler.min.js') }}"></script>
    @if (config('baddi.zendesk_key'))
    <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key={{ config('baddi.zendesk_key') }}"></script>
    @endif
    <script type="text/javascript">
    $(document).ready(function() {
      $(window).on('scroll', function() {
        var position = $(this).scrollTop();
        var bottom = $(this).height();

        if (position >= bottom) { 
          $('.back-top').fadeIn();
        } else { 
          $('.back-top').fadeOut();
        }
      });

      $('.back-top').click(function() {
          $('html, body').animate({scrollTop : 0}, 300);
      });
    });

    @yield('script')
    </script>
  </body>
</html>