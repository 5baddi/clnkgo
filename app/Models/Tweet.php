<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tweet extends ModelEntity
{   
    /** @var string */
    public const HASHTAG_COLUMN = 'hashtag';
    public const URL_COLUMN = 'url';
    public const AUTHOR_ID_COLUMN = 'author_id';
    public const TEXT_COLUMN = 'text';
    public const SOURCE_COLUMN = 'source';
    public const LANG_COLUMN = 'lang';
    public const PUBLIC_METRICS_COLUMN = 'public_metrics';
    public const ENTITIES_COLUMN = 'entities';
    public const POSSIBLY_SENSITIVE_COLUMN = 'possibly_sensitive';
    public const PUBLISHED_AT_COLUMN = 'published_at';
    public const WITHHELD_COLUMN = 'withheld';
    public const ATTACHMENTS_COLUMN = 'attachments';
    public const REFERENCED_TWEETS_COLUMN = 'referenced_tweets';
    public const IN_REPLY_TO_USER_ID_COLUMN = 'in_reply_to_user_id';
    public const CONTEXT_ANNOTATIONS_COLUMN = 'context_annotations';
    public const GEO_COLUMN = 'geo';
    public const DUE_AT_COLUMN = 'due_at';
    public const EMAIL_COLUMN = 'email';

    /** @var array */
    protected $casts = [
        self::PUBLISHED_AT_COLUMN   => 'datetime',
        self::DUE_AT_COLUMN         => 'datetime'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(TwitterUser::class, self::AUTHOR_ID_COLUMN);
    }
    
    public function answers(): HasMany
    {
        return $this->hasMany(RequestAnswer::class, self::ID_COLUMN, RequestAnswer::TWEET_ID_COLUMN);
    }
    
    public function media(): HasMany
    {
        return $this->hasMany(TwitterMedia::class, TwitterMedia::TWEET_ID_COLUMN, self::ID_COLUMN);
    }

    public function getText(): string
    {
        return $this->getAttribute(self::TEXT_COLUMN);
    }
}