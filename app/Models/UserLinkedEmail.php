<?php

/**
 * ClnkGO
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\ClnkGO\Models;

use BADDIServices\ClnkGO\Entities\ModelEntity;

class UserLinkedEmail extends ModelEntity
{
    /** @var string */
    public const USER_ID_COLUMN = 'user_id';
    public const EMAIL_COLUMN = 'email';
    public const CONFIRMATION_TOKEN_COLUMN = 'confirmation_token';
    public const CONFIRMED_AT_COLUMN = 'confirmed_at';

    public function isConfirmed(): bool
    {
        return ! blank($this->getAttribute(self::CONFIRMED_AT_COLUMN));
    }
    
    public function getUserId(): string
    {
        return $this->getAttribute(self::USER_ID_COLUMN);
    }
    
    public function getEmail(): string
    {
        return $this->getAttribute(self::EMAIL_COLUMN);
    }
    
    public function getConfirmationToken(): ?string
    {
        return $this->getAttribute(self::CONFIRMATION_TOKEN_COLUMN);
    }
}