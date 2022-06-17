<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Entities;

use BADDIServices\ClnkGO\Traits\Filterable;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use BADDIServices\ClnkGO\Traits\HasUUID;
use Carbon\Carbon;

class ModelEntity extends EloquentModel
{
    use HasUUID, Filterable;

    /** @var string */
    public const ID_COLUMN = 'id';
    public const DELETED_AT_COLUMN = 'deleted_at';
    public const UPDATED_AT_COLUMN = self::UPDATED_AT;
    public const CREATED_AT_COLUMN = self::CREATED_AT;

    /** @var bool */
    public $incrementing = false;

    /** @var string */
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $guarded = [];

    public function getId(): string
    {
        return $this->getAttribute(self::ID_COLUMN);
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->getAttribute(self::UPDATED_AT_COLUMN) ?? null;
    }
    
    public function getDeletedAt(): ?Carbon
    {
        return $this->getAttribute(self::DELETED_AT_COLUMN) ?? null;
    }
    
    public function getCreatedAt(): ?Carbon
    {
        return $this->getAttribute(self::CREATED_AT_COLUMN) ?? null;
    }
}