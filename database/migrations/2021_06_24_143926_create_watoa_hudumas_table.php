<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatoaHudumasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watoa_hudumas', function (Blueprint $table) {
            $table->id();
            $table->string('jina_kamili');
            $table->string('anwani');
            $table->string('aina_ya_huduma')->nullable();
            $table->string('mawasiliano');
            $table->string('maelezo')->nullable()->default('--');
            $table->timestamps();

            $table->foreign('aina_ya_huduma')->references('aina_ya_huduma')->on('hudumas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('watoa_hudumas');
    }
}
