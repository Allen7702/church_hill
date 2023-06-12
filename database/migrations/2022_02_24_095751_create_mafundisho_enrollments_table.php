<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMafundishoEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mafundisho_enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('type');
            $table->string('status');
            $table->string('partner_name')->nullable();
            $table->string('partner_jumuiya')->nullable();
            $table->string('partner_phone')->nullable();
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->foreignId('mwanafamilia_id')->constrained();
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
        Schema::dropIfExists('mafundisho_enrollments');
    }
}
