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
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-event" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                    <line x1="16" y1="3" x2="16" y2="7"></line>
                    <line x1="8" y1="3" x2="8" y2="7"></line>
                    <line x1="4" y1="11" x2="20" y2="11"></line>
                    <rect x="8" y="15" width="2" height="2"></rect>
                  </svg>&nbsp;
                  <span>{{ $tweet->published_at->format('d M - h:i A') }}</span>
                </div>
                @if($tweet->due_at)
                <span>Due {{ $tweet->due_at->diffForHumans() }}</span>
                @endif
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection