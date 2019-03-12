<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenilaiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penilaians', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('magang_id');
            $table->unsignedInteger('sub_aspek_nilai_id');
            $table->float('nilai',8,2);
            $table->string('custom_name')->nullable();
            $table->timestamps();

            $table->foreign('magang_id')->references('id')->on('magangs')->onDelete('cascade');
            $table->foreign('sub_aspek_nilai_id')->references('id')->on('sub_aspek_nilais')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penilaians');
    }
}
