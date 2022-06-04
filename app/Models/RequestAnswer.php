<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;

class RequestAnswer extends ModelEntity
{   
    /** @var string */
    public const TWEET_ID_COLUMN = 'tweet_id';
    public const USER_ID_COLUMN = 'user_id';
    public const CONTENT_COLUMN = 'content';
    public const EMAIL_COLUMN = 'email';
    public const SUBJECT_COLUMN = 'subject';
    public const FROM_COLUMN = 'from';
    public const ANSWERED_COLUMN = 'answered';
    public const MAIL_SENT_AT_COLUMN = 'mail_sent_at';

    public function isAnswered(): bool
    {
        return (bool)$this->getAttribute(self::ANSWERED_COLUMN);
    }
}