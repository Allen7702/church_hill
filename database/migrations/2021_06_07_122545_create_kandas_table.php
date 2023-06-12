<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKandasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kandas', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_kanda')->unique();
            $table->integer('idadi_ya_jumuiya')->default(0);
            $table->string('slug');
            $table->string('herufi_ufupisho')->unique();
            $table->string('uniqueness');
            $table->string('comment')->nullable()->default('--');
            $table->timestamps();
        });

        //TODO creating schema
        // Schema::connection('pgsql')->create('kanda', function (Blueprint $table) {
        //     $table->string('jina_la_kanda');
        //     $table->string('maelezo')->nullable();
        //     $table->string('uniqueness');
        //     $table->string('jina_la_parokia')->nullable();

        //     $table->foreign('jina_la_parokia')->references('jina_la_parokia')->on('parokia')->onDelete('set null')->onUpdate('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kandas');
    }
}
