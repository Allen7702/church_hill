<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAhadiAinayamichangosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ahadi_ainayamichangos', function (Blueprint $table) {
            $table->id();
            $table->integer('aina_ya_michango_id');
            $table->integer('idadi_ya_awamu')->nullable();
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
        Schema::dropIfExists('ahadi_ainayamichangos');
    }
}
