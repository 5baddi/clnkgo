@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
    <div class="row row-deck row-cards">
        <div class="col-sm-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Live Requests</div>
                </div>
                <div class="h1 mt-3 mb-3 text-green text-center">{{ $liveRequests }}</div>
              </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Last 24 Hours</div>
                </div>
                <div class="h1 mt-3 mb-3 text-center">{{ $last24hRequests }}</div>
              </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="subheader">Keyword Match</div>
                </div>
                <div class="h1 mt-3 mb-3 text-center">{{ $keywordMatch }}</div>
              </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
      <div class="card-body">
        <form id="search-form" class="row" action="{{ route('dashboard') }}" method="GET">
          <div class="col-6">
            <div class="form-group mb-2">
              <label class="form-label">Search</label>
              <input id="term" type="text" name="term" value="{{ old('term') ?? $term }}" class="form-control @if ($errors->has('term')) is-invalid @endif" placeholder="Enter search term..." onblur="this.form.submit()"/>  
            </div>
            <span class="text-muted text-sm">Hit <kbd>ENTER</kbd> to search by term</span>
          </div>
          <div class="col-3 form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-select @if ($errors->has('category')) is-invalid @endif" placeholder="Category" id="category" onchange="this.form.submit()">
              <option @if (old('category') === 'all' || $category === 'all' || is_null($sort)) selected @endif value="all">All</option>
              <option @if (old('category') === 'health,psychology,mental,illness,disease,doctor' || $category === 'health,psychology,mental,illness,disease,doctor') selected @endif value="health,psychology,mental,illness,disease,doctor">Health</option>
              <option @if (old('category') === 'tech,technology' || $category === 'tech,technology') selected @endif value="tech,technology">Tech</option>
              <option @if (old('category') === 'business' || $category === 'business') selected @endif value="business">Business</option>
              <option @if (old('category') === 'fashion' || $category === 'fashion') selected @endif value="fashion">Fashion</option>
              <option @if (old('category') === 'sports' || $category === 'sports') selected @endif value="sports">Sports</option>
              <option @if (old('category') === 'uk,united kingdom' || $category === 'uk, united kingdom') selected @endif value="uk,united kingdom">United Kingdom</option>
              <option @if (old('category') === 'finance' || $category === 'finance') selected @endif value="finance">Finance</option>
              <option @if (old('category') === 'travel' || $category === 'travel') selected @endif value="travel">Travel</option>
              <option @if (old('category') === 'news' || $category === 'news') selected @endif value="news">News</option>
              <option @if (old('category') === 'podcast' || $category === 'podcast') selected @endif value="podcast">Podcast</option>
            </select>
          </div>
          <div class="col-3 form-group">
            <label class="form-label">Sort by</label>
            <select name="sort" class="form-select @if ($errors->has('sort')) is-invalid @endif" placeholder="Sort by" id="sort-by" onchange="this.form.submit()">
              <option @if (old('sort') === 'published_at' || $sort === 'published_at') selected @endif value="published_at">Oldest</option>
              <option @if (old('sort') === '-published_at' || $sort === '-published_at' || is_null($sort)) selected @endif value="-published_at">Newest</option>
            </select>
          </div>
          {{-- <div class="col-2 form-group">
            <label class="form-label">Filter by</label>
            <select name="filter" class="form-select @if ($errors->has('filter')) is-invalid @endif" placeholder="Filter by" id="filter-by" onchange="this.form.submit()">
              <option selected value="-1">Choose a filter</option>
              <option @if (old('filter') === 'keyword' || $filter === 'keyword') selected @endif value="keyword">Keyword Match</option>
              <option @if (old('filter') === 'bookmarked' || $filter === 'bookmarked') selected @endif value="bookmarked">Saved Queries</option>
              <option @if (old('filter') === 'answered' || $filter === 'answered') selected @endif value="answered">Answered Requests</option>
            </select>
          </div> --}}
        </form>
      </div>
    </div>
    <div class="row row-cards mt-4">
      @if ($tweets->count() === 0)
      <div class="card">
        <div class="card-body">
          <div class="row text-center">
            <div class="col-12">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-empty" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                <circle cx="12" cy="12" r="9"></circle>
                <line x1="9" y1="10" x2="9.01" y2="10"></line>
                <line x1="15" y1="10" x2="15.01" y2="10"></line>
                <line x1="9" y1="15" x2="15" y2="15"></line>
              </svg>
              <h4>You don't have any requests yet.<br/>today could be the day!</h4>
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
    var categoryEl = document.getElementById('category');
    window.Choices && (new Choices(categoryEl, {
        classNames: {
            containerInner: categoryEl.className,
            input: 'form-control',
            inputCloned: 'form-control-sm',
            listDropdown: 'dropdown-menu',
            itemChoice: 'dropdown-item',
            activeState: 'show',
            selectedState: 'active',
        },
        shouldSort: true,
        searchEnabled: true,
    }));
    
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
    
    {{-- var filterByEl = document.getElementById('filter-by');
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
    })); --}}

    $("#term").keyup(function(event) {
      if (event.keyCode === 13) {
          $("#search-form").submit();
      }
    });

    var page = 1;

    $(window).scroll(function(){
      var position = $(this).scrollTop();
      var bottom = $(document).height() - $(this).height();
      var lastPage = parseInt('{{ $tweets->lastPage() }}');

      if(position == bottom && page < lastPage){
        $('.custom-loader').css('display', 'block');
        ++page;

        $.ajax({
          url: `{{ route('dashboard.paginate.tweets') }}?{{ count(Request()->query()) === 0 ? '' : http_build_query(Request()->query()) . '&' }}page=${page}`,
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