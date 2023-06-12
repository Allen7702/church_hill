<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZakasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zakas', function (Blueprint $table) {
            $table->id();
            $table->double('kiasi',14,2)->default(0);
            $table->string('mwanajumuiya')->nullable();
            $table->string('jumuiya')->nullable();
            $table->date('tarehe');
            $table->string('imewekwa')->nullable();
            $table->string('status')->default('subiri_uthibitisho');
            $table->timestamps();
            // $table->foreign('mwanafamilia')->references('id')->on('mwanafamilias')->onDelete('set null')->onUpdate('cascade');
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
        Schema::dropIfExists('zakas');
    }
}
