@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<form action="{{ route('dashboard.responses.update', ['id' => $response->getId()]) }}" method="POST">
    @csrf
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit canned response</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mt-2">You have used <strong>{{ $count }}</strong> of <strong>10</strong> canned responses in your plan.</p>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label class="form-label required">Title</label>
                            <input type="text" name="title" class="form-control @if($errors->has('title')) is-invalid @endif" value="{{ old('title') ?? $response->getTitle() }}" placeholder="Title of canned response" autofocus  required/>
                            <p class="small text-muted mt-1">Give your Canned Response a name so it's easy to identify later on.</p>
                            @if($errors->has('title'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label">@lang('Content')</label>
                            <textarea rows="5" maxlength="{{ \BADDIServices\SourceeApp\App::TWEET_CHARACTERS_LIMIT }}" name="content" class="form-control @if($errors->has('content')) is-invalid @endif" placeholder="Canned response content" required>{{ old('content') ?? $response->getContent() }}</textarea>
                            <p class="small text-muted mt-1">The optimal length of a tweet — <strong>70</strong> to <strong>{{ \BADDIServices\SourceeApp\App::TWEET_CHARACTERS_LIMIT }}</strong> characters</p>
                            @if($errors->has('content'))
                                <div class="invalid-feedback d-block">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-12 text-end">
                        <div class="d-flex">
                            <button type="submit" class="btn btn-twitter ms-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                                    <circle cx="12" cy="14" r="2"></circle>
                                    <polyline points="14 4 14 8 8 8 8 4"></polyline>
                                </svg>
                                &nbsp;Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection