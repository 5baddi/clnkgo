@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>Forget about having to search for the perfect query to pitch.</p>
                        <p>We do the searching for you, using your keywords and preferences to match you with opportunities that are just right for you.</p>
                        <p>Enter your keyword and we'll notified you every time query matches with your keyword.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Keyword Used: (<span id="tags-count">0</span> of {{ $max }})</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-2">
                            <input type="text" value="{{ $keywords ?? '' }}" id="tags-input" class="form-control @if ($errors->has('keywords')) is-invalid @endif" autofocus placeholder="Add your keywords"/>
                            @if ($errors->has('keywords'))
                            <div class="invalid-feedback">{{ $errors::first('keywords') }}</div>
                            @endif
                        </div>
                        <span class="text-muted text-sm" style="color: white !important;">Hit <kbd>ENTER</kbd> or <kbd>comma</kbd> to add a keyword</span>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="col-12 text-end">
                    <form class="d-flex" action="{{ route('dashboard.keywords.save') }}" method="POST">
                        @csrf
                        <input type="hidden" id="keywords" name="keywords"/>
                        <button type="submit" class="btn btn-twitter ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                                <circle cx="12" cy="14" r="2"></circle>
                                <polyline points="14 4 14 8 8 8 8 4"></polyline>
                            </svg>
                            &nbsp;Save
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Tips & tricks</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>Here are a few tips when adding you keywords:</p>
                        <ul>
                            <li>Make sure to add plurals.</li>
                            <li>If you get too many matches for a single word, try a phrase instead. For example rather than adding 'Director' enter 'Finance Director'.</li>
                        </ul>
                        <h4>Keyword Ideas:</h4>
                        <p>Don't just list your business expertise. Journalists will often be looking for sources in particular locations or with certain interests. Include these in your keyword list to maximise your exposure e.g.</p>
                        <ul>
                            <li>Add your local village, town, city, county etc.</li>
                            <li>Add your hobbies, family circumstances and things you have a passion for.</li>
                            <li>Regularly check new requests on the 'Queries' page for keyword ideas. </li>
                        </ul>
                        <p>Still struggling to come up with keywords? Have a look at our Word Cloud. 👇</p>
                        <h4 class="mt-2">Word Cloud</h4>
                        <p>We've analysed requests from the past 2 weeks and pulled the most frequently used words.</p>
                        <p>This word cloud is frequently refreshed so check back for more inspiration.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <div id="words-cloud">
                        @foreach ($hashtags as $hashtag)
                        <span data-weight="{{ rand(10, 42) }}">{{ $hashtag }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @include('partials.dashboard.scripts.form')
@endsection

@section('script')
    $('document').ready(function() {
        $('#tags-input').tagsinput({
            maxTags: {{ $max }},
            cancelConfirmKeysOnEmpty: false,
            trimValue: true,
            allowDuplicates: false,
        });

        var tags = $('#tags-input').tagsinput('items');
        $('#tags-count').text(tags.length || 0);

        $('#tags-input').on('itemAdded', function(event) {
            var tags = $('#tags-input').tagsinput('items');

            $('#tags-count').text(tags.length || 0);
            $('#keywords').val(tags.join(','));
        });
        
        $('#tags-input').on('itemRemoved', function(event) {
            var tags = $('#tags-input').tagsinput('items');

            $('#tags-count').text(tags.length || 0);
            $('#keywords').val(tags.join(','));
        });

        $('#words-cloud').awesomeCloud({
            'shape' : 'circle',
            'size' : {
                'grid' : 16,
                'factor': 0,
                'normalize': true
            },
            'color': {
                'start': '#202837',
                'end': '#311847'
            },
            'options': {
                'rotationRatio': 0.3,
                'printMultiplier': 3,
                'color': 'gradient',
                'sort': 'highest'
            }
        });
    });
@endsection
