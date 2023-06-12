<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMajinaUsaidizisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('majina_usaidizis', function (Blueprint $table) {
            $table->id();
            $table->string('jina_kamili');
            $table->string('mawasiliano');
            $table->string('cheo_familia');
            $table->string('jumuiya');
            $table->string('jinsia');
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('majina_usaidizis');
    }
}
