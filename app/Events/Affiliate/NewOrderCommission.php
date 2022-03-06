<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Events\Affiliate;

use App\Models\User;
use BADDIServices\SourceeApp\Models\Commission;
use BADDIServices\SourceeApp\Models\Store;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Bus\Queueable;

class NewOrderCommission
{
    use Dispatchable, InteractsWithSockets, SerializesModels, Queueable;

    /** @var Store */
    public $store;
    
    /** @var User */
    public $affiliate;
    
    /** @var Commission */
    public $commission;

    public function __construct(Store $store, User $affiliate, Commission $commission)
    {
        $this->store = $store;
        $this->affiliate = $affiliate;
        $this->commission = $commission;
    }

    /**
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}