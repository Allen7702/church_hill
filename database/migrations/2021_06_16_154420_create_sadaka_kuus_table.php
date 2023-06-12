<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSadakaKuusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sadaka_kuus', function (Blueprint $table) {
            $table->id();
            $table->double('kiasi',14,2)->default(0);
            $table->date('tarehe');
            $table->string('status')->default('subiri_uthibitisho');
            $table->string('imewekwa')->nullable();
            $table->string('maelezo')->nullable()->default('--');
            $table->string('misa')->nullable();
            $table->timestamps();

            $table->foreign('misa')->references('jina_la_misa')->on('aina_za_misas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sadaka_kuus');
    }
}
