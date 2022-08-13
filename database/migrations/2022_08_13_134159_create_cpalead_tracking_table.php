<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpaleadTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpalead_tracking', function (Blueprint $table) {
            $table->uuid('id');
            $table->bigInteger('campaign_id');
            $table->string('email')->unique();
            $table->timestamp('sent_at')->nullable();
            $table->tinyInteger('is_unsubscribed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpalead_tracking');
    }
}