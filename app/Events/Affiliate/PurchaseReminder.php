<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Events\Affiliate;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use BADDIServices\SourceeApp\Models\Store;
use BADDIServices\SourceeApp\Models\Setting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PurchaseReminder
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    /** @var Store */
    public $store;
    
    /** @var User */
    public $affiliate;

    /** @var string|null */
    public $reminder;

    /** @var Setting */
    public $setting;

    public function __construct(Store $store, User $affiliate, ?string $reminder = null)
    {
        $this->store = $store;
        $this->affiliate = $affiliate;
        $this->reminder = $reminder;

        $this->store->load('setting');
        $this->setting = $store->setting;
    }

    /**
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}