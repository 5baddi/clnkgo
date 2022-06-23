<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Repositories;

use Illuminate\Support\Arr;
use BADDIServices\ClnkGO\Models\TwitterUser;
use Illuminate\Database\Eloquent\Collection;

class TwitterUserRespository
{
    public function all(): Collection
    {
        return TwitterUser::query()
            ->get();
    }

    public function findById(string $id): ?TwitterUser
    {
        return TwitterUser::query()
            ->find($id);
    }

    public function save(array $attributes): TwitterUser
    {
        return TwitterUser::query()
            ->updateOrCreate(
                [TwitterUser::ID_COLUMN => $attributes[TwitterUser::ID_COLUMN]],
                Arr::except($attributes, TwitterUser::ID_COLUMN)
            );
    }

    public function delete(string $id): bool
    {
        return TwitterUser::query()
            ->find($id)
            ->delete();
    }
}