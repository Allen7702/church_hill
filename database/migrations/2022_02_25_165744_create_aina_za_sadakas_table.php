<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAinaZaSadakasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aina_za_sadakas', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_sadaka')->unique();
            $table->string('maelezo')->nullable()->default('--');
            $table->string('slug');
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
        Schema::dropIfExists('aina_za_sadakas');
    }
}
