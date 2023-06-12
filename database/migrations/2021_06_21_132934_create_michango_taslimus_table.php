<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMichangoTaslimusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('michango_taslimus', function (Blueprint $table) {
            $table->id();
            $table->string('aina_ya_mchango')->nullable();
            $table->date('tarehe');
            $table->double('kiasi')->default(0.0);
            $table->string('maelezo')->nullable()->default('--');
            $table->unsignedBigInteger('mwanafamilia');
            $table->string('kundi')->default('michango');
            $table->string('imewekwa')->nullable();
            $table->timestamps();

            $table->foreign('aina_ya_mchango')->references('aina_ya_mchango')->on('aina_za_michangos')->onDelete('set null')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('michango_taslimus');
    }
}
