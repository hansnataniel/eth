<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawalconfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawalconfirmations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_nota');
            $table->integer('user_id');
            $table->integer('withdrawal_id');
            $table->string('amount');
            $table->date('date');
            $table->boolean('status');
            $table->timestamp('confirm_at');
            $table->integer('confirm_by');
            $table->timestamp('decline_at');
            $table->integer('decline_by');
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
        Schema::dropIfExists('withdrawalconfirmations');
    }
}
