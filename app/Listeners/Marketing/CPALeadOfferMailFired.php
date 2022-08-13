<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Listeners\Marketing;

use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\ClnkGO\Events\Marketing\CPALeadOfferMail;

class CPALeadOfferMailFired implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public function handle(CPALeadOfferMail $event)
    {
        $email = $event->email;
        $offer = $event->offer;

        $template = 'emails.marketing.cpalead_offer';
        $subject = 'New Offer for you!';

        $data = [
            'email'         => $email,
            'subject'       => $subject,
            'offer'         => $offer,
        ];

        Mail::send($template, $data, function($message) use ($email, $subject) {
            $message->to($email);
            $message->subject($subject);
        });
    }
}
