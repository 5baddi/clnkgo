<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;

class SavedResponse extends ModelEntity
{   
    /** @var string */
    public const USER_ID_COLUMN = 'user_id';
    public const TITLE_COLUMN = 'title';
    public const CONTENT_COLUMN = 'content';

    public function getTitle(): string
    {
        return $this->getAttribute(self::TITLE_COLUMN);
    }
    
    public function getContent(): ?string
    {
        return $this->getAttribute(self::CONTENT_COLUMN);
    }
}