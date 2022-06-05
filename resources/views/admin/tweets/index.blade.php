@extends('layouts.admin')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="row row-cards">
    <div class="card-tabs">
        <ul class="nav nav-tabs" style="border-bottom: unset !important;">
            <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
                <a href="{{ route('admin.tweets', ['source' => 'twitter']) }}" class="nav-link card-title {{ $source === 'twitter' ? 'active' : '' }}">Twitter</a>
            </li>
            <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
                <a href="{{ route('admin.tweets', ['source' => 'internal']) }}" class="nav-link card-title {{ $source === 'password' ? 'active' : '' }}">Internal</a>
            </li>
        </ul>
    </div>
    <div class="col mt-0">
        @if($source === 'twitter')
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                  <thead>
                    <tr>
                      <th>Journalist</th>
                      <th>Username</th>
                      <th>Join at</th>
                      <th>Created at</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @if ($tweets->count() === 0)
                    <tr>
                        <td colspan="5" class="text-center text-xs text-muted">No data</td>
                    </tr>
                    @endif
                    @foreach ($tweets as $tweet)
                    <tr>
                        <td>{{ $tweet->author->name }}</td>
                        <td>
                            <a href="https://twitter.com/{{ $tweet->author->username }}" target="_blank">{{ '@' . $tweet->author->username }}</a>
                        </td>
                        <td>{{ $tweet->registered_at ? $tweet->registered_at->format('d/m/Y') : '---' }}</td>
                        <td>{{ $tweet->published_at->format('d/m/Y') }}</td>
                        <td align="center">
                            <a href="{{ route('dashboard.requests.show', ['id' => $tweet->getId()]) }}" target="_blank" class="btn btn-dark" title="Show query">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="12" cy="12" r="2"></circle>
                                    <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
            @if ($tweets->count() > 0)
            <div class="card-footer d-flex align-items-center">
                {!! $tweets->appends(request()->merge(['source' => $source])->query())->links('partials.admin.paginator') !!}
            </div>
            @endif
        </div>
        @endif
        @if($source === 'internal')
        <div class="card">
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                  <thead>
                    <tr>
                      <th>Journalist</th>
                      <th>Username</th>
                      <th>Join at</th>
                      <th>Queries</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @if ($tweets->count() === 0)
                    <tr>
                        <td colspan="5" class="text-center text-xs text-muted">No data</td>
                    </tr>
                    @endif
                    @foreach ($tweets as $tweet)
                    <tr>

                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>
            @if ($tweets->count() > 0)
            <div class="card-footer d-flex align-items-center">
                {!! $tweets->appends(request()->merge(['source' => $source])->query())->links('partials.admin.paginator') !!}
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection