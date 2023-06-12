<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJumuiyasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jumuiyas', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_jumuiya')->unique();
            $table->string('slug');
            $table->string('comment')->nullable()->default('--');
            $table->string('jina_la_kanda')->nullable();
            $table->integer('idadi_ya_familia')->default(0);
            $table->timestamps();

            $table->foreign('jina_la_kanda')->references('jina_la_kanda')->on('kandas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jumuiyas');
    }
}
