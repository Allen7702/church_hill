<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBajetiMakisioMapatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bajeti_makisio_mapatos', function (Blueprint $table) {
            $table->id();
            $table->string('aina_ya_mapato');
            $table->double('kiasi');
            $table->string('kundi');
            $table->integer('mwaka');
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
        Schema::dropIfExists('bajeti_makisio_mapatos');
    }
}
