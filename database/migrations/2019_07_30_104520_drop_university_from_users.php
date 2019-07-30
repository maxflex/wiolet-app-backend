<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropUniversityFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company', 'occupation', 'university']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('job', ['unoccupied', 'dont_want_to_work', 'freelancer', 'occupied', 'hardworker'])->nullable();
            $table->enum('education', ['school', 'college', 'unfinished_university', 'university', 'master'])->nullable();
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
