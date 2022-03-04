<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use BADDIServices\SourceeApp\Entities\ModelEntity;

class PurchaseReminder extends ModelEntity
{
    /** @var string */
    public const STORE_ID_COLUMN = 'store_id';
    public const USER_ID_COLUMN = 'user_id';
    public const MAIL_24H_SENT_COLUMN = 'mail_24h_sent';
    public const MAIL_48H_SENT_COLUMN = 'mail_48h_sent';
    public const MAIL_120H_SENT_COLUMN = 'mail_120h_sent';

    /** @var array */
    protected $fillable = [
        self::STORE_ID_COLUMN,
        self::USER_ID_COLUMN,
        self::MAIL_24H_SENT_COLUMN,
        self::MAIL_48H_SENT_COLUMN,
        self::MAIL_120H_SENT_COLUMN,
    ];

    /** @var array */
    protected $casts = [
        self::MAIL_24H_SENT_COLUMN      => 'boolean',
        self::MAIL_48H_SENT_COLUMN      => 'boolean',
        self::MAIL_120H_SENT_COLUMN     => 'boolean',
    ];

    public function store(): HasOne
    {
        return $this->hasOne(Store::class);
    }
    
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}