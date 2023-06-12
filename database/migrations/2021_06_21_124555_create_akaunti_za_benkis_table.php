<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkauntiZaBenkisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akaunti_za_benkis', function (Blueprint $table) {
            $table->id();
            $table->string('jina_la_benki');
            $table->string('jina_la_akaunti');
            $table->string('akaunti_namba')->unique();
            $table->string('tawi');
            $table->string('maelezo')->nullable()->default('--');
            $table->string('hali_ya_akaunti')->default('hai');
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
        Schema::dropIfExists('akaunti_za_benkis');
    }
}
