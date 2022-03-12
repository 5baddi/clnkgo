<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BADDIServices\SourceeApp\Database\Seeders\PacksSeeder;

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
            PacksSeeder::class,
        ]);
    }
}
