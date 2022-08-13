<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use BADDIServices\ClnkGO\Models\CPALeadTracking;

class CPALeadTrackingRepository
{
    public function all(): Collection
    {
        return CPALeadTracking::query()
            ->get();
    }

    public function findById(string $id): ?CPALeadTracking
    {
        return CPALeadTracking::query()
            ->find($id);
    }
    
    public function findByEmail(string $email): ?CPALeadTracking
    {
        return CPALeadTracking::query()
            ->where([CPALeadTracking::EMAIL_COLUMN => $email])
            ->first();
    }

    public function create(array $attributes): CPALeadTracking
    {
        return CPALeadTracking::query()
            ->create($attributes);
    }
    
    public function save(array $attributes): CPALeadTracking
    {
        if (! Arr::has($attributes, CPALeadTracking::ID_COLUMN)) {
            return CPALeadTracking::query()
                ->create($attributes);
        }

        return CPALeadTracking::query()
            ->updateOrCreate(
                [
                    CPALeadTracking::ID_COLUMN      => $attributes[CPALeadTracking::ID_COLUMN]
                ],
                Arr::except($attributes, [CPALeadTracking::ID_COLUMN])
            );
    }

    public function delete(string $id): bool
    {
        return CPALeadTracking::query()
            ->find($id)
            ->delete();
    }
}