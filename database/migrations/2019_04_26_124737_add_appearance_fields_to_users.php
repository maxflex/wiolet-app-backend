<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppearanceFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('body_type', ['athletic', 'normal', 'a_bit_overweight', 'tough', 'overweight', 'slim'])->nullable();
            $table->enum('hair_color', ['black', 'blonde', 'brown', 'dyed', 'red', 'white', 'other'])->nullable();
            $table->enum('eye_color', ['brown', 'blue', 'green', 'black', 'grey', 'other'])->nullable();
            $table->enum('kids', ['no', 'yes', 'adults'])->nullable();
            $table->enum('lives', ['alone', 'with_parents', 'with_roommates', 'with_lover'])->nullable();
            $table->enum('alcohol', ['sometimes', 'no', 'yes'])->nullable();
            $table->enum('smoking', ['sometimes', 'no', 'yes'])->nullable();
            $table->string('company')->nullable();
            $table->string('occupation')->nullable();
            $table->string('university')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
