<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Listeners\Affiliate;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use BADDIServices\SourceeApp\Models\Store;
use BADDIServices\SourceeApp\Models\Setting;
use BADDIServices\SourceeApp\Events\Affiliate\PurchaseReminder;
use BADDIServices\SourceeApp\Events\Affiliate\PurchaseReminder\FirstPurchaseReminder;
use BADDIServices\SourceeApp\Events\Affiliate\PurchaseReminder\ThirdPurchaseReminder;
use BADDIServices\SourceeApp\Events\Affiliate\PurchaseReminder\SecondPurchaseReminder;

class PurchaseReminderFired implements ShouldQueue
{
    /** @var string */
    public const SUBJECT = "Now you get paid cold hard cash for every sale you bring us! 🤑";

    public function handle(PurchaseReminder $event)
    {
        /** @var User */
        $affiliate = $event->affiliate;
        
        /** @var Store */
        $store = $event->store;
        
        /** @var string|null */
        $reminder = $event->reminder;

        /** @var Setting */
        $setting = $event->setting;

        $emailLayout = 'emails.purchase.instantly';

        if ($reminder instanceof FirstPurchaseReminder) {
            $emailLayout = 'emails.purchase.24h';
        }
        
        if ($reminder instanceof SecondPurchaseReminder) {
            $emailLayout = 'emails.purchase.48h';
        }

        if ($reminder instanceof ThirdPurchaseReminder) {
            $emailLayout = 'emails.purchase.120h';
        }

        Mail::send($emailLayout, ['affiliate' => $affiliate, 'store' => $store, 'setting' => $setting], function($message) use ($affiliate) {
            $message->to($affiliate->email);
            $message->subject(self::SUBJECT);
        });
    }
}