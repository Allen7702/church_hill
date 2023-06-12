<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentreDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centre_details', function (Blueprint $table) {
            $table->id();
            $table->string('centre_name')->unique();
            $table->string('address');
            $table->string('email');
            $table->string('region');
            $table->string('country');
            $table->string('jimbo');
            $table->string('telephone1')->nullable();
            $table->string('telephone2')->nullable();
            $table->string('telephone3')->nullable();
            $table->string('photo')->nullable();
            $table->string('uniqueness')->unique();
            $table->timestamps();
        });

        //TODO schema
        // Schema::connection('pgsql')->create('parokia', function (Blueprint $table) {
        //     $table->string('jina_la_parokia')->unique();
        //     $table->string('mawasiliano')->nullable();
        //     $table->string('uniqueness');
        //     $table->string('mkoa');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centre_details');
    }
}
