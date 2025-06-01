<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_sponsors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('company_name');
            $table->string('phone_number');
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('sponsor_image')->nullable();
            $table->integer('views_purchased')->default(1000);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_sponsors');
    }
}; 