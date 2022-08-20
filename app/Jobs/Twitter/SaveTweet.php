<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Twitter;

use Throwable;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\AppLogger;
use BADDIServices\ClnkGO\Models\Tweet;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Helpers\EmojiParser;
use BADDIServices\ClnkGO\Services\TweetService;
use BADDIServices\ClnkGO\Domains\TwitterService;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class SaveTweet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public string $hashtag,
        public array $tweet
    ) {}

    public function middleware()
    {
        return [(new WithoutOverlapping($this->tweet[Tweet::ID_COLUMN]))->releaseAfter(30)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var TweetService */
            $tweetService = app(TweetService::class);

            /** @var TwitterService */
            $twitterService = app(TwitterService::class);

            /** @var EmojiParser */
            $emojiParser = app(EmojiParser::class);

            DB::beginTransaction();

            $dueAt = extractDate($this->tweet['text']);
            $dueAt = $emojiParser->replace($dueAt ?? null, '');

            preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/im', $this->tweet['text'] ?? '', $emailMatches);

            $email = $emojiParser->replace($emailMatches[0] ?? null, '');

            // FIXME: find then update or create
            $tweetService->save(
                $this->hashtag,
                [
                    Tweet::ID_COLUMN                    => $this->tweet['id'],
                    Tweet::URL_COLUMN                   => $twitterService->getTweetUrl($this->tweet['author_id'], $this->tweet['id']),
                    Tweet::PUBLISHED_AT_COLUMN          => Carbon::parse($this->tweet['created_at']),
                    Tweet::SOURCE_COLUMN                => $this->tweet['source'] ?? null,
                    Tweet::AUTHOR_ID_COLUMN             => $this->tweet['author_id'],
                    Tweet::TEXT_COLUMN                  => $this->tweet['text'],
                    Tweet::LANG_COLUMN                  => $this->tweet['lang'] ?? null,
                    Tweet::DUE_AT_COLUMN                => ! is_null($dueAt) ? Carbon::parse($dueAt) : null,
                    Tweet::EMAIL_COLUMN                 => $email ?? null,
                    Tweet::POSSIBLY_SENSITIVE_COLUMN    => $this->tweet['possibly_sensitive'] ?? false,
                    Tweet::IN_REPLY_TO_USER_ID_COLUMN   => $this->tweet['in_reply_to_user_id'] ?? null,
                    Tweet::REFERENCED_TWEETS_COLUMN     => json_encode($this->tweet['referenced_tweets'] ?? null),
                    Tweet::ATTACHMENTS_COLUMN           => json_encode($this->tweet['attachments'] ?? null),
                    Tweet::CONTEXT_ANNOTATIONS_COLUMN   => json_encode($this->tweet['context_annotations'] ?? null),
                    Tweet::PUBLIC_METRICS_COLUMN        => json_encode($this->tweet['public_metrics'] ?? null),
                    Tweet::GEO_COLUMN                   => json_encode($this->tweet['geo'] ?? null),
                    Tweet::ENTITIES_COLUMN              => json_encode($this->tweet['entities'] ?? null),
                    Tweet::WITHHELD_COLUMN              => json_encode($this->tweet['withheld'] ?? null),
                ]
            );

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error(
                $e,
                'job:save-tweet',
                func_get_args()
            );
        }
    }
}