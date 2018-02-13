<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMininghistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mininghistories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mh');
            $table->string('balance_api');
            $table->string('inc');
            $table->string('balance_real');
            $table->string('selisih_real');
            $table->string('balance');
            $table->string('selisih');
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
        Schema::dropIfExists('mininghistories');
    }
}
