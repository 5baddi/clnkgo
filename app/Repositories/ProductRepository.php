<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function first(): ?Product
    {
        return Product::query()
                    ->first();
    }
    
    public function where(array $attributes): Collection
    {
        return Product::query()
                    ->where($attributes)
                    ->get();
    }
    
    public function getTopByIds(array $ids, int $limit = 5): Collection
    {
        return Product::query()
                    ->whereIn(Product::PRODUCT_ID_COLUMN, $ids)
                    ->groupBy(Product::ID_COLUMN)
                    ->take($limit)
                    ->get();
    }
    
    public function save(array $attributes): Product
    {
        return Product::query()
                    ->updateOrCreate(
                        [
                            Product::PRODUCT_ID_COLUMN => $attributes[Product::PRODUCT_ID_COLUMN]
                        ],
                        $attributes
                    );
    }
}