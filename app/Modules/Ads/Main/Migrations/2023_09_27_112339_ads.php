<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('ads', function (Blueprint $table) {
            $table->increments('id');
            $table->text('type');
            $table->dateTime('create_at');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('status')->default('for_moderation');
            $table->integer('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->integer('brand_id');
            $table->integer('model_id');
            $table->foreign('model_id')->references('id')->on('car_models');
            $table->integer('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->text('transmission');
            $table->integer('body_id');
            $table->foreign('body_id')->references('id')->on('bodies');
            $table->text('state');
            $table->bigInteger('price');
            $table->json('photos');
            $table->text('mileage');
            $table->text('engine_capacity');
            $table->text('color_car');
            $table->text('year_factory');
            $table->boolean('cleared');
            $table->bigInteger('type_engine');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
