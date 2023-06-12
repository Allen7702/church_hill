<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTareheToAwamuMichangosTable extends Migration
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
            $table->date('tarehe')->nullable();
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
            $table->dropColumn('tarehe');
        });
    }
}
