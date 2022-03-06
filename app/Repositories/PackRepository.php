<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\Pack;
use Illuminate\Database\Eloquent\Collection;

class PackRepository
{
    public function all(): Collection
    {
        return Pack::query()
                    ->get();
    }
    
    public function findById(string $id): ?Pack
    {
        return Pack::query()
                    ->find($id);
    }
}