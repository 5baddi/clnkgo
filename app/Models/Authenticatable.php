<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Traits\HasUUID;
use Illuminate\Foundation\Auth\User as BaseUser;

class Authenticatable extends BaseUser
{
    use HasUUID;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public const ID_COLUMN = 'id';

    public function getId(): ?string
    {
        return $this->getAttribute(self::ID_COLUMN);
    }
}