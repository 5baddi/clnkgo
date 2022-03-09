@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
    <div class="row row-deck row-cards">
        <div class="col-sm-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Live Requests</div>
                </div>
                <div class="h1 mt-3 mb-3 text-green text-center">{{ $liveRequests }}</div>
              </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Last 24 Hours</div>
                </div>
                <div class="h1 mt-3 mb-3 text-center">{{ $liveRequests }}</div>
              </div>
            </div>
        </div>
    </div>
    <div class="row row-cards mt-4">
      @include('dashboard.paginate')
      <div class="custom-loader">Loading...</div>
    </div>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection

@section('script')
  $('document').ready(function() {
    var page = 1;

    $(window).scroll(function(){
      var position = $(this).scrollTop();
      var bottom = $(document).height() - $(this).height();
      var lastPage = parseInt('{{ $tweets->lastPage() }}');

      if(position == bottom && page < lastPage){
        $('.custom-loader').css('display', 'block');
        ++page;

        $.ajax({
          url: `{{ route('dashboard.paginate.tweets') }}?page=${page}`,
          type: 'get',
          success: function(response){
            $('.custom-loader').css('display', 'none');

            $(response).insertBefore('.custom-loader');
          },
          error: function (req, status, error) {
            $('.custom-loader').css('display', 'none');
          }
        });
      } else {
        $('.custom-loader').css('display', 'none');
      }
    });
  });
@endsection