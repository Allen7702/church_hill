<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWanafamiliaUsaidizisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wanafamilia_usaidizis', function (Blueprint $table) {
            $table->id();
            $table->string('jina_kamili');
            $table->string('jina_la_jumuiya');
            $table->string('mawasiliano')->unique();
            $table->string('cheo_familia');
            $table->string('jinsia');
            $table->string('maoni')->nullable()->default('--');
            $table->unsignedBigInteger('familia_id')->nullable();
            $table->string('slug');
            $table->timestamps();

            $table->foreign('familia_id')->references('id')->on('familias')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wanafamilia_usaidizis');
    }
}
