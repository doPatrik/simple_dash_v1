<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id('id_bill');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('price');
            $table->date('dead_line');
            $table->boolean('is_paid')->default(0);
            $table->unsignedBigInteger('id_provider')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->timestamps();

            $table->foreign('id_provider', 'fk_bills_providers')
                ->references('id_provider')->on('providers')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreign('id_user', 'fk_bills_users')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
