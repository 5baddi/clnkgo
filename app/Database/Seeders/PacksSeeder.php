<?php

/**
 * Presspitch.io
 *
 * @copyright Copyright (c) 2022, BADDI Services. (https://baddi.info)
 */

namespace BADDIServices\SourceeApp\Database\Seeders;

use BADDIServices\SourceeApp\Models\Pack;
use Illuminate\Database\Seeder;

class PacksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Pack::create([
            'name'          => 'The plan',
            'price'         => 12,
            'type'          => Pack::RECURRING_TYPE,
            'is_popular'    => true,
            'trial_days'    => 3,
            'features'      => [
                [
                    'key'       => Pack::ALL_REQUESTS_ACCESS,
                    'name'      => 'Access to all Journo Requests',
                    'enabled'   => true
                ],
                [
                    'key'       => Pack::INSTANT_REQUEST_NOTIFICATIONS,
                    'name'      => 'Instant Journo Request notifications',
                    'enabled'   => true
                ],
                [
                    'key'       => Pack::KEYWORDS,
                    'name'      => 'Up to 30 Keywords',
                    'enabled'   => true,
                    'limit'     => 30
                ],
                [
                    'key'       => Pack::CANNED_RESPONSES,
                    'name'      => 'Up to 10 Templates',
                    'enabled'   => true,
                    'limit'     => 10
                ],
                [
                    'key'       => Pack::SUPPORT,
                    'name'      => '24/7 support',
                    'enabled'   => true,
                ],
                [
                    'key'       => Pack::CANCEL_ANYTIME,
                    'name'      => 'Cancel Anytime',
                    'enabled'   => true,
                ],
            ]
        ]);
    }
}
