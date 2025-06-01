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
        Schema::create('like_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('like_balance_id');
            $table->decimal('amount', 10, 2);
            $table->string('type'); // 'deposit', 'like_purchase', 'refund', etc.
            $table->string('description')->nullable();
            $table->json('metadata')->nullable(); // Для дополнительных данных, например, post_id, like_id и т.д.
            $table->timestamps();
            
            $table->foreign('like_balance_id')->references('id')->on('like_balances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('like_transactions');
    }
};
