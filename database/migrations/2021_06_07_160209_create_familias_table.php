<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_familia');
            $table->string('jina_la_jumuiya')->nullable();
            $table->integer('wanafamilia')->default(0);
            $table->string('maoni')->nullable()->default('--');
            $table->string('mawasiliano')->nullable();
            $table->string('slug');
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
        Schema::dropIfExists('familias');
    }
}
