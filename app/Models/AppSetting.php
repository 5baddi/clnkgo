<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;

class AppSetting extends ModelEntity
{   
    /** @var string */
    public const KEY_COLUMN = 'key';
    public const VALUE_COLUMN = 'value';
    public const TYPE_COLUMN = 'type';

    public const STRING_TYPE = 1;
    public const ARRAY_TYPE = 2;
    public const OBJECT_TYPE = 3;

    public const EMAILS_PROVIDERS_KEY = 'emails_providers';

    protected $primaryKey = self::KEY_COLUMN;

    public function getType(): ?int
    {
        return $this->getAttribute(self::TYPE_COLUMN);
    }
    
    public function getValue(): mixed
    {
        if (in_array($this->getType(), [self::ARRAY_TYPE, self::OBJECT_TYPE])) {
            return json_decode($this->getAttribute(self::VALUE_COLUMN), true);
        }

        return $this->getAttribute(self::VALUE_COLUMN);
    }
}