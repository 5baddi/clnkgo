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

    protected $primaryKey = self::KEY_COLUMN;
}