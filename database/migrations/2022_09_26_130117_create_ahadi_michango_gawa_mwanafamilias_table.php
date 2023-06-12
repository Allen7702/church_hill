<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAhadiMichangoGawaMwanafamiliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    
    {
        Schema::create('ahadi_michango_gawa_mwanafamilias', function (Blueprint $table) {
            $table->id();
            $table->integer('ahadi_michango_id');
            $table->integer('mwanafamilia_id');
            $table->decimal('kiasi',12,2);
            $table->integer('imewekwa');
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
        Schema::dropIfExists('ahadi_michango_gawa_mwanafamilias');
    }
}
