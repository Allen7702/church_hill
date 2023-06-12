<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVyeoKanisasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vyeo_kanisas', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_cheo')->unique();
            $table->string('maelezo')->nullable()->default('--');
            $table->string('slug');
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
        Schema::dropIfExists('vyeo_kanisas');
    }
}
