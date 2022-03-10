<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;

class UserFavoriteTweet extends ModelEntity
{   
    /** @var string */
    public const USER_ID_COLUMN = 'user_id';
    public const TWEET_ID_COLUMN = 'tweet_id';
}