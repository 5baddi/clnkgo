<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use BADDIServices\SourceeApp\Entities\ModelEntity;

class Pack extends ModelEntity
{
    use SoftDeletes;
    
    /** @var string */
    public const PER_MONTH = 'month';
    public const PER_YEAR = 'year';
    public const NAME_COLUMN = 'name';
    public const FEATURES_COLUMN = 'features';
    public const PRICE_COLUMN = 'price';
    public const TYPE_COLUMN = 'type';
    public const INTERVAL_COLUMN = 'interval';
    public const TRIAL_DAYS_COLUMN = 'trial_days';

    public const RECURRING_TYPE = 'recurring';
    public const USAGE_TYPE = 'usage';
    public const IS_POPULAR_TYPE = 'is_popular';

    /** @var int */
    public const DEFAULT_CHARGE_PRICE = 10;
    public const DEFAULT_MAX_USAGE_PRICE = 1000;
    public const DEFAULT_TRIAL_DAYS = 7;
    public const UNLIMITED_AFFILIATES = 1;
    public const PAYOUT_METHODS = 2;
    public const REPORTING = 3;
    public const CUSTOMIZATION = 4;
    public const SUPPORT = 5;
    public const REVENUE_NOT_SHARED = 5;

    public const ALL_REQUESTS_ACCESS = 1;
    public const INSTANT_REQUEST_NOTIFICATIONS = 2;
    public const KEYWORDS = 3;
    public const CANNED_RESPONSES = 4;
    public const CANCEL_ANYTIME = 5;

    /** @var array */
    public const INTERVAL = [
        self::PER_MONTH,
        self::PER_YEAR
    ];

    /** @var array */
    public const TYPES = [
        self::RECURRING_TYPE,
        self::USAGE_TYPE
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getFeaturesAttribute(): array
    {
        return json_decode($this->attributes[self::FEATURES_COLUMN], true);
    }

    public function setFeaturesAttribute($value): self
    {
        $this->attributes[self::FEATURES_COLUMN] = json_encode($value);

        return $this;
    }

    public function isFixedPrice(): bool
    {
        return $this->attributes[self::TYPE_COLUMN] === self::TYPES[0];
    }

    public function isUsageType(): bool
    {
        return $this->getAttribute(self::TYPE_COLUMN) === self::USAGE_TYPE;
    }
}
