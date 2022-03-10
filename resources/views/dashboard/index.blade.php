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
    <div class="card mt-4">
      <div class="card-body">
        <form class="row" action="{{ route('dashboard') }}" method="GET">
          <div class="col-6">
            <div class="form-group mb-2">
              <label class="form-label">Search</label>
              <input type="text" name="term" value="{{ old('term') ?? $term }}" class="form-control @if ($errors->has('term')) is-invalid @endif" placeholder="Enter search term..."/>  
            </div>
            <span class="text-muted text-sm">Hit <kbd>ENTER</kbd> to search by term</span>
          </div>
          <div class="col-3 form-group">
            <label class="form-label">Sort by</label>
            <select name="sort" class="form-select @if ($errors->has('sort')) is-invalid @endif" placeholder="Sort by" id="sort-by" onchange="this.form.submit()">
              <option @if (old('sort') === 'oldest' || $sort === 'oldest') selected @endif value="oldest">Oldest</option>
              <option @if (old('sort') === 'newest' || $sort === 'newest' || is_null($sort)) selected @endif value="newest">Newest</option>
            </select>
          </div>
          <div class="col-3 form-group">
            <label class="form-label">Filter by</label>
            <select name="filter" class="form-select @if ($errors->has('filter')) is-invalid @endif" placeholder="Filter by" id="filter-by" onchange="this.form.submit()">
              <option selected value="-1">Choose a filter</option>
              <option @if (old('filter') === 'keyword' || $filter === 'keyword') selected @endif value="keyword">Keyword Match</option>
              <option @if (old('filter') === 'bookmarked' || $filter === 'bookmarked') selected @endif value="bookmarked">Bookmarked</option>
              <option @if (old('filter') === 'answered' || $filter === 'answered') selected @endif value="answered">Answered Requests</option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <div class="row row-cards mt-4">
      @if ($tweets->count() === 0)
      <div class="card">
        <div class="card-body">
          <div class="row text-center">
            <div class="col-12">
              <h4>You don't have any requests yet.<br/>today could be the day! 😉</h4>
            </div>
          </div>
        </div>
      </div>
      @endif
      @include('dashboard.paginate')
      <div class="custom-loader">Loading...</div>
    </div>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection

@section('script')
  $('document').ready(function() {
    var sortByEl = document.getElementById('sort-by');
    window.Choices && (new Choices(sortByEl, {
        classNames: {
            containerInner: sortByEl.className,
            input: 'form-control',
            inputCloned: 'form-control-sm',
            listDropdown: 'dropdown-menu',
            itemChoice: 'dropdown-item',
            activeState: 'show',
            selectedState: 'active',
        },
        shouldSort: false,
        searchEnabled: false,
    }));
    
    var filterByEl = document.getElementById('filter-by');
    window.Choices && (new Choices(filterByEl, {
        classNames: {
            containerInner: filterByEl.className,
            input: 'form-control',
            inputCloned: 'form-control-sm',
            listDropdown: 'dropdown-menu',
            itemChoice: 'dropdown-item',
            activeState: 'show',
            selectedState: 'active',
        },
        shouldSort: false,
        searchEnabled: false,
    }));

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