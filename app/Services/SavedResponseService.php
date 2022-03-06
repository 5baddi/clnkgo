<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\SourceeApp\Models\SavedResponse;
use BADDIServices\SourceeApp\Repositories\SavedResponseRepository;

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

    public function findById(string $id): ?SavedResponse
    {
        return $this->savedResponseRepository->findById($id);
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

        $updated = $this->savedResponseRepository->update($savedResponse->getId(), $attributes);
        if ($updated) {
            return $this->findById($savedResponse->getId());
        }

        return false;
    }
}