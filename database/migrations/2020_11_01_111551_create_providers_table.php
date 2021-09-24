<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_addresses', function(Blueprint $table) {
            $table->id('id_provider_address');
            $table->string('postal_code');
            $table->string('city');
            $table->string('street_name');
            $table->timestamps();
        });
        Schema::create('providers', function (Blueprint $table) {
            $table->id('id_provider');
            $table->string('name');
            $table->unsignedInteger('number');
            $table->string('owner_name');
            $table->string('label');
            $table->string('color_code');
            $table->unsignedBigInteger('id_provider_address');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_address');
            $table->timestamps();

            $table->foreign('id_user', 'fk_providers_users')
                ->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('id_provider_address', 'fk_providers_provider_addresses')
                ->references('id_provider_address')->on('provider_addresses')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('id_address', 'fk_providers_addresses')
                ->references('id_address')->on('addresses')
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
        Schema::dropIfExists('providers');
        Schema::dropIfExists('provider_addresses');
    }
}
