<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id_from');
            $table->foreign('user_id_from')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_to');
            $table->foreign('user_id_to')->references('id')->on('users');


            $table->enum('type', [
                'like',
                'dislike',
                'ban',
                'removed-from-you-want-to-meet-list',
                'removed-from-want-to-meet-you-list',
                'removed-from-dates-list',
            ])->index();

            $table->string('comment')->nullable();

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
        Schema::dropIfExists('events');
    }
}
