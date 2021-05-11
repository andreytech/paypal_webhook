<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paypal_subscription_id');
            $table->string('paypal_id')->unique();
            $table->unsignedBigInteger('total_amount');
            $table->unsignedBigInteger('ordinal')->index();
            $table->timestamps();

            $table->foreign('paypal_subscription_id')->references('id')->on('paypal_subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypal_transactions');
    }
}
