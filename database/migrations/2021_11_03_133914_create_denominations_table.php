<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDenominationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('denominations', function (Blueprint $table) {
            $table->id();
            $table->integer('noti_10000')->default(0);
            $table->integer('noti_5000')->default(0);
            $table->integer('noti_2000')->default(0);
            $table->integer('noti_1000')->default(0);
            $table->integer('noti_500')->default(0);
            $table->integer('sarafu_500')->default(0);
            $table->integer('sarafu_200')->default(0);
            $table->integer('sarafu_100')->default(0);
            $table->integer('sarafu_50')->default(0);
            $table->string('mwekaji');
            $table->date('tarehe');
            $table->string('status')->default('subiri_uthibitisho');
            $table->string('aina_ya_toleo');
            $table->string('nukushi')->nullable();
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
        Schema::dropIfExists('denominations');
    }
}
