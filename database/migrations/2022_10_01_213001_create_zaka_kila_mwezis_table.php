<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZakaKilaMwezisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zaka_kila_mwezis', function (Blueprint $table) {
            $table->id();
            $table->integer('mwanafamilia_id');
            $table->decimal('kiasi',12,2)->nullable();
            $table->enum('status',['ajalipa','kalipa']);
            $table->integer('mwezi')->nullable();
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
        Schema::dropIfExists('zaka_kila_mwezis');
    }
}
