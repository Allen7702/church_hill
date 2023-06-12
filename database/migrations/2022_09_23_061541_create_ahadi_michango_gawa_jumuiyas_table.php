<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAhadiMichangoGawaJumuiyasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ahadi_michango_gawa_jumuiyas', function (Blueprint $table) {
            $table->id();
            $table->integer('ahadi_michango_id');
            $table->integer('jumuiya_id');
            $table->decimal('kiasi',12,2);
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
        Schema::dropIfExists('ahadi_michango_gawa_jumuiyas');
    }
}
