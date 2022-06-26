<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\ClnkGO\Models\UserLinkedEmail;
use BADDIServices\ClnkGO\Http\Filters\QueryFilter;
use BADDIServices\ClnkGO\Repositories\UserRespository;
use BADDIServices\ClnkGO\Events\LinkedEmail\LinkedEmailConfirmationMail;

class UserService
{
    public function __construct(
        private UserRespository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function getUsersKeywords(): Collection
    {
        $keywrods = collect();
        $usersKeywords = User::query()
            ->select('keywords')
            ->get()
            ->pluck('keywords');

        $usersKeywords = $usersKeywords->filter(function ($value) {
            return $value !== null || $value !== "" || strlen($value) > 0;
        });

        $usersKeywords->each(function ($value) use(&$keywrods) {
            if ($value === null || $value === "" || strlen($value) === 0) {
                return true;
            }

            $keywrods = $keywrods->merge(explode(',', trim($value)));
        });

        return $keywrods->unique();
    }

    public function paginate(QueryFilter $queryFilter): LengthAwarePaginator
    {
        return $this->userRepository->paginate($queryFilter);
    }

    public function paginateWithRelations(?int $page = null): LengthAwarePaginator
    {
        return $this->userRepository->paginateWithRelations($page);
    }

    public function verifyPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    public function exists(int $customerId): ?User
    {
        return $this->userRepository->exists($customerId);
    }
    
    public function findById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }
    
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
    
    public function findByToken(string $token): ?User
    {
        return $this->userRepository->findByToken($token);
    }
    
    public function confirmEmail(User $user): bool
    {
        return $this->userRepository->confirmEmail($user->getId());
    }
    
    public function findByCustomerId(int $customerId): ?User
    {
        return $this->userRepository->findByCustomerId($customerId);
    }
    
    public function findLinkedEmailById(string $linkedEmailId): ?UserLinkedEmail
    {
        return $this->userRepository->findLinkedEmailById($linkedEmailId);
    }
    
    public function findLinkedEmailByToken(string $linkedEmailToken): ?UserLinkedEmail
    {
        return $this->userRepository->findLinkedEmailByToken($linkedEmailToken);
    }
    
    public function removeLinkedEmail(UserLinkedEmail $linkedEmail): bool
    {
        return $this->userRepository->removeLinkedEmailById($linkedEmail->getId());
    }
    
    public function confirmLinkedEmail(UserLinkedEmail $linkedEmail): bool
    {
        return $this->userRepository->confirmLinkedEmailById($linkedEmail->getId());
    }

    public function create(array $attributes): User
    {
        $validator = Validator::make($attributes, [
            User::FIRST_NAME_COLUMN    => 'required|string|min:1',
            User::LAST_NAME_COLUMN     => 'required|string|min:1',
            User::EMAIL_COLUMN         => 'required|email',
            User::PASSWORD_COLUMN      => 'required|string',
            User::PHONE_COLUMN         => 'nullable|string|max:50',
            User::IS_SUPERADMIN_COLUMN => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if (! isset($attributes[User::IS_SUPERADMIN_COLUMN])) {
            Arr::set($attributes, User::ROLE_COLUMN, User::DEFAULT_ROLE);
        } else {
            Arr::set($attributes, User::IS_SUPERADMIN_COLUMN, $attributes[User::IS_SUPERADMIN_COLUMN]);
        }

        if (! Arr::has($attributes, User::CONFIRMATION_TOKEN_COLUMN)) {
            Arr::set($attributes,  User::CONFIRMATION_TOKEN_COLUMN, Str::substr(md5($attributes[User::EMAIL_COLUMN]), 0, 60));
        }

        $attributes[User::PASSWORD_COLUMN] = Hash::make($attributes[User::PASSWORD_COLUMN]);

        return $this->userRepository->create($attributes);
    }

    public function update(User $user, array $attributes): User
    {
        $attributes = collect($attributes);

        $filteredAttributes = $attributes->only([
            User::FIRST_NAME_COLUMN,
            User::LAST_NAME_COLUMN,
            User::EMAIL_COLUMN,
            User::PHONE_COLUMN,
            User::PASSWORD_COLUMN,
            User::LAST_LOGIN_COLUMN,
            User::ROLE_COLUMN,
            User::VERIFIED_AT_COLUMN,
            User::KEYWORDS_COLUMN
        ])->filter(function($value, $key) {
            if ($key === User::KEYWORDS_COLUMN) {
                return true;
            }

            return $value !== null;
        });

        if ($filteredAttributes->has(User::PASSWORD_COLUMN)) {
            $filteredAttributes->put(User::PASSWORD_COLUMN, Hash::make($filteredAttributes->get(User::PASSWORD_COLUMN)));
        }

        if ($filteredAttributes->has(User::KEYWORDS_COLUMN)) {
            $filteredAttributes->put(User::KEYWORDS_COLUMN, strtolower($filteredAttributes->get(User::KEYWORDS_COLUMN)));
        }

        return $this->userRepository->update($user, $filteredAttributes->toArray());
    }
    
    public function delete(User $user): bool
    {
        return $this->userRepository->delete($user->id);
    }
    
    public function restrict(User $user): User
    {
        return $this->userRepository->update(
            $user,
            [
                User::BANNED_COLUMN => ! $user->isBanned()
            ]
        );
    }

    public function generateResetPasswordToken(User $user): ?string
    {
        return $this->userRepository->generateResetPasswordToken($user->email);
    }
    
    public function verifyResetPasswordToken(string $token): ?User
    {
        return $this->userRepository->verifyResetPasswordToken($token);
    }
    
    public function removeResetPasswordToken(string $token): bool
    {
        return $this->userRepository->removeResetPasswordToken($token);
    }

    public function saveLinkedEmail(User $user, string $email): UserLinkedEmail
    {   
        $linkedEmail = $this->userRepository->saveLinkedEmail($user->getId(), strtolower($email));

        Event::dispatch(new LinkedEmailConfirmationMail($linkedEmail->getId()));

        return $linkedEmail;
    }
}