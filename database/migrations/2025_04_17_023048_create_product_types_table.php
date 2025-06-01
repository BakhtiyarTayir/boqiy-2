<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_types', function (Blueprint $table) {
	        $table->bigInteger('id');
	        $table->decimal('price_for_sponsor', 15,3)->default(0);
	        $table->decimal('price_for_every_one', 15,3)->default(0);
	        $table->text('text_for_sponsor')->nullable();
	        $table->text('text_for_every_one')->nullable();
	        $table->string('file_path')->nullable();
	        $table->boolean('is_deleted')->default(0);
	        $table->timestamp('created_date')->useCurrent();
        });
    }
};
