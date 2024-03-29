<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use BADDIServices\ClnkGO\Entities\ModelEntity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends ModelEntity
{
    use SoftDeletes;

    /** @var string */
    public const USER_ID_COLUMN = 'user_id';
    public const STORE_ID_COLUMN = 'store_id';
    public const PACK_ID_COLUMN = 'pack_id';
    public const CHARGE_ID_COLUMN = 'charge_id';
    public const USAGE_ID_COLUMN = 'usage_id';
    public const STATUS_COLUMN = 'status';
    public const BILLING_ON_COLUMN = 'billing_on';
    public const ACTIVATED_ON_COLUMN = 'activated_on';
    public const TRIAL_ENDS_ON_COLUMN = 'trial_ends_on';
    public const ENDS_ON_COLUMN = 'ends_on';
    public const CANCELLED_ON_COLUMN = 'cancelled_on';
    public const ACTIVE_STATUS = 'active';

    public const DEFAULT_STATUS = 'pending';
    public const CHARGE_ACCEPTED = 'active';
    public const CHARGE_CANCELLD = 'cancelled';
    public const CHARGE_DECLINED = 'declined';
    public const CHARGE_EXPIRED = 'expired';

    /** @var array */
    public const STATUSES = [
        self::DEFAULT_STATUS,
        self::CHARGE_ACCEPTED,
        self::CHARGE_DECLINED,
        self::CHARGE_EXPIRED,
        self::CHARGE_CANCELLD,
    ];

    /** @var array */
    protected $casts = [
        self::ACTIVATED_ON_COLUMN   => 'date',
        self::TRIAL_ENDS_ON_COLUMN  => 'date',
        self::ENDS_ON_COLUMN        => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }
    
    public function getTrialEndsOn(): ?Carbon
    {
        if (is_null($this->getAttribute(self::TRIAL_ENDS_ON_COLUMN))) {
            return null;
        }

        return Carbon::parse($this->getAttribute(self::TRIAL_ENDS_ON_COLUMN));
    }
    
    public function getEndsOn(): ?Carbon
    {
        if (is_null($this->getAttribute(self::ENDS_ON_COLUMN))) {
            return null;
        }

        return Carbon::parse($this->getAttribute(self::ENDS_ON_COLUMN));
    }
    
    public function isTrial(): bool
    {
        return $this->getTrialEndsOn() !== null && $this->getTrialEndsOn()->greaterThan(Carbon::now()->addDay());
    }
    
    public function isActive(): bool
    {
        return $this->isTrial() || ($this->getEndsOn() !== null && $this->getEndsOn()->greaterThan(Carbon::now()->addDay()));
    }
}
