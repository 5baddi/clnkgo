<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Models;

use BADDIServices\ClnkGO\Entities\ModelEntity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TwitterUser extends ModelEntity
{   
    /** @var string */
    public const NAME_COLUMN = 'name';
    public const USERNAME_COLUMN = 'username';
    public const EMAIL_COLUMN = 'email';
    public const WEBSITE_COLUMN = 'website';
    public const URL_COLUMN = 'url';
    public const DESCRIPTION_COLUMN = 'description';
    public const PROFILE_IMAGE_URL_COLUMN = 'profile_image_url';
    public const PROFILE_BANNER_URL_COLUMN = 'profile_banner_url';
    public const VERIFIED_COLUMN = 'verified';
    public const REGISTERED_AT_COLUMN = 'registered_at';
    public const PROTECTED_COLUMN = 'protected';
    public const ENTITIES_COLUMN = 'entities';
    public const LOCATION_COLUMN = 'location';
    public const PINNED_TWEET_ID_COLUMN = 'pinned_tweet_id';
    public const PUBLIC_METRICS_COLUMN = 'public_metrics';
    public const WITHHELD_COLUMN = 'withheld';

    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class, Tweet::AUTHOR_ID_COLUMN, self::ID_COLUMN);
    }
}