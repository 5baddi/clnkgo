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
                <div class="card-actions">
                    @include('dashboard.bookmark-button')
                </div>
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
                  @if($featureService->isEnabled(\BADDIServices\SourceeApp\App::EXTRACT_DUE_DATE_FEATURE) && $tweet->due_at && $tweet->due_at->greaterThan(now()))
                  <span title="Due on" style="margin-left: 2rem !important;">Due {{ $tweet->due_at->diffForHumans() }}</span>
                  @endif
                </div>
                @if($featureService->isEnabled(\BADDIServices\SourceeApp\App::MARK_AS_ANSWERED_FEATURE) && (! $answer || ! $answer->isAnswered()))
                <div class="card-actions">
                    <form action="{{ route('dashboard.requests.answered', ['id' => $tweet->getId()]) }}" method="POST">
                        @csrf
                        <button class="btn btn-default btn-xs" type="submit">
                            Response sent? Mark as answered&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checks" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M7 12l5 5l10 -10"></path>
                                <path d="M2 12l5 5m5 -5l5 -5"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @if($answer && $answer->isAnswered())
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
            <div class="card-footer d-flex justify-content-between">
                <div class="card-actions">
                    <form action="{{ route('dashboard.requests.unanswered', ['id' => $tweet->getId()]) }}" method="POST">
                        @csrf
                        <button class="btn btn-default btn-xs" type="submit">
                            Response not sent? Mark as unanswered&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-checks" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M7 12l5 5l10 -10"></path>
                                <path d="M2 12l5 5m5 -5l5 -5"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(! $answer || ! $answer->isAnswered())
    <div class="col-12 mt-4">
        <div class="card-tabs">
            <ul class="nav nav-tabs">
                <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
                    <a href="#direct" class="nav-link card-title active" data-bs-toggle="tab">Draft your Response</a>
                </li>
                <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
                    <a href="#mail" class="nav-link card-title" data-bs-toggle="tab">Send your response as an email</a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="direct" class="card tab-pane show active">
                    <form action="{{ route('dashboard.requests.dm', ['id' => $tweet->getId()]) }}" method="POST" target="_blank" onsubmit="window.location.reload();">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                @include('dashboard.requests.partials.content-form')
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
                    </form>
                </div>

                <div id="mail" class="card tab-pane">
                    <form action="{{ route('dashboard.requests.mail', ['id' => $tweet->getId()]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="content" id="mail-content"/>
                        <div class="card-body">
                            <div class="row">
                                @include('dashboard.requests.partials.content-form')

                                <div class="col-12 mt-2">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="subject" class="form-control @if($errors->has('subject')) is-invalid @endif" value="{{ old('subject', ! is_null($answer) ? $answer->subject : null) }}" placeholder="Subject"/>
                                    @if($errors->has('subject'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('subject') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="form-label">From</label>                        
                                    <select class="form-select @if($errors->has('from')) is-invalid @endif" id="emails">
                                        <option value="{{ $user->email }}" @if(! old('from')) selected @endif>{{ $user->email }}</option>
            
                                        @foreach ($emails as $email)
                                        <option value="{{ $email }}" @if(old('from') === $email) selected @endif>{{ $email }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('from'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('from') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12 mt-2">
                                    <label class="form-label">{{ ($tweet->email ?? $tweet->author->email) ? 'Detected' : '' }} E-mail</label>
                                    <input type="email" name="email" class="form-control @if($errors->has('email')) is-invalid @endif" value="{{ old('email') ?? ($answer->email ?? ($tweet->email ?? $tweet->author->email)) }}" placeholder="Email address"/>
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
                                <button type="submit" class="btn btn-twitter ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <polyline points="3 7 12 13 21 7"></polyline>
                                    </svg>
                                    &nbsp;Send as an email
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-12 mt-4">
        <h2>Journalist's Bio</h2>
        <div class="card card-link">
            <div class="card-cover card-cover-blurred text-center">
              <span class="avatar avatar-xl avatar-thumb avatar-rounded" style="background-image: url({{ $tweet->author->profile_image_url ?? asset('assets/img/default_avatar.png') }})"></span>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="card-title mb-1">{{ $tweet->author->name }}</div>
                    <div class="text-muted">{{ '@' . $tweet->author->username }}</div>
                    <p class="mt-4">{{ $tweet->author->description }}</p>
                </div>
            </div>
            @if($tweet->author && ($tweet->author->location || $tweet->author->email))
            <div class="card-footer d-flex justify-content-between">
                <div class="d-flex align-items-center">
                    @if($tweet->author->location)
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="11" r="3"></circle>
                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                    </svg>&nbsp;
                    <span style="margin-right: 2rem !important;" title="Location">{{ $tweet->author->location }}</span>
                    @endif
                    @if($tweet->author->website)
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                        <line x1="3.6" y1="9" x2="20.4" y2="9"></line>
                        <line x1="3.6" y1="15" x2="20.4" y2="15"></line>
                        <path d="M11.5 3a17 17 0 0 0 0 18"></path>
                        <path d="M12.5 3a17 17 0 0 1 0 18"></path>
                    </svg>&nbsp;
                    <a style="margin-right: 2rem !important;" href="//{{ $tweet->author->website }}" target="_blank" title="Website">{{ $tweet->author->website }}</a>
                    @endif
                    @if($tweet->author->email)
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <polyline points="3 7 12 13 21 7"></polyline>
                    </svg>&nbsp;
                    <span style="margin-right: 2rem !important;" title="Email">{{ $tweet->author->email }}</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection
@section('script')
    $('document').ready(function() {
        $('#mail-content').val($('#direct-content').val());

        $('#direct-content').on('change', function (event) {
            $('#mail-content').val($('#direct-content').val());
        });

        var cannedResponses = document.getElementById('canned-responses');
        window.Choices && (new Choices(cannedResponses, {
            classNames: {
                containerInner: cannedResponses.className,
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
        
        var emails = document.getElementById('emails');
        window.Choices && (new Choices(emails, {
            classNames: {
                containerInner: emails.className,
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

        cannedResponses.addEventListener(
            'choice',
            function(event) {
                if (typeof event.detail.choice.value !== "undefined") {
                    $('#direct-content').val(event.detail.choice.value);
                    $('#mail-content').val(event.detail.choice.value);
                }
            },
            false,
        );
    });
@endsection