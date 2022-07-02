@extends('layouts.dashboard')

@section('title')
    {{ ucfirst($title) }}
@endsection

@section('content')
<div class="row row-cards">
    <div class="col-8 d-flex">
        <div class="col-12 card">
            <div class="card-header" style="border: none;">
                <div class="card-actions">
                    @include('dashboard.bookmark-button')
                </div>
            </div>
            <div class="card-body">
                <div class="row h-100">
                    <div class="col-{{ $tweet->media->first() ? '8' : '12' }} d-flex justify-content-center align-self-center">
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
                  @if($featureService->isEnabled(\BADDIServices\ClnkGO\App::EXTRACT_DUE_DATE_FEATURE) && $tweet->due_at && $tweet->due_at->greaterThan(now()))
                  <span title="Due on" style="margin-left: 2rem !important;">Due {{ $tweet->due_at->diffForHumans() }}</span>
                  @endif
                </div>
                <div class="card-actions">
                    <a class="btn btn-clnkgo btn-xs" href="{{ route('dashboard', ['author' => $tweet->getAuthorId()]) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-dots-circle-horizontal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <line x1="8" y1="12" x2="8" y2="12.01"></line>
                            <line x1="12" y1="12" x2="12" y2="12.01"></line>
                            <line x1="16" y1="12" x2="16" y2="12.01"></line>
                        </svg>
                        &nbsp;Show more queries
                    </a>

                    @if($featureService->isEnabled(\BADDIServices\ClnkGO\App::MARK_AS_ANSWERED_FEATURE) && (! $answer || ! $answer->isAnswered()))
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
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-4 d-flex">
        <div class="col-12 card card-link">
            <div class="card-cover card-cover-blurred text-center"
                @if($tweet->author->profile_banner_url)
                style="background-image: url({{ $tweet->author->profile_banner_url }})"
                @endif
                >
              <span class="avatar avatar-xl avatar-thumb avatar-rounded" style="background-image: url({{ $tweet->author->profile_image_url ?? asset('assets/img/default_avatar.png') }})"></span>
            </div>
            <div class="card-body text-center">
                <div class="row">
                    <div class="card-title mb-1">{{ $tweet->author->name }}</div>
                    <a href="{{ route('dashboard.requests.redirect.to-profile', ['username' => $tweet->author->username]) }}" target="_blank" class="text-muted">{{ '@' . $tweet->author->username }}</a>
                    <p class="mt-2">{{ $tweet->author->description }}</p>
                </div>
            </div>
            @if($tweet->author && ($tweet->author->location || $tweet->author->email))
            <div class="card-footer">
                @if($tweet->author->location)
                <div class="mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="11" r="3"></circle>
                        <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"></path>
                    </svg>&nbsp;
                    <span title="Location">{{ $tweet->author->location }}</span>
                </div>
                @endif
                @if($tweet->author->website)
                <div class="mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-world" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                        <line x1="3.6" y1="9" x2="20.4" y2="9"></line>
                        <line x1="3.6" y1="15" x2="20.4" y2="15"></line>
                        <path d="M11.5 3a17 17 0 0 0 0 18"></path>
                        <path d="M12.5 3a17 17 0 0 1 0 18"></path>
                    </svg>&nbsp;
                    <a href="{{ route('dashboard.requests.redirect', ['url' => $tweet->author->website]) }}" target="_blank" title="Website">{{ $tweet->author->website }}</a>
                </div>
                @endif
                @if($tweet->author->email)
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                        <polyline points="3 7 12 13 21 7"></polyline>
                    </svg>&nbsp;
                    <a title="Email" href="mailto:{{ $tweet->author->email }}">{{ $tweet->author->email }}</a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
    @if($answer && $answer->isAnswered())
    <div class="col-8 mt-4">
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
    <div class="col-8 mt-4">
        <div class="card-tabs">
            <ul class="nav nav-tabs">
                <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
                    <a href="#direct" class="nav-link card-title active" data-bs-toggle="tab">Draft your Response</a>
                </li>
                <li class="nav-item" style="border-bottom: 1px solid rgba(98,105,118,.16);">
                    <a href="#mail" class="nav-link card-title" data-bs-toggle="tab">Send your response as an email</a>
                </li>
            </ul>

            <div class="card tab-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <label class="form-label">Use a canned response</label>
                            <select class="form-select" @if($cannedResponses->count() === 0) disabled @else id="canned-responses" @endif>
                                @foreach ($cannedResponses as $cannedResponse)
                                <option value="{{ $cannedResponse->content }}">{{ $cannedResponse->title }}</option>
                                @endforeach
                            </select>
                            <p class="small text-muted mt-2">
                                <a href="{{ route('dashboard.responses.new') }}">Create new canned response</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div id="direct" class="tab-pane show active">
                    <form action="{{ route('dashboard.requests.dm', ['id' => $tweet->getId()]) }}" method="POST" target="_blank">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <label class="form-label required">Your response</label>
                                    <textarea id="direct-content" rows="5" name="content" class="form-control @if($errors->has('content')) is-invalid @endif" placeholder="Write your response here..." required>{{ old('content') ?? ($answer ? $answer->content : '') }}</textarea>
                                    @if($errors->has('content'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('content') }}
                                        </div>
                                    @endif
                                    <p class="small text-muted mt-2">Reply via Twitter</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-clnkgo ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-twitter" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c-.002 -.249 1.51 -2.772 1.818 -4.013z"></path>
                                    </svg>
                                    &nbsp;Send as a Direct Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div id="mail" class="tab-pane">
                    <form action="{{ route('dashboard.requests.mail', ['id' => $tweet->getId()]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="content" id="mail-content"/>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mt-2">
                                    <label class="form-label required">Your response</label>
                                    <textarea id="mail-content-text" rows="5" name="content" class="form-control @if($errors->has('content')) is-invalid @endif" placeholder="Write your response here..." required>{{ old('content') ?? ($answer ? $answer->content : '') }}</textarea>
                                    @if($errors->has('content'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('content') }}
                                        </div>
                                    @endif
                                    <p class="small text-muted mt-2">Reply via Twitter</p>
                                </div>

                                <div class="col-12 mt-2">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="subject" class="form-control @if($errors->has('subject')) is-invalid @endif" value="{{ old('subject', ! is_null($answer) ? $answer->subject : null) }}" placeholder="Subject"/>
                                    @if($errors->has('subject'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('subject') }}
                                        </div>
                                    @endif
                                </div>
                                @if($featureService->isPackFeatureEnabled(\BADDIServices\ClnkGO\Models\Pack::MULTIPLE_EMAILS_SENDER))
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
                                @endif
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
                                <button type="submit" class="btn btn-clnkgo ms-auto">
                                    {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                                        <polyline points="3 7 12 13 21 7"></polyline>
                                    </svg> --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                        <path d="M21 3l-6.5 18a0.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a0.55 .55 0 0 1 0 -1l18 -6.5"></path>
                                    </svg>
                                    &nbsp;Send as an E-Mail
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
  @include('partials.dashboard.scripts.form')
@endsection
@section('script')
    $('document').ready(function() {
        $('#mail-content-text').val($('#mail-content').val());

        $('#direct-content-text').on('change', function (event) {
            $('#mail-content-text').val($('#mail-content').val());
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
                    $('#mail-content-text').val(event.detail.choice.value);
                }
            },
            false,
        );
    });
@endsection