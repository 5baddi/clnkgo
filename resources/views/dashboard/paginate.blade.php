@foreach ($tweets as $tweet)
    <div class="col-12">
        <a class="card card-link" href="{{ route('dashboard.requests.show', ['id' => $tweet->getId()]) }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto">
                        <span class="avatar rounded" style="background-image: url({{ $tweet->author->profile_image_url ?? 'https://abs.twimg.com/sticky/default_profile_images/default_profile.png' }})"></span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">{{ $tweet->author->name }}</div>
                        <div class="text-muted">{{ '@' . $tweet->author->username }}</div>
                    </div>
                    <div class="col-2 text-end">
                        @php
                            $inFavorite = $user->favorite->where(\BADDIServices\SourceeApp\Models\UserFavoriteTweet::TWEET_ID_COLUMN, $tweet->getId())->first() instanceof \BADDIServices\SourceeApp\Models\UserFavoriteTweet;
                        @endphp
                        <form action="{{ route(sprintf('dashboard.%sbookmark.tweet', $inFavorite ? 'un' : ''), ['id' => $tweet->getId()]) }}" method="POST">
                            @csrf
                            <button class="btn btn-xs" type="submit">
                                @if(! $inFavorite)
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bookmark" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M9 4h6a2 2 0 0 1 2 2v14l-5 -3l-5 3v-14a2 2 0 0 1 2 -2"></path>
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bookmark-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <line x1="3" y1="3" x2="21" y2="21"></line>
                                    <path d="M17 17v3l-5 -3l-5 3v-13m1.178 -2.818c.252 -.113 .53 -.176 .822 -.176h6a2 2 0 0 1 2 2v7"></path>
                                </svg>
                                @endif
                            </button>
                        </form>
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
                    @if($tweet->due_at)
                    <span title="Due on">Due {{ $tweet->due_at->diffForHumans() }}</span>
                    @endif
                </div>
            </div>
        </a>
    </div>
@endforeach