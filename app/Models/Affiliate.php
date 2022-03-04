<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use BADDIServices\SourceeApp\Entities\ModelEntity;

class Affiliate extends ModelEntity
{
    /** @var string */
    public const STORE_ID_COLUMN = 'store_id';
    public const CUSTOMER_ID_COLUMN = 'customer_id';
    public const EMAIL_COLUMN = 'email';
    public const LAST_NAME_COLUMN = 'last_name';
    public const FIRST_NAME_COLUMN = 'first_name';
    public const COUPON_COLUMN = 'coupon';

    /** @var array */
    protected $fillable = [
        self::CUSTOMER_ID_COLUMN,
        self::STORE_ID_COLUMN,
        self::FIRST_NAME_COLUMN,
        self::LAST_NAME_COLUMN,
        self::EMAIL_COLUMN,
        self::COUPON_COLUMN,
    ];

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }

    public function getFullName(): ?string
    {
        return ucwords($this->getAttribute(self::FIRST_NAME_COLUMN) . ' ' . $this->getAttribute(self::LAST_NAME_COLUMN));
    }
}