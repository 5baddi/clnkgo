<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Repositories;

use Carbon\Carbon;
use App\Models\User;
use BADDIServices\SourceeApp\Models\UserLinkedEmail;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRespository
{
    public function paginateWithRelations(?int $page = null): LengthAwarePaginator
    {
        return User::query()
                    ->with(['store'])
                    ->where(User::ID_COLUMN, '!=', Auth::id())
                    ->paginate(10, ['*'], 'ap', $page);
    }

    public function exists(int $customerId): ?User
    {
        return User::query()
                    ->with(['store', 'subscription'])
                    ->where(User::CUSTOMER_ID_COLUMN, $customerId)
                    ->first();
    }
    
    public function findById(string $id): ?User
    {
        return User::query()
                    ->with(['subscription', 'favorite', 'linkedEmails'])
                    ->find($id);
    }
    
    public function findByEmail(string $email): ?User
    {
        return User::query()
                    ->with(['subscription'])
                    ->where([
                        User::EMAIL_COLUMN => strtolower($email)
                    ])
                    ->first();
    }
    
    public function findByCustomerId(int $customerId): ?User
    {
        return User::query()
                    ->with(['store'])
                    ->where([
                        User::CUSTOMER_ID_COLUMN => $customerId
                    ])
                    ->first();
    }

    public function create(array $attributes): User
    {
        Arr::set($attributes, User::EMAIL_COLUMN, strtolower($attributes[User::EMAIL_COLUMN]));
        
        return User::query()
                    ->create($attributes);
    }

    /**
     * @return User|false
     */
    public function update(User $user, array $attributes)
    {
        $userUpdated = User::query()
                            ->where(
                                [
                                    User::ID_COLUMN => $user->id
                                ]
                            )
                            ->update($attributes);

        if ($userUpdated) {
            return $user->refresh();
        }

        return false;
    }
    
    public function delete(string $id): bool
    {
        return User::query()
                    ->find($id)
                    ->delete();
    }

    public function countByPeriod(Carbon $startDate, carbon $endDate, array $conditions = []): int
    {
        return User::query()
                    ->whereDate(
                        User::CREATED_AT,
                        '>=',
                        $startDate
                    )
                    ->whereDate(
                        User::CREATED_AT,
                        '<=',
                        $endDate
                    )
                    ->where($conditions)
                    ->count();
    }

    public function generateResetPasswordToken(string $email): ?string
    {
        DB::table('password_resets')
            ->where('email', $email)
            ->delete();

        DB::table('password_resets')
            ->insert([
                'email'         => $email,
                'token'         => Str::random(60),
                'created_at'    => Carbon::now()
            ]);

        $tokenData = DB::table('password_resets')
            ->where('email', $email)
            ->select('token')
            ->first();

        return $tokenData->token ?? null;
    }

    public function verifyResetPasswordToken(string $token): ?User
    {
        $token = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if ($token === null || $token->email === null) {
            return null;
        }

        return $this->findByEmail($token->email);
    }

    public function removeResetPasswordToken(string $token): bool
    {
        return DB::table('password_resets')
            ->where('token', $token)
            ->delete() > 0;
    }

    public function saveLinkedEmails(string $userId, array $emails): bool
    {
        $linkedEmails = 0;

        foreach($emails as $email) {
            UserLinkedEmail::query()
                ->updateOrCreate(
                    [
                        'email'     => $email
                    ],
                    [
                        'email'     => $email,
                        'user_id'   => $userId,
                    ]
                );
        }

        return $linkedEmails > 0;
    }
}