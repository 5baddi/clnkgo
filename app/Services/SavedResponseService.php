<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\ClnkGO\Models\SavedResponse;
use BADDIServices\ClnkGO\Repositories\SavedResponseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class SavedResponseService extends Service
{
    /** @var SavedResponseRepository */
    private $savedResponseRepository;

    public function __construct(SavedResponseRepository $savedResponseRepository)
    {
        $this->savedResponseRepository = $savedResponseRepository;
    }

    public function paginate(User $user, ?int $page = null): LengthAwarePaginator
    {
        return $this->savedResponseRepository->paginate($user->getId(), $page);
    }
    
    public function count(User $user): int
    {
        return $this->savedResponseRepository->count($user->getId());
    }

    public function findById(string $id): ?SavedResponse
    {
        return $this->savedResponseRepository->findById($id);
    }
    
    public function getByUser(User $user): Collection
    {
        return $this->savedResponseRepository->getByUserId($user->getId());
    }
    
    public function create(User $user, array $attributes): SavedResponse
    {
        $validator = Validator::make($attributes, [
            SavedResponse::TITLE_COLUMN    => 'required|string|min:1',
            SavedResponse::CONTENT_COLUMN  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $attributes = Arr::only(
            $attributes,
            [
                SavedResponse::TITLE_COLUMN,
                SavedResponse::CONTENT_COLUMN,
            ]
        );
        
        $attributes[SavedResponse::USER_ID_COLUMN] = $user->getId();

        return $this->savedResponseRepository->create($attributes);
    }
    
    /**
     * @return false|SavedResponse
     */
    public function update(SavedResponse $savedResponse, array $attributes)
    {
        $validator = Validator::make($attributes, [
            SavedResponse::TITLE_COLUMN    => 'required|string|min:1',
            SavedResponse::CONTENT_COLUMN  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $attributes = Arr::only(
            $attributes,
            [
                SavedResponse::TITLE_COLUMN,
                SavedResponse::CONTENT_COLUMN,
            ]
        );

        $updated = $this->savedResponseRepository->update($savedResponse->getId(), $attributes);
        if ($updated) {
            return $this->findById($savedResponse->getId());
        }

        return false;
    }

    public function delete(SavedResponse $response): bool
    {
        return $this->savedResponseRepository->delete($response->id);
    }
}