<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageShukraniUkumbushosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_shukrani_ukumbushos', function (Blueprint $table) {
            $table->id();
            $table->string('kichwa')->unique();
            $table->string('kundi');
            $table->string('aina_ya_toleo');
            $table->longText('ujumbe');
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
        Schema::dropIfExists('message_shukrani_ukumbushos');
    }
}
