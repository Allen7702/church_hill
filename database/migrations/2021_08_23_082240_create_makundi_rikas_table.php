<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMakundiRikasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('makundi_rikas', function (Blueprint $table) {
            $table->id();
            $table->string('rika')->unique();
            $table->double('umri_kuanzia')->unique();
            $table->double('umri_ukomo')->unique();
            $table->string('maelezo')->nullable()->default('--');
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
        Schema::dropIfExists('makundi_rikas');
    }
}
