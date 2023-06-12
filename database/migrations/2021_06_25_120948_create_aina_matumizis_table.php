<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAinaMatumizisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aina_matumizis', function (Blueprint $table) {
            $table->id();
            $table->string('aina_ya_matumizi')->unique();
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
        Schema::dropIfExists('aina_matumizis');
    }
}
