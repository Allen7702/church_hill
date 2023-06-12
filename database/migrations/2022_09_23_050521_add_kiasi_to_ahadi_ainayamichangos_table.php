<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKiasiToAhadiAinayamichangosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ahadi_ainayamichangos', function (Blueprint $table) {
            //
            $table->decimal('kiasi',12,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ahadi_ainayamichangos', function (Blueprint $table) {
            //
            $table->dropColumn('kiasi');
        });
    }
}
