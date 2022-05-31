<?php

use BADDIServices\SourceeApp\App;
use BADDIServices\SourceeApp\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ImportEmailsProvidersData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('app_settings')
            ->insert([
                AppSetting::KEY_COLUMN      => AppSetting::EMAILS_PROVIDERS_KEY,
                AppSetting::VALUE_COLUMN    => json_encode(App::EMAIL_PROVIDERS),
                AppSetting::TYPE_COLUMN     => AppSetting::ARRAY_TYPE,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {}
}
