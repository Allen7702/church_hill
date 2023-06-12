<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAhadiAinayamichangosTable extends Migration
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
            $table->enum('status',['aujaanza','unaendelea','umewekwa pembeni','umefugwa'])->default('aujaanza');
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
            $table->dropColumn('status');
        });
    }
}
