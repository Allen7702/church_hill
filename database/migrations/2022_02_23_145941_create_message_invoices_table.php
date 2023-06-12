<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->string('payment_ref')->nullable();
            $table->string('status');
            $table->double('amount');
            $table->string('channel');
            $table->string('recharge_sms');
            $table->string('mobile_number');
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
        Schema::dropIfExists('message_invoices');
    }
}
