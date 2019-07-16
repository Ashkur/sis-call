<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id');
            $table->text('ocurrence')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['PENDENTE', 'EM ANDAMENTO',  'RESOLVIDO', 'NÃO RESOLVIDO']);
            $table->text('solution_description')->nullable();
            $table->enum('solved', ['YES', 'NO'])->nullable();
            $table->timestamps();

            $table->foreign('employee_id')
                ->references('id')
                ->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
