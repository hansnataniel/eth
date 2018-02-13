<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_nota');
            $table->integer('usermh_id');
            $table->integer('user_id');
            $table->integer('bank_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('bank');
            $table->string('account_name');
            $table->string('account_number');
            $table->integer('amount');
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
        Schema::dropIfExists('payments');
    }
}
