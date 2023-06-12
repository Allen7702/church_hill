<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatangazosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matangazos', function (Blueprint $table) {
            $table->id();
            $table->string('kichwa')->unique();
            $table->date('tarehe');
            $table->string('uchapishaji');
            $table->longText('maelezo');
            $table->string('alama')->default('jipya');
            $table->string('attachment')->nullable();
            $table->string('imewekwa_na')->nullable();
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
        Schema::dropIfExists('matangazos');
    }
}
