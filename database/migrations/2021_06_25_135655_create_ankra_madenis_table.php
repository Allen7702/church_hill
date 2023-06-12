<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnkraMadenisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ankra_madenis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mtoa_huduma')->nullable();
            $table->double('kiasi');
            $table->string('namba_ya_ankra')->unique();
            $table->date('tarehe');
            $table->string('status')->default('haijalipwa');
            $table->double('deni');
            $table->string('maelezo')->nullable()->default('--');
            $table->timestamps();

            $table->foreign('mtoa_huduma')->references('id')->on('watoa_hudumas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ankra_madenis');
    }
}
