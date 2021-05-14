<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Models;

use BADDIServices\SocialRocket\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pack extends Model
{
    use HasFactory, HasUUID;

    /** @var string */
    public const PER_MONTH = 'month';
    public const FEATURES_COLUMN = 'features';
    public const PRICE_TYPE_COLUMN = 'price_type';

    /** @var int */
    public const DEFAULT_TRIAL_DAYS = 7;
    public const UNLIMITED_AFFILIATES = 1;
    public const PAYOUT_METHODS = 2;
    public const REPORTING = 3;
    public const CUSTOMIZATION = 4;
    public const SUPPORT = 5;
    public const REVENUE_NOT_SHARED = 5;

    /** @var array */
    public const PAYMENT_CYCLES = [
        self::PER_MONTH
    ];

    /** @var array */
    public const PRICE_TYPES = [
        'fixed',
        'percentage'
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
        return $this->attributes[self::PRICE_TYPE_COLUMN] === self::PRICE_TYPES[0];
    }
}
