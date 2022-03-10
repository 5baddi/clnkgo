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
                  <span title="Published at">{{ $tweet->published_at->format('d M - h:i A') }}</span>
                </div>
                @if($tweet->due_at)
                <span title="Due on">Due {{ $tweet->due_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Request marked as answered</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <p>Mark request as unanswered to send further correspondence.</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <strong>Response not sent? Mark as unanswered</strong>
                    </div>
                    <div class="col-auto ms-auto">
                        <label class="form-check form-switch m-0">
                            <input class="form-check-input position-static" type="checkbox" checked/>
                        </label>
                    </div>
                  </div>
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
                    <div class="col-12">
                        <label class="form-label required">Your response</label>
                        <textarea rows="5" maxlength="{{ \BADDIServices\SourceeApp\App::TWEET_CHARACTERS_LIMIT }}" name="content" class="form-control @if($errors->has('content')) is-invalid @endif" placeholder="Write your response here..." required>{{ old('content') }}</textarea>
                        @if($errors->has('content'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('content') }}
                            </div>
                        @endif
                        <p class="small text-muted mt-2">To send your response as a Direct Message via Twitter <strong>(make sure you are signed in)</strong></p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-twitter ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                            <path d="M21 3l-6.5 18a0.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a0.55 .55 0 0 1 0 -1l18 -6.5"></path>
                        </svg>
                        &nbsp;Send as a Direct Message
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Send your response as an email</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <label class="form-label">Detected email</label>
                        <input type="text" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ old('email') ?? ($tweet->email ?? $tweet->author->email) }}" placeholder="Email address"/>
                        <p class="small text-muted mt-2">💡 For most requests can identify if an email address exists. But occasionally it might need help, you can edit the address if needed.</p>
                        @if($errors->has('email'))
                            <div class="invalid-feedback d-block">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-secondary ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                            <polyline points="3 7 12 13 21 7"></polyline>
                        </svg>
                        &nbsp;Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection