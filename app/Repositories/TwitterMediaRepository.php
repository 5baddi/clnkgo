<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use BADDIServices\SourceeApp\Models\TwitterMedia;
use Illuminate\Database\Eloquent\Collection;

class TwitterMediaRepository
{
    public function all(): Collection
    {
        return TwitterMedia::query()
            ->get();
    }

    public function findById(string $id): ?TwitterMedia
    {
        return TwitterMedia::query()
            ->find($id);
    }

    public function save(array $attributes): TwitterMedia
    {
        return TwitterMedia::query()
            ->updateOrCreate(
                [TwitterMedia::ID_COLUMN => $attributes[TwitterMedia::ID_COLUMN]],
                $attributes
            );
    }

    public function delete(string $id): bool
    {
        return TwitterMedia::query()
            ->find($id)
            ->delete();
    }
}