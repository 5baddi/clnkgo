@foreach ($tweets as $tweet)
    @if(is_null($tweet->author))
    @continue
    @endif
    <div class="col-12">
        @if($user->isSuperAdmin())
        <div class="card">
            <div class="card-header">
                <div>
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="avatar rounded" style="background-image: url({{ $tweet->author->profile_image_url ?? asset('assets/img/default_avatar.png') }})"></span>
                    </div>
                    <div class="col">
                      <div class="card-title">{{ $tweet->author->name }}</div>
                      <div class="card-subtitle">{{ '@' . $tweet->author->username }}</div>
                    </div>
                  </div>
                </div>
                <div class="card-actions">
                    @php
                        $inFavorite = $user->favorites->where(\BADDIServices\ClnkGO\Models\UserFavoriteTweet::TWEET_ID_COLUMN, $tweet->getId())->first() instanceof \BADDIServices\ClnkGO\Models\UserFavoriteTweet;
                    @endphp
                    @include('dashboard.bookmark-button')
                </div>
            </div>
            <div class="card-body p-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-100" preserveAspectRatio="none" width="400" height="200" viewBox="0 0 400 200" stroke="var(--tblr-border-color, #b8cef1)">
                    <line x1="0" y1="0" x2="400" y2="200"></line>
                    <line x1="0" y1="200" x2="400" y2="0"></line>
                </svg>
            </div>
        </div>
        @else
        <a class="card card-link" href="{{ route('dashboard.requests.show', ['id' => $tweet->getId()]) }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto">
                        <span class="avatar rounded" style="background-image: url({{ $tweet->author->profile_image_url ?? asset('assets/img/default_avatar.png') }})"></span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">{{ $tweet->author->name }}</div>
                        <div class="text-muted">{{ '@' . $tweet->author->username }}</div>
                    </div>
                    <div class="col-2 text-end">
                        @php
                            $inFavorite = $user->favorites->where(\BADDIServices\ClnkGO\Models\UserFavoriteTweet::TWEET_ID_COLUMN, $tweet->getId())->first() instanceof \BADDIServices\ClnkGO\Models\UserFavoriteTweet;
                        @endphp
                        @include('dashboard.bookmark-button')
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
                        <span title="Published at">{{ $tweet->published_at->format('d M - h:i A') }}</span>
                    </div>
                    @if($featureService->isEnabled(\BADDIServices\ClnkGO\App::EXTRACT_DUE_DATE_FEATURE) && $tweet->due_at && $tweet->due_at->greaterThan(now()))
                    <span title="Due on">Due {{ $tweet->due_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endif
    </div>
@endforeach