<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Jobs\Marketing;

use Throwable;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use BADDIServices\ClnkGO\AppLogger;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use BADDIServices\ClnkGO\Models\CPALeadTracking;
use Illuminate\Queue\Middleware\WithoutOverlapping;
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
        public array $offer
    ) {}

    public function middleware()
    {
        return [(new WithoutOverlapping($this->offer['campid']))->releaseAfter(600)];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if (empty($this->offer['campid'])) {
                return;
            }
    
            $template = 'emails.marketing.cpalead_offer';
            $subject = $this->offer['title'] ?? 'New Offer for you!';
    
            $data = [
                'email'         => $this->email,
                'subject'       => $subject,
                'link'          => $this->offer['link'],
                'featuredImage' => end($this->offer['creatives'])['url'] ?? null,
                'description'   => $this->offer['description'] ?? '',
                'buttonText'    => $this->offer['button_text'] ?? null,
            ];
    
            Mail::send($template, $data, function ($message) use ($subject) {
                $message->to($this->email);
                $message->subject($subject);
            });
    
            Event::dispatch(
                new CPALeadOfferMailWasSent([
                    CPALeadTracking::CAMPAIGN_ID_COLUMN     => $this->offer['campid'],
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