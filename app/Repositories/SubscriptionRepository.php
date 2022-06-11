<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\SourceeApp\Models\Subscription;

class SubscriptionRepository
{
    public function findById(string $id): ?Subscription
    {
        return Subscription::query()
            ->with(['pack'])
            ->find($id);
    }
    
    public function paginateWithRelations(?int $page = null): LengthAwarePaginator
    {
        return Subscription::query()
                    ->with(['user', 'pack'])
                    ->where(Subscription::USER_ID_COLUMN, '!=', Auth::id())
                    ->paginate(10, ['*'], 'ap', $page);
    }

    public function getUsageBills(): Collection
    {
        return Subscription::query()
                    ->whereNotNull(Subscription::USAGE_ID_COLUMN)
                    ->get();
    }

    public function save(string $userId, array $attributes): Subscription
    {
        $attributes[Subscription::USER_ID_COLUMN] = $userId;

        return Subscription::query()
                    ->updateOrCreate(
                        [
                            Subscription::USER_ID_COLUMN    => $userId,
                        ],
                        $attributes
                    );
    }

    public function delete(string $id): bool
    {
        return Subscription::query()
                    ->where(Subscription::ID_COLUMN, $id)
                    ->delete() === 1;
    }
}