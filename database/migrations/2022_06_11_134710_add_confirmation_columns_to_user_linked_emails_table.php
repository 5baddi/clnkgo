<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmationColumnsToUserLinkedEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_linked_emails', function (Blueprint $table) {
            $table->string('confirmation_token', 60)->nullable();
            $table->timestamp('confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_linked_emails', function (Blueprint $table) {
            $table->dropColumn('confirmation_token');
            $table->dropColumn('confirmed_at');
        });
    }
}
