<?php

use BADDIServices\SocialRocket\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trackers', function (Blueprint $table) {
            $table->uuid('id')->unqiue()->primary();
            $table->uuid('store_id');
            $table->bigInteger('customer_id');
            $table->bigInteger('order_id');
            $table->float('payment_due', 50);
            $table->string('presentment_currency', 10)->default(Setting::DEFAULT_CURRENCY);
            $table->float('total_price', 50);
            $table->float('discount', 50)->nullable();
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
        Schema::dropIfExists('trackers');
    }
}
