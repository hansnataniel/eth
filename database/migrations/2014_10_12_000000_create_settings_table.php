<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('back_session_lifetime');
            $table->integer('front_session_lifetime');
            $table->integer('visitor_lifetime');
            $table->text('admin_url');

            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            $table->string('contact_email')->nullable();
            $table->string('receiver_email')->nullable();
            $table->string('receiver_email_name')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_email_name')->nullable();
            
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();

            $table->integer('totalmh');
            $table->integer('minfee');
            $table->integer('charge');

            $table->float('eter_charge');
            $table->integer('idr_charge');
            $table->integer('usd_kurs');
            $table->integer('mh_price');
            $table->string('cloud_mining_walletid');
            $table->string('machine_walletid');
            
            $table->text('google_analytics')->nullable();
            $table->boolean('maintenance');
            
            $table->integer('update_by');
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
        Schema::dropIfExists('settings');
    }
}
