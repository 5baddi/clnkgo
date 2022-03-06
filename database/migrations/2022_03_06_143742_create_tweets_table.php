<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('hashtag');
            $table->string('url');
            $table->bigInteger('author_id');
            $table->longText('text');
            $table->string('source')->nullable();
            $table->string('in_reply_to_user_id')->nullable();
            $table->string('lang', 10)->nullable();
            $table->json('geo')->nullable();
            $table->json('referenced_tweets')->nullable();
            $table->json('context_annotations')->nullable();
            $table->json('attachments')->nullable();
            $table->json('public_metrics')->nullable();
            $table->json('entities')->nullable();
            $table->json('withheld')->nullable();
            $table->boolean('possibly_sensitive')->nullable();
            $table->timestamp('published_at');
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
        Schema::dropIfExists('tweets');
    }
}
