<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdUserToExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenditures', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable();

            $table->foreign('id_user', 'fk_expenditures_users')
                ->references('id')->on('users');
        });

        Schema::table('expenditure_types', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable();

            $table->foreign('id_user', 'fk_expenditure_types_users')
                ->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenditures', function (Blueprint $table) {
            $table->dropForeign('fk_expenditures_users');
            $table->dropColumn('id_user');
        });

        Schema::table('expenditure_types', function (Blueprint $table) {
            $table->dropForeign('fk_expenditure_types_users');
            $table->dropColumn('id_user');
        });
    }
}
