<?php

namespace Database\Factories;

use App\Models\Saunalog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaunalogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Saunalog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word, // サウナ施設の名前
            'area' => $this->faker->address, // サウナ施設の所在地
            'rank' => $this->faker->numberBetween(1, 5), // ランク（1〜5）
            'comment' => $this->faker->sentence, // コメント
            'userId' => User::factory(), // Userモデルのファクトリーを使用してユーザーIDを生成
        ];
    }
}
