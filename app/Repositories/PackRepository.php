<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Repositories;

use BADDIServices\ClnkGO\Models\Pack;
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
    
    public function findByName(string $name): ?Pack
    {
        return Pack::query()
                    ->where(Pack::NAME_COLUMN, $name)
                    ->first();
    }
}