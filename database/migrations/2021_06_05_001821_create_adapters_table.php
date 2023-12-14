<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*
     * adapter_id: Int <pk>
        adapter_name: String
        adapter_class_name: String
     */
    public function up()
    {
        Schema::create('adapter', function (Blueprint $table) {
            $table->increments('adapter_id');
            $table->string('adapter_name');
            $table->string('adapter_class_name');
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
        Schema::dropIfExists('adapter');
    }
}
