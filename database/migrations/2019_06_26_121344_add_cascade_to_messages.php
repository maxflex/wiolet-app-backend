<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCascadeToMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_user_id_from_foreign');
            $table->dropForeign('messages_user_id_to_foreign');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('user_id_from')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id_to')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            //
        });
    }
}
