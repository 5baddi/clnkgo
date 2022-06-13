<?php

namespace Database\Seeders;

use BADDIServices\SourceeApp\Database\Seeders\AppSettingsSeeder;
use Illuminate\Database\Seeder;
use BADDIServices\SourceeApp\Database\Seeders\PacksSeeder;
use BADDIServices\SourceeApp\Database\Seeders\UsersSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // AppSettingsSeeder::class,
            // UsersSeeder::class,
            PacksSeeder::class,
        ]);
    }
}
