<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name', 256);
            $table->string('last_name', 256);
            $table->string('address', 512);
            $table->string('address_extra', 128)->nullable();
            $table->string('city', 512);
            $table->string('zip', 16);
            $table->string('country', 256);
            $table->string('province', 256);
            $table->tinyInteger('type')->default(0); // 0 = shipping, 1 = billing
            $table->string('hash', 32);
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
        Schema::dropIfExists('addresses');
    }
}
