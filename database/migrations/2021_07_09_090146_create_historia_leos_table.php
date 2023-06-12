<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriaLeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historia_leos', function (Blueprint $table) {
            $table->id();
            $table->string('kichwa')->unique();
            $table->date('tarehe');
            $table->string('uchapishaji');
            $table->longText('maelezo');
            $table->string('picha');
            $table->string('imewekwa_na')->nullable();
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
        Schema::dropIfExists('historia_leos');
    }
}
