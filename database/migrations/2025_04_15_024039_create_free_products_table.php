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
        Schema::create('free_products', function (Blueprint $table) {
	        $table->bigInteger('id');
	        $table->bigInteger('product_type_id');
	        $table->bigInteger('sponsor_id');
	        $table->bigInteger('receiver_id')->nullable();
	        $table->boolean('is_payment_sponsor')->default(0);
	        $table->boolean('is_sold')->default(0);
	        $table->boolean('is_active')->default(0);
	        $table->boolean('is_deleted')->default(0);
	        $table->integer('deadline_hour')->default(36)->comment('Tovar kimgadir topshirilgandan keyin 36 soatdan keyin free dokonda chiqmay qolishi uchun');
	        $table->timestamp('delivered_date')->nullable()->comment('Tovar kimgadir berilgan vaqt');
	        $table->timestamp('created_date')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_products');
    }
};
