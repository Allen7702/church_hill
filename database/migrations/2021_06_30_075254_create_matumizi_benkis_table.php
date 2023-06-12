<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatumiziBenkisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matumizi_benkis', function (Blueprint $table) {
            $table->id();
            $table->string('namba_ya_ankra')->nullable();
            $table->double('kiasi');
            $table->string('imewekwa_na')->nullable();
            $table->date('tarehe');
            $table->string('aina_ya_ulipaji');
            $table->string('aina_ya_matumizi')->nullable();
            $table->string('akaunti_namba')->nullable();
            $table->string('namba_nukushi')->unique();
            $table->string('kundi');
            $table->string('maelezo')->nullable()->default('--');
            $table->timestamps();

            $table->foreign('akaunti_namba')->references('akaunti_namba')->on('akaunti_za_benkis')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matumizi_benkis');
    }
}
