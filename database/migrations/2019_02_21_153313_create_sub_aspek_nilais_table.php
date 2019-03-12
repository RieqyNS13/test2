<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubAspekNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_aspek_nilais', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('aspek_nilai_id');
            $table->string('name');
            $table->boolean('is_custom')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('aspek_nilai_id')->references('id')->on('aspek_nilais')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_aspek_nilais');
    }
}
