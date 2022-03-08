<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\SourceeApp\Models\SavedResponse;

class SavedResponseRepository
{
    public function all(): Collection
    {
        return SavedResponse::query()
            ->get();
    }

    public function paginate(string $userId, ?int $page = null): LengthAwarePaginator
    {
        return SavedResponse::query()
            ->where(SavedResponse::USER_ID_COLUMN, $userId)
            ->paginate(10, ['*'], 'page', $page);
    }
    
    public function count(string $userId): int
    {
        return SavedResponse::query()
            ->where(SavedResponse::USER_ID_COLUMN, $userId)
            ->count();
    }

    public function findById(string $id): ?SavedResponse
    {
        return SavedResponse::query()
            ->find($id);
    }
    
    public function getByUserId(string $userId): Collection
    {
        return SavedResponse::query()
            ->where(SavedResponse::USER_ID_COLUMN, $userId)
            ->get();
    }

    public function create(array $attributes): SavedResponse
    {
        return SavedResponse::query()
            ->create($attributes);
    }
     
    public function update(string $id, array $attributes): bool
    {
        return SavedResponse::query()
            ->where(SavedResponse::ID_COLUMN, $id)
            ->update($attributes);
    }

    public function delete(string $id): bool
    {
        return SavedResponse::query()
            ->find($id)
            ->delete();
    }
}