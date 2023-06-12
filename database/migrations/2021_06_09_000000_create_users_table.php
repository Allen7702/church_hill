<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('jina_kamili');
            $table->string('mawasiliano')->unique();
            $table->string('ngazi')->default('wengineo');
            $table->string('ruhusa');
            $table->string('anwani')->nullable()->default('--');
            $table->string('cheo')->nullable()->default('--');
            $table->string('picha')->nullable();
            $table->string('jumuiya')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('mwanajumuiya_id')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('cheo')->references('jina_la_cheo')->on('vyeo_kanisas')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('jumuiya')->references('jina_la_jumuiya')->on('jumuiyas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
