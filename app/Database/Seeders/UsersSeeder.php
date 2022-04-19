<?php

/**
 * Sourcee.app
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use BADDIServices\SourceeApp\Services\UserService;

class UsersSeeder extends Seeder
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->userService->create([
            User::FIRST_NAME_COLUMN     => "Mohamed",
            User::LAST_NAME_COLUMN      => "BADDI",
            User::EMAIL_COLUMN          => "project@baddi.info",
            User::PASSWORD_COLUMN       => "baddidev",
            User::IS_SUPERADMIN_COLUMN  => true,
        ]);
    }
}