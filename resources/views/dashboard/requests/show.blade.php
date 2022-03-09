@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Writer Request</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>{{ $tweet->getText() }}</p>
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection