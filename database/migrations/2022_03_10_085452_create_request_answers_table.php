<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_answers', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->bigInteger('tweet_id');
            $table->longText('content')->nullable();
            $table->string('email')->nullable();
            $table->boolean('answered')->default(false);
            $table->timestamp('mail_sent_at')->nullable();
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
        Schema::dropIfExists('request_answers');
    }
}
