<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatumiziTaslimusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matumizi_taslimus', function (Blueprint $table) {
            $table->id();
            $table->string('namba_ya_ankra')->nullable();
            $table->double('kiasi');
            $table->string('imewekwa_na')->nullable();
            $table->date('tarehe');
            $table->string('aina_ya_ulipaji');
            $table->string('kundi');
            $table->string('aina_ya_matumizi')->nullable();
            $table->string('maelezo')->nullable()->default('--');
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
        Schema::dropIfExists('matumizi_taslimus');
    }
}
