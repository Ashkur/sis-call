<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warnings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('id_technician');
            $table->string('title');
            $table->text('description');
            $table->timestamps();

            $table->foreign('id_technician')
                ->references('id')->on('technicians');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warnings');
    }
}
