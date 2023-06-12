<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMwanafamiliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mwanafamilias', function (Blueprint $table) {
            $table->id();
            $table->string('jina_kamili');
            $table->string('mawasiliano')->nullable();
            $table->unsignedBigInteger('familia')->nullable();
            $table->string('ndoa')->nullable();
            $table->string('jinsia')->nullable();
            $table->string('ubatizo')->default('tayari');
            $table->string('ekaristi')->nullable();
            // $table->string('cheo')->nullable();
            $table->string('kipaimara')->nullable();
            $table->string('komunio')->nullable();
            $table->string('aina_ya_ndoa')->nullable();
            $table->string('taaluma')->nullable();
            $table->string('dhehebu')->nullable();
            $table->string('maoni')->nullable()->default('--');
            $table->date('dob')->nullable();
            $table->string('namba_ya_cheti')->nullable();
            $table->string('parokia_ya_ubatizo')->nullable();
            $table->string('jimbo_la_ubatizo')->nullable();
            $table->string('namba_utambulisho')->unique()->nullable();
            $table->string('cheo_familia')->nullable();
            $table->string('rika')->nullable();
            $table->timestamps();

            $table->foreign('familia')->references('id')->on('familias')->onDelete('set null')->onUpdate('cascade');
            // $table->foreign('cheo')->references('jina_la_cheo')->on('vyeo_kanisas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mwanafamilias');
    }
}
