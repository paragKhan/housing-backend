<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousingModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('housing_models', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->string('location');
            $table->string('gallery');
            $table->text('description');
            $table->string('bedrooms')->nullable();
            $table->string('bathrooms')->nullable();
            $table->string('width')->nullable();
            $table->string('garages')->nullable();
            $table->string('patios')->nullable();
            $table->string('master_plan_photo');
            $table->string('basic_plan_photo');
            $table->boolean('include_in_application')->default(false);
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
        Schema::dropIfExists('housing_models');
    }
}
