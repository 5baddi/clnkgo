<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Models\Marketing;

use BADDIServices\ClnkGO\Entities\ModelEntity;

class CPALeadTracking extends ModelEntity
{   
    /** @var string */
    public const CAMPAIGN_ID_COLUMN = 'campaign_id';
    public const EMAIL_COLUMN = 'email';
    public const SENT_AT_COLUMN = 'sent_at';
    public const IS_UNSUBSCRIBED_COLUMN = 'is_unsubscribed';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cpalead_tracking';

    public function isUnsubscribed(): bool
    {
        return $this->getAttribute(self::IS_UNSUBSCRIBED_COLUMN) === 1;
    }
}