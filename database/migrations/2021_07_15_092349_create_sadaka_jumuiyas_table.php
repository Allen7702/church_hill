<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSadakaJumuiyasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sadaka_jumuiyas', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_jumuiya')->nullable();
            $table->double('kiasi');
            $table->string('imewekwa_na');
            $table->date('tarehe');
            $table->string('status')->default('haijathibitishwa');
            $table->string('maelezo')->nullable()->default('--');
            $table->timestamps();

            $table->foreign('jina_la_jumuiya')->references('jina_la_jumuiya')->on('jumuiyas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sadaka_jumuiyas');
    }
}
