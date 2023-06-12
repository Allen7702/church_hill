<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaliZaKanisasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mali_za_kanisas', function (Blueprint $table) {
            $table->id();
            $table->string('aina_ya_mali')->nullable();
            $table->string('jina_la_mali');
            $table->string('usajili')->unique();
            $table->string('slug');
            $table->double('thamani')->default(0.0);
            $table->string('hali_yake');
            $table->string('maelezo')->nullable()->default('--');
            $table->timestamps();

            $table->foreign('aina_ya_mali')->references('aina_ya_mali')->on('aina_za_malis')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mali_za_kanisas');
    }
}
