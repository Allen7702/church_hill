<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatibaYaMisasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratiba_ya_misas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('date');
            $table->text('description')->nullable();
            $table->foreignId('aina_za_misa_id')->constrained();
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
        Schema::dropIfExists('ratiba_ya_misas');
    }
}
