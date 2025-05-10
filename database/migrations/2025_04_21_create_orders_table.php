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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // Уникальный ID заказа
            $table->unsignedBigInteger('user_id'); // ID пользователя
            $table->decimal('amount', 10, 2); // Сумма заказа
            $table->string('payment_method'); // Метод оплаты (payme, etc)
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending'); // Статус заказа
            $table->string('transaction_id')->nullable(); // ID транзакции в платежной системе
            $table->string('type'); // Тип заказа (like_balance_topup, etc)
            $table->json('metadata')->nullable(); // Дополнительные данные
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}; 