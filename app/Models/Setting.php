<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Setting extends Model
{
    /** @var string */
    public const FIXED_TYPE = 'fixed';
    public const PERCENTAGE_TYPE = 'percentage';
    public const UNIQUE_DISCOUNT_FORMAT = 'unique';
    public const RANDOM_DISCOUNT_FORMAT = 'random';
    public const DEFAULT_COLOR = '#000000';
    public const DEFAULT_CURRENCY = 'USD';

    public const STORE_ID_COLUMN = 'store_id';
    public const CURRENCY_COLUMN = 'currency';
    public const BRAND_NAME_COLUMN = 'brand_name';
    public const COMMISSION_TYPE_COLUMN = 'commission_type';
    public const DISCOUNT_TYPE_COLUMN = 'discount_type';
    public const COMMISSION_AMOUNT_COLUMN = 'commission_amount';
    public const DISCOUNT_AMOUNT_COLUMN = 'discount_amount';
    public const DISCOUNT_FORMAT_COLUMN = 'discount_format';
    public const COLOR_COLUMN = 'color';

    /** @var int */
    public const DFEAULT_COMMISSION = 10;
    public const DFEAULT_DISCOUNT= 10;

    /** @var array */
    public const COMMISSION_TYPES = [
        self::FIXED_TYPE,
        self::PERCENTAGE_TYPE,
    ];
    
    /** @var array */
    public const DISCOUNT_TYPES = [
        self::FIXED_TYPE,
        self::PERCENTAGE_TYPE,
    ];
    
    /** @var array */
    public const DISCOUNT_FORMATS = [
        self::UNIQUE_DISCOUNT_FORMAT    =>  'First Name + Unique Number',
        self::RANDOM_DISCOUNT_FORMAT    =>  'Random Letters + Numbers'
    ];

    /** @var array */
    protected $fillable = [
        self::STORE_ID_COLUMN,
        self::BRAND_NAME_COLUMN,
        self::COMMISSION_TYPE_COLUMN,
        self::DISCOUNT_TYPE_COLUMN,
        self::COMMISSION_AMOUNT_COLUMN,
        self::DISCOUNT_AMOUNT_COLUMN,
        self::DISCOUNT_FORMAT_COLUMN,
        self::COLOR_COLUMN,
    ];

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }
}
