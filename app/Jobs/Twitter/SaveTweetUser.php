<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Twitter;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Models\TwitterUser;
use BADDIServices\ClnkGO\Domains\TwitterService;
use BADDIServices\ClnkGO\Helpers\EmojiParser;
use BADDIServices\ClnkGO\Models\Marketing\MailingList;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use BADDIServices\ClnkGO\Services\TwitterUserService;

class SaveTweetUser implements ShouldQueue
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
        public array $user
    ) {}

    public function middleware()
    {
        return [(new WithoutOverlapping($this->user[TwitterUser::ID_COLUMN]))->releaseAfter(30)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var TwitterService */
            $twitterService = app(TwitterService::class);

            /** @var TwitterUserService */
            $twitterUserService = app(TwitterUserService::class);

            /** @var EmojiParser */
            $emojiParser = app(EmojiParser::class);

            DB::beginTransaction();

            $website = extractWebsite($this->user['description'] ?? '');
            preg_match('/(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/im', $this->user['description'] ?? '', $emailMatches);
            
            if (! isset($emailMatches[0]) && isset($this->user['location']) && filter_var($this->user['location'], FILTER_VALIDATE_EMAIL)) {
                $emailMatches[0] = $this->user['location'];
                $this->user['location'] = null;
            }

            if (is_null($website) && isset($emailMatches[0])) {
                $website = extractWebsite($emailMatches[0] ?? '');
            }

            $email = $emojiParser->replace($emailMatches[0] ?? null, '');
            $website = $emojiParser->replace($website ?? null, '');


            // FIXME: find then update or create
            $twitterUserService->save(
                [
                    TwitterUser::ID_COLUMN                    => $this->user['id'],
                    TwitterUser::USERNAME_COLUMN              => $this->user['username'],
                    TwitterUser::EMAIL_COLUMN                 => $email ?? null,
                    TwitterUser::WEBSITE_COLUMN               => $website,
                    TwitterUser::NAME_COLUMN                  => $this->user['name'] ?? null,
                    TwitterUser::VERIFIED_COLUMN              => $this->user['verified'] ?? false,
                    TwitterUser::PROTECTED_COLUMN             => $this->user['protected'] ?? false,
                    TwitterUser::PROFILE_IMAGE_URL_COLUMN     => $this->user['profile_image_url'] ? (string)Str::replace('_normal', '', $this->user['profile_image_url']) : null,
                    TwitterUser::PROFILE_BANNER_URL_COLUMN    => $this->user['profile_banner_url'] ?? null,
                    TwitterUser::DESCRIPTION_COLUMN           => $this->user['description'] ?? null,
                    TwitterUser::PINNED_TWEET_ID_COLUMN       => $this->user['pinned_tweet_id'] ?? null,
                    TwitterUser::LOCATION_COLUMN              => $this->user['location'] ?? null,
                    TwitterUser::URL_COLUMN                   => (is_string($this->user['url']) && strlen($this->user['url']) !== 0)  ? $this->user['url'] : $twitterService->getUserUrl($this->user['username']),
                    TwitterUser::REGISTERED_AT_COLUMN         => Carbon::parse($this->user['created_at'] ?? now()),
                    TwitterUser::ENTITIES_COLUMN              => json_encode($this->user['entities'] ?? null),
                    TwitterUser::PUBLIC_METRICS_COLUMN        => json_encode($this->user['public_metrics'] ?? null),
                    TwitterUser::WITHHELD_COLUMN              => json_encode($this->user['withheld'] ?? null),
                ]
            );

            if (! empty( $email) && filter_var( $email, FILTER_VALIDATE_EMAIL)) {
                // TODO: use service
                MailingList::query()
                    ->updateOrCreate(
                        [
                            MailingList::EMAIL_COLUMN => strtolower( $email),
                        ],
                        [
                            MailingList::NAME_COLUMN => $this->user['name'] ?? null,
                        ]
                    );
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error(
                $e,
                'job:save-tweet-user',
                func_get_args()
            );
        }
    }
}