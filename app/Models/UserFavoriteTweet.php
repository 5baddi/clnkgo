<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Models;

use BADDIServices\ClnkGO\Entities\ModelEntity;

class UserFavoriteTweet extends ModelEntity
{   
    /** @var string */
    public const USER_ID_COLUMN = 'user_id';
    public const TWEET_ID_COLUMN = 'tweet_id';
    public const SAVED_AT_COLUMN = 'saved_at'; // TODO: persist saved at datetime
}