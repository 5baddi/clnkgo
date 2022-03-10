<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_users', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('username');
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->longText('description')->nullable();
            $table->string('profile_image_url')->nullable();
            $table->string('profile_banner_url')->nullable();
            $table->boolean('verified')->nullable();
            $table->boolean('protected')->nullable();
            $table->string('location')->nullable();
            $table->string('pinned_tweet_id')->nullable();
            $table->json('entities')->nullable();
            $table->json('public_metrics')->nullable();
            $table->json('withheld')->nullable();
            $table->timestamp('registered_at');
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
        Schema::dropIfExists('twitter_users');
    }
}
