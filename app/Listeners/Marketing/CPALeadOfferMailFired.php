<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners\Marketing;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Models\CPALeadTracking;
use BADDIServices\ClnkGO\Services\CPALeadTrackingService;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMail;

class CPALeadOfferMailFired implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public function __construct(
        private CPALeadTrackingService $CPALeadTrackingService
    ) {}

    public function handle(CPALeadOfferMail $event)
    {
        $email = $event->email;
        $offer = $event->offer;

        if (empty($offer['campid'])) {
            return;
        }

        $template = 'emails.marketing.cpalead_offer';
        $subject = $offer['title'] ?? 'New Offer for you!';

        $data = [
            'email'         => $email,
            'subject'       => $subject,
            'link'          => $offer['link'],
            'featuredImage' => end($offer['creatives'])['url'] ?? null,
            'description'   => $offer['description'] ?? '',
            'buttonText'    => $offer['button_text'] ?? null,
        ];

        Mail::send($template, $data, function ($message) use ($email, $subject) {
            $message->to($email);
            $message->subject($subject);
        });

        $this->CPALeadTrackingService
            ->create([
                CPALeadTracking::CAMPAIGN_ID_COLUMN     => $offer['campid'],
                CPALeadTracking::EMAIL_COLUMN           => $email,
                CPALeadTracking::SENT_AT_COLUMN         => Carbon::now(),
            ]);
    }
}
