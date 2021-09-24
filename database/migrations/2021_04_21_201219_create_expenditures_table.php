<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditures', function (Blueprint $table) {
            $table->id('id_expenditure');
            $table->unsignedInteger('price');
            $table->string('description');
            $table->unsignedBigInteger('id_type');
            $table->foreign('id_type', 'fk_expenditures_expenditure_types')
                ->references('id_type')->on('expenditure_types');
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
        Schema::dropIfExists('expenditures');
    }
}
