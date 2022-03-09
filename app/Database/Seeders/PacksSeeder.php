<?php

/**
 * Sourcee.app
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
            'price'         => 25,
            'type'          => Pack::RECURRING_TYPE,
            'is_popular'    => true,
            'trial_days'    => 7,
            'features'      => [
                [
                    'key'       =>  Pack::ALL_REQUESTS_ACCESS,
                    'name'      =>  'Access to all Journo Requests',
                    'enabled'   =>  true
                ],
                [
                    'key'       =>  Pack::INSTANT_REQUEST_NOTIFICATIONS,
                    'name'      =>  'Instant Journo Request notifications',
                    'enabled'   =>  true
                ],
                [
                    'key'       =>  Pack::KEYWORDS,
                    'name'      =>  '30 Keywords - More keywords = A greater chance of receiving relevant Journo Request notifications straight to your inbox',
                    'enabled'   =>  true,
                    'limit'     => 30
                ],
                [
                    'key'       =>  Pack::CANNED_RESPONSES,
                    'name'      =>  '10 Canned Responses - Save time by storing commonly used responses to Journo Requests',
                    'enabled'   =>  true,
                    'limit'     => 10
                ],
                [
                    'key'       =>  Pack::SUPPORT,
                    'name'      =>  'live chat support',
                    'enabled'   =>  true,
                ],
                [
                    'key'       =>  Pack::CANCEL_ANYTIME,
                    'name'      =>  'Cancel Anytime',
                    'enabled'   =>  true,
                ],
            ]
        ]);
    }
}
