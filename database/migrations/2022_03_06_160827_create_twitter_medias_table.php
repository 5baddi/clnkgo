<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_media', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('tweet_id');
            $table->string('type');
            $table->integer('duration_ms')->nullable();
            $table->integer('height')->nullable();
            $table->integer('width')->nullable();
            $table->text('alt_text')->nullable();
            $table->string('url')->nullable();
            $table->string('preview_image_url')->nullable();
            $table->json('public_metrics')->nullable();
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
        Schema::dropIfExists('twitter_media');
    }
}
