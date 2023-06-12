<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliaUsaidizisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familia_usaidizis', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_familia');
            $table->string('jina_la_jumuiya');
            $table->string('mawasiliano')->unique();
            $table->string('cheo_familia');
            $table->string('jinsia');
            $table->string('maoni')->nullable()->default('--');
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
        Schema::dropIfExists('familia_usaidizis');
    }
}
