<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicianTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technician_ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('technician_id');
            $table->timestamps();

            $table->foreign('technician_id')
                ->references('id')->on('technicians');

            $table->foreign('ticket_id')
                ->references('id')->on('tickets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technician_ticket');
    }
}
