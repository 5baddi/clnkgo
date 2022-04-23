<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;

class UserLinkedEmail extends ModelEntity
{
    /** @var string */
    public const USER_ID_COLUMN = 'user_id';
    public const EMAIL_COLUMN = 'email';
}