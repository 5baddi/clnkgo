<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PageRepository
{
    public function all(): Collection
    {
        return Page::query()
            ->get();
    }
    
    public function findById(string $id): ?Page
    {
        return Page::query()
            ->find($id);
    }

    public function paginate(?int $page = null): LengthAwarePaginator
    {
        return Page::query()
            ->paginate(10, ['*'], 'ap', $page ?? 1);
    }

    public function create(array $attributes): Page
    {
        return Page::query()
            ->create($attributes);
    }
}