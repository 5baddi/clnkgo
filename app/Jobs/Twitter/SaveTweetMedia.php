<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Twitter;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Models\TwitterMedia;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use BADDIServices\ClnkGO\Services\TwitterMediaService;

class SaveTweetMedia implements ShouldQueue
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
        public int $tweetId,
        public array $media
    ) {}

    public function middleware()
    {
        return [(new WithoutOverlapping($this->media['media_key']))->releaseAfter(30)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        TwitterMediaService $twitterMediaService
    ) {
        try {
            DB::beginTransaction();

            $twitterMediaService->save(
                [
                    TwitterMedia::TWEET_ID_COLUMN           => $this->tweetId,
                    TwitterMedia::ID_COLUMN                 => $this->media['media_key'],
                    TwitterMedia::TYPE_COLUMN               => $this->media['type'],
                    TwitterMedia::URL_COLUMN                => $this->media['url'] ?? null,
                    TwitterMedia::PREVIEW_IMAGE_URL_COLUMN  => $this->media['preview_image_url'] ?? null,
                    TwitterMedia::ALT_TEXT_COLUMN           => $this->media['alt_text'] ?? null,
                    TwitterMedia::HEIGHT_COLUMN             => $this->media['height'] ?? null,
                    TwitterMedia::WIDTH_COLUMN              => $this->media['width'] ?? null,
                    TwitterMedia::DURATION_MS_COLUMN        => $this->media['duration_ms'] ?? null,
                    TwitterMedia::PUBLIC_METRICS_COLUMN     => json_encode($this->media['public_metrics'] ?? null),
                ]
            );

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            AppLogger::error(
                $e,
                'job:save-tweet-media',
                func_get_args()
            );
        }
    }
}