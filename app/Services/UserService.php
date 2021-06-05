<?php

/**
 * Social Rocket
 *
 * @copyright   Copyright (c) 2021, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SocialRocket\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use BADDIServices\SocialRocket\Models\Store;
use BADDIServices\SocialRocket\Models\Setting;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use BADDIServices\SocialRocket\Services\CouponService;
use BADDIServices\SocialRocket\Repositories\UserRespository;
use BADDIServices\SocialRocket\Notifications\Affiliate\NewAffiliateAccount;

class UserService extends Service
{
    /** @var UserRespository */
    private $userRepository;
    
    /** @var CouponService */
    private $couponService;

    public function __construct(UserRespository $userRepository, CouponService $couponService)
    {
        $this->userRepository = $userRepository;
        $this->couponService = $couponService;
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
    
    public function findByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }
    
    public function getStoreOwner(Store $store): ?User
    {
        return $this->userRepository->getStoreOwner($store->id);
    }

    public function coupons(Store $store): array
    {
        return $this->userRepository->coupons($store->id);
    }

    public function create(Store $store, array $attributes, bool $isAffiliate = false): User
    {
        if (isset($attributes[User::ID_COLUMN])) {
            Arr::set($attributes, User::CUSTOMER_ID_COLUMN, $attributes[User::ID_COLUMN]);
        }

        if ($isAffiliate) {
            Arr::set($attributes, User::ROLE_COLUMN, User::DEFAULT_ROLE);
        } else {
            if (!isset($attributes[User::IS_SUPERADMIN_COLUMN])) {
                Arr::set($attributes, User::ROLE_COLUMN, User::STORE_OWNER_ROLE);
            } else {
                Arr::set($attributes, User::IS_SUPERADMIN_COLUMN, $attributes[User::IS_SUPERADMIN_COLUMN]);
            }
        }

        $validator = Validator::make($attributes, [
            User::CUSTOMER_ID_COLUMN   => 'nullable|integer',
            User::FIRST_NAME_COLUMN    => 'required|string|min:1',
            User::LAST_NAME_COLUMN     => 'required|string|min:1',
            User::EMAIL_COLUMN         => 'required|email',
            User::PASSWORD_COLUMN      => 'nullable|string',
            User::PHONE_COLUMN         => 'nullable|string|max:50'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $coupon = $this->couponService->generateDiscountCode($store, $attributes[User::FIRST_NAME_COLUMN]);
        Arr::set($attributes, User::COUPON_COLUMN, $coupon);

        return $this->userRepository->create($store->id, $attributes);
    }

    public function update(User $user, array $attributes): User
    {
        $attributes = collect([
            User::FIRST_NAME_COLUMN     => Arr::get($attributes, User::FIRST_NAME_COLUMN),
            User::LAST_NAME_COLUMN      => Arr::get($attributes, User::LAST_NAME_COLUMN),
            User::EMAIL_COLUMN          => Arr::get($attributes, User::EMAIL_COLUMN),
            User::PHONE_COLUMN          => Arr::get($attributes, User::PHONE_COLUMN),
            User::PASSWORD_COLUMN       => Arr::get($attributes, User::PASSWORD_COLUMN),
            User::LAST_LOGIN_COLUMN     => Arr::get($attributes, User::LAST_LOGIN_COLUMN)
        ]);

        $filterAttributes = $attributes->filter(function($value, $key) {
            return $value !== null;
        });

        return $this->userRepository->update($user, $filterAttributes->toArray());
    }

    public function welcomeMail(User $user): void
    {
        
    }
    
    public function notifyStoreOwner(Store $store, User $affiliate): void
    {
        $store->load(['user', 'setting']);
        
        /** @var User */
        $user = $store->user;

        /** @var Setting */
        $setting = $store->setting;

        $user->notify(new NewAffiliateAccount($user, $affiliate, $setting));
    }
}