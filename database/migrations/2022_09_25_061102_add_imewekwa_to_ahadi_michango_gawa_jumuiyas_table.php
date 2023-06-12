<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImewekwaToAhadiMichangoGawaJumuiyasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ahadi_michango_gawa_jumuiyas', function (Blueprint $table) {
            //
            $table->integer('imewekwa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ahadi_michango_gawa_jumuiyas', function (Blueprint $table) {
            //
            $table->dropColumn('imewekwa');
        });
    }
}
