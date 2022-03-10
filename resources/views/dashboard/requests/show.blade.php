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
                    <div class="col-@if($tweet->media->first())8 @else 12 @endif d-flex align-items-center">
                        <p style="line-height: 2rem;">{{ $tweet->getText() }}</p>
                    </div>
                    @if($tweet->media->first())
                    <div class="col-4">
                        <img class="w-100 h-100 object-cover" src="{{ $tweet->media->first()->type === 'photo' ? $tweet->media->first()->url : $tweet->media->first()->preview_image_url }}"/>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between" style="background-color: #fafbfc;">
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
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Draft your Response</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>The Writer may indicate a preferred communication method in their request. Either a Twitter Direct Message (DM) or an email.</p>
                        <p>Draft your response below and we will pre-populate a DM/Email for you when you click send. You will then have the chance to make any final changes before you submit.</p>
                        <p>You can find out more about the Writer to tailor your response in the 'Posted by' section below.</p>
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