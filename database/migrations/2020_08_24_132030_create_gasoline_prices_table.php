<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGasolinePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gasoline_prices', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('street');
            $table->string('rfc');
            $table->string('date_insert')->nullable();
            $table->decimal('regular', 10, 2)->nullable();
            $table->string('colony')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('municipality_id')->nullable();
            $table->string('permission_number')->nullable();
            $table->string('application_date')->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('premium', 10, 2)->nullable();
            $table->string('business_name', 220)->nullable();
            $table->string('zipcode', 10)->nullable();
            $table->string('dieasel', 10)->nullable();
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
        Schema::dropIfExists('gasoline_prices');
    }
}
