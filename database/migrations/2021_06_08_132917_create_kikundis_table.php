<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKikundisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kikundis', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_kikundi')->unique();
            $table->integer('idadi_ya_waumini')->default(0);
            $table->string('maoni')->nullable()->default('--');
            $table->string('slug')->unique();
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
        Schema::dropIfExists('kikundis');
    }
}
