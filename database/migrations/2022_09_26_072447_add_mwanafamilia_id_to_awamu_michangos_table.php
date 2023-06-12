<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMwanafamiliaIdToAwamuMichangosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('awamu_michangos', function (Blueprint $table) {
            //
            $table->integer('mwanafamilia_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('awamu_michangos', function (Blueprint $table) {
            //
            $table->dropColumn('mwanafamilia_id');
        });
    }
}
