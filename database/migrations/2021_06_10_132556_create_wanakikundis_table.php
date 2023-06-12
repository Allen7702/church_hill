<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWanakikundisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wanakikundis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mwanafamilia')->nullable();
            $table->unsignedBigInteger('kikundi')->nullable();
            $table->string('maoni')->nullable()->default('--');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('mwanafamilia')->references('id')->on('mwanafamilias')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('kikundi')->references('id')->on('kikundis')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wanakikundis');
    }
}
