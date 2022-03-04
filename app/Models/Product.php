<?php

/**
* Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Models;

use BADDIServices\SourceeApp\Entities\ModelEntity;

class Product extends ModelEntity
{
    /** @var string */
    public const TABLE_NAME = 'products';
    public const STORE_ID_COLUMN = 'store_id';
    public const PRODUCT_ID_COLUMN = 'product_id';
    public const TITLE_COLUMN = 'title';
    public const SLUG_COLUMN = 'slug';
    public const IMAGE_COLUMN = 'image';

    /** @var array */
    protected $fillable = [
        self::STORE_ID_COLUMN,
        self::PRODUCT_ID_COLUMN,
        self::TITLE_COLUMN,
        self::SLUG_COLUMN,
        self::IMAGE_COLUMN,
    ];
}