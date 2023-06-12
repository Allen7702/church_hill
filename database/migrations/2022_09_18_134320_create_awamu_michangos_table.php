<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwamuMichangosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awamu_michangos', function (Blueprint $table) {
            $table->id();
            $table->integer('michango_benki_id')->nullable();
            $table->integer('michango_taslimu_id')->nullable();
            $table->integer('awamu');
            $table->integer('ahadi_ainayamichango_id');
            $table->double('kiasi')->nullable();
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
        Schema::dropIfExists('awamu_michangos');
    }
}
