<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use BADDIServices\ClnkGO\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use BADDIServices\ClnkGO\Models\Authenticatable;
use BADDIServices\ClnkGO\Models\UserLinkedEmail;
use BADDIServices\ClnkGO\Models\UserFavoriteTweet;
use BADDIServices\ClnkGO\Traits\Filterable;

class User extends Authenticatable
{
    use Notifiable, Filterable;

    /** @var string */
    public const EMAIL_COLUMN = 'email';
    public const LAST_NAME_COLUMN = 'last_name';
    public const FIRST_NAME_COLUMN = 'first_name';
    public const PHONE_COLUMN = 'phone';
    public const PASSWORD_COLUMN = 'password';
    public const KEYWORDS_COLUMN = 'keywords';
    public const CUSTOMER_ID_COLUMN = 'customer_id';
    public const LAST_LOGIN_COLUMN = 'last_login';
    public const VERIFIED_AT_COLUMN = 'verified_at';
    public const CONFIRMATION_TOKEN_COLUMN = 'confirmation_token';
    public const REMEMBER_TOLEN_COLUMN = 'remember_token';
    public const ROLE_COLUMN = 'role';
    public const IS_SUPERADMIN_COLUMN = 'is_superadmin';
    public const BANNED_COLUMN = 'banned';

    public const DEFAULT_ROLE = 'client';
    public const JOURNALIST_ROLE = 'journalist';

    /** @var array */
    public const ROLES = [
        self::DEFAULT_ROLE,
        self::JOURNALIST_ROLE,
    ];

    /** @var array */
    protected $guarded = [];

    /** @var array */
    protected $hidden = [
        self::PASSWORD_COLUMN,
        self::REMEMBER_TOLEN_COLUMN,
    ];

    /** @var array */
    protected $casts = [
        self::CREATED_AT                => 'datetime',
        self::UPDATED_AT                => 'datetime',
        self::LAST_LOGIN_COLUMN         => 'datetime',
        self::VERIFIED_AT_COLUMN        => 'datetime',
        self::IS_SUPERADMIN_COLUMN      => 'boolean',
        self::BANNED_COLUMN             => 'boolean',
    ];

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'user_id');
    }
    
    public function favorites(): HasMany
    {
        return $this->hasMany(UserFavoriteTweet::class, 'user_id');
    }
    
    public function linkedEmails(): HasMany
    {
        return $this->hasMany(UserLinkedEmail::class, 'user_id');
    }

    public function setEmailAttribute($value): self
    {
        $this->attributes[self::EMAIL_COLUMN] = strtolower($value);

        return $this;
    }

    public function getEmail(): string
    {
        return $this->getAttribute(self::EMAIL_COLUMN);
    }
    
    public function getConfirmationToken(): ?string
    {
        return $this->getAttribute(self::CONFIRMATION_TOKEN_COLUMN);
    }
    
    public function getFirstName(): string
    {
        return $this->getAttribute(self::FIRST_NAME_COLUMN);
    }

    public function getFullName(): ?string
    {
        return ucwords($this->getAttribute(self::FIRST_NAME_COLUMN) . ' ' . $this->getAttribute(self::LAST_NAME_COLUMN));
    }

    public function isSuperAdmin(): bool
    {
        return $this->getAttribute(self::IS_SUPERADMIN_COLUMN) === true && is_null($this->getAttribute(self::ROLE_COLUMN));
    }
    
    public function isBanned(): bool
    {
        return $this->getAttribute(self::BANNED_COLUMN) === true;
    }

    public function hasPassword(): bool
    {
        return $this->getAttribute(self::PASSWORD_COLUMN) !== null;
    }
    
    public function getPassword(): ?string
    {
        return $this->getAttribute(self::PASSWORD_COLUMN);
    }

    public function getKeywordsAsString(): ?string
    {
        return $this->getAttribute(self::KEYWORDS_COLUMN);
    }
    
    public function getKeywords(): array
    {
        if ($this->getAttribute(self::KEYWORDS_COLUMN) !== null && strlen($this->getAttribute(self::KEYWORDS_COLUMN)) > 0) {
            return explode(',', $this->getAttribute(self::KEYWORDS_COLUMN));
        }

        return [];
    }
}