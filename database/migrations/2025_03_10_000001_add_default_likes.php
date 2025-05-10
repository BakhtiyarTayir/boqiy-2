<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDefaultLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('likes')->insert([
            [
                'name' => 'Сердце',
                'price' => 10,
                'animation_path' => 'heart.svg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Звезда',
                'price' => 20,
                'animation_path' => 'star.svg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Цветок',
                'price' => 30,
                'animation_path' => 'flower.svg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Бриллиант',
                'price' => 50,
                'animation_path' => 'diamond.svg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('likes')->whereIn('name', ['Сердце', 'Звезда', 'Цветок', 'Бриллиант'])->delete();
    }
} 