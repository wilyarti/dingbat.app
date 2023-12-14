<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserColumnToWeek extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('week', function (Blueprint $table) {
            $table->integer('user_id')->nullable();
            $table->string('week_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('week', function (Blueprint $table) {
            $table->dropColumn('user_id')->nullable();
            $table->dropColumn('week_name')->nullable();

        });
    }
}
