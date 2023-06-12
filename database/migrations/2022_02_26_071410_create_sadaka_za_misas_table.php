<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSadakaZaMisasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sadaka_za_misas', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->date('ilifanyika');
            $table->foreignId('aina_za_sadaka_id')->constrained();
            $table->foreignId('aina_za_misa_id')->constrained();
            $table->double('kiasi');
            $table->morphs('misaable');
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
        Schema::dropIfExists('sadaka_za_misas');
    }
}
