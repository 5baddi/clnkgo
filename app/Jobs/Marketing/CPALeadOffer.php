<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Marketing;

use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Bus\Queueable;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use BADDIServices\ClnkGO\Models\Marketing\CPALeadTracking;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMailWasSent;

class CPALeadOffer implements ShouldQueue
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
        public string $email,
        public array $article
    ) {}

    public function middleware()
    {
        return [(new WithoutOverlapping($this->email))->releaseAfter(600)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (! Arr::has($this->article, ['title', 'description', 'urlToImage'])) {
                return;
            }
    
            $template = 'emails.marketing.cpalead_offer';
            $subject = $this->article['title'];
    
            $data = [
                'email'         => $this->email,
                'subject'       => $subject,
                'excerpt'       => $this->article['description'],
                'image'         => $this->article['urlToImage'],
            ];

            Mail::send($template, $data, function ($message) use ($subject) {
                $message->to($this->email);
                $message->subject($subject);
            });
    
            Event::dispatch(
                new CPALeadOfferMailWasSent([
                    CPALeadTracking::EMAIL_COLUMN           => $this->email,
                    CPALeadTracking::SENT_AT_COLUMN         => Carbon::now(),
                ])
            );
        } catch (Throwable $e) {
            AppLogger::error(
                $e,
                'cpalead:send-offer',
                func_get_args()
            );
        }
    }
}