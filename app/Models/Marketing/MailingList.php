<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Models\Marketing;

use BADDIServices\ClnkGO\Entities\ModelEntity;

class MailingList extends ModelEntity
{   
    /** @var string */
    public const EMAIL_COLUMN = 'email';
    public const NAME_COLUMN = 'name';
    public const ISO_COUNTRY_COLUMN = 'iso_country';
    public const SENT_AT_COLUMN = 'sent_at';
    public const IS_ACTIVE_COLUMN = 'is_active';
    public const IS_UNSUBSCRIBED_COLUMN = 'is_unsubscribed';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mailing_list';

    public function isUnsubscribed(): bool
    {
        return $this->getAttribute(self::IS_UNSUBSCRIBED_COLUMN) === 1;
    }

    public function getEmail(): string
    {
        return $this->getAttribute(self::EMAIL_COLUMN);
    }
}