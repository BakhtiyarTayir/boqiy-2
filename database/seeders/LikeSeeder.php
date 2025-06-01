<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Like;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Создаем несколько типов лайков с публичными URL для анимаций
        $likes = [
            [
                'name' => 'Сердце',
                'price' => 10.00,
                'animation_path' => 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcDdtY2JkOXE2a3o2NnBxbWR0MXQzMnJ1MXRlcWR0NnEzaWJlcWJtdCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/l0K4kWJir91VEoa1W/giphy.gif',
                'is_active' => true
            ],
            [
                'name' => 'Огонь',
                'price' => 20.00,
                'animation_path' => 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcWJjMnJnZWJnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/26BRv0ThflsHCqDDi/giphy.gif',
                'is_active' => true
            ],
            [
                'name' => 'Звезда',
                'price' => 30.00,
                'animation_path' => 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcWJjMnJnZWJnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/l2JJu8U8SoHhQEnoQ/giphy.gif',
                'is_active' => true
            ],
            [
                'name' => 'Конфетти',
                'price' => 50.00,
                'animation_path' => 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcWJjMnJnZWJnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/26tOZ42Mg6pbTUPHW/giphy.gif',
                'is_active' => true
            ],
            [
                'name' => 'Салют',
                'price' => 100.00,
                'animation_path' => 'https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExcWJjMnJnZWJnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZnRnZiZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/26u4exk4zsAqPcq08/giphy.gif',
                'is_active' => true
            ]
        ];

        foreach ($likes as $like) {
            Like::create($like);
        }
    }
}
