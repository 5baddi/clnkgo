@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
    <div class="row align-items-center pt-2 pb-4">
        <div class="col-auto ms-auto d-print-none">
          <form id="periodForm" action="{{ route('dashboard.filtered') }}" method="POST">
            @csrf
            <input type="hidden" id="startDate" name="start-date"/>
            <input type="hidden" id="endDate" name="end-date"/>
          </form>
          <div class="input-icon mb-2">
            <input type="text" id="period" name="period" class="form-control " placeholder="Select a date"/>
            <span class="input-icon-addon">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><rect x="4" y="5" width="16" height="16" rx="2"></rect><line x1="16" y1="3" x2="16" y2="7"></line><line x1="8" y1="3" x2="8" y2="7"></line><line x1="4" y1="11" x2="20" y2="11"></line><line x1="11" y1="15" x2="12" y2="15"></line><line x1="12" y1="15" x2="12" y2="18"></line></svg>
            </span>
          </div>
          {{-- <input type="text" id="period"/> --}}
            {{-- <div class="btn-list">
                <div class="ms-auto lh-1">
                    <div class="dropdown">
                        <a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last {{ $period ? $period : '7 days' }}</a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item {{ ($period === '7 days' || !$period) ? 'active' : '' }}" href="{{ route('dashboard', ['period' => '7_days']) }}">Last 7 days</a>
                            <a class="dropdown-item {{ $period === '30 days' ? 'active' : '' }}" href="{{ route('dashboard', ['period' => '30_days']) }}">Last 30 days</a>
                            <a class="dropdown-item {{ $period === 'current year' ? 'active' : '' }}" href="{{ route('dashboard', ['period' => 'current_year']) }}">Current year</a>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="row row-deck row-cards">
        <div class="col-sm-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Live Requests</div>
                </div>
                <div class="h1 mt-3 mb-3 text-green text-center">{{ $ordersEarnings }}</div>
              </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Last 24 Hours</div>
                </div>
                <div class="h1 mt-3 mb-3 text-center">{{ $paidOrdersCommissions }}</div>
              </div>
            </div>
        </div>
    </div>
    <div class="row row-cards mt-4">
      @foreach ($tweets as $tweet)
        <div class="col-12">
          <a class="card card-link" href="#">
            <div class="card-body">
              <div class="row">
                <div class="col-auto">
                  <span class="avatar rounded" style="background-image: url({{ $tweet->author->profile_image_url }})"></span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ $tweet->author->name }}</div>
                  <div class="text-muted">{{ '@' . $tweet->author->username }}</div>
                </div>
                <div class="col-12 mt-4 mb-2">
                  <p>{{ $tweet->text }}</p>
                </div>
              </div>
              <div class="card-meta d-flex justify-content-between">
                <div class="d-flex align-items-center">
                  <!-- Download SVG icon from http://tabler-icons.io/i/check -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l5 5l10 -10"></path></svg>
                  <span>Posted: {{ $tweet->published_at->format('d M - h:i A') }}</span>
                </div>
                <span>Due 2 days</span>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
@endsection

@section('scripts')
    @include('partials.dashboard.scripts.analytics')
@endsection

@section('script')
  document.addEventListener("DOMContentLoaded", function () {
    $('#period').daterangepicker({
      "startDate": "{{ $startDate }}",
      "endDate": "{{ $endDate }}",
      locale: {
        format: 'YYYY/MM/DD'
      }
    }); 

    $('#period').on('apply.daterangepicker', function(ev, picker) {
      $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
      $('#endDate').val(picker.endDate.format('YYYY-MM-DD'));
      $('#periodForm').submit();
    });

    window.ApexCharts && (new ApexCharts(document.getElementById('earnings-chart'), {
      chart: {
        type: "area",
        fontFamily: 'inherit',
        height: 340,
        parentHeightOffset: 0,
        stacked: true,
        redrawOnParentResize: true,
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
      },
      series: [{
        name: "Revenue",
        data: {!! json_encode($ordersEarningsChart) !!}
      }],
      markers: {
        size: 0
      },
      tooltip: {
        enabled: true,
        shared: true,
        followCursor: false,
        intersect: false,
        inverseOrder: false,
        fillSeriesColor: false,
      },
      grid: {
        row: {
            colors: ['#f3f3f3', 'transparent'],
            opacity: 0.5
        },
      },
      colors: ["#1d9bf0"],
      xaxis: {
        type: 'datetime',
        min: new Date("{{ $startDate }}").getTime(),
        max: new Date("{{ $endDate }}").getTime(),
        labels: {
          format: 'dd MMM',
          show: true,
          hideOverlappingLabels: true,
          showDuplicates: false,
        },
      },
    })).render();
  });
@endsection