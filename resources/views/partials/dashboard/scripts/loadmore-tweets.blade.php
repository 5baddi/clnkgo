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

    function throttle(fn, wait) {
      var time = Date.now();
      return function() {
        if ((time + wait - Date.now()) < 0) {
          fn();
          time = Date.now();
        }
      }
    }

    @if($tweets->total() > 0)
    $(window).scroll(function(){
      var position = $(this).scrollTop();
      var bottom = $(document).height() - $(this).height();
      var lastPage = parseInt('{{ $tweets->lastPage() }}');

      if(position == bottom && page < lastPage){
        $('.custom-loader').css('display', 'block');
        ++page;

        throttle($.ajax({
          url: `{{ route('dashboard.paginate.tweets') }}?{{ count(Request()->query()) === 0 ? '' : http_build_query(Request()->query()) . '&' }}page=${page}`,
          type: 'get',
          success: function(response){
            $('.custom-loader').css('display', 'none');

            $(response).insertBefore('.custom-loader');
          },
          error: function (req, status, error) {
            $('.custom-loader').css('display', 'none');
          }
        }), 1000);
      } else {
        $('.custom-loader').css('display', 'none');
      }
    });
    @endif
  });
@endsection