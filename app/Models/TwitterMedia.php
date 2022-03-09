<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;

class TwitterMedia extends ModelEntity
{   
    /** @var string */
    public const TWEET_ID_COLUMN = 'tweet_id';
    public const TYPE_COLUMN = 'type';
    public const DURATION_MS_COLUMN = 'duration_ms';
    public const ALT_TEXT_COLUMN = 'alt_text';
    public const URL_COLUMN = 'url';
    public const PREVIEW_IMAGE_URL_COLUMN = 'preview_image_url';
    public const PUBLIC_METRICS_COLUMN = 'public_metrics';
    public const HEIGHT_COLUMN = 'height';
    public const WIDTH_COLUMN = 'width';
}