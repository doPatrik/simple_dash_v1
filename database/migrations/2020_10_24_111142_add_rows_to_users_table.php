<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('id_address');
            $table->string('post_code', 60);
            $table->string('city', 60);
            $table->string('street_name', 192);
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user', 'fk_address_user')->references('id')->on('users')
            ->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name', 52);
            $table->string('last_name', 52);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
        Schema::dropIfExists('addresses');
    }
}
