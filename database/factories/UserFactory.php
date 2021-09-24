<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use OnexHelper;
use Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'hash_id' => OnexHelper::generateHashID(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email_id' => $this->faker->unique()->safeEmail(),
            'username' => base64_encode(OnexHelper::generateHashID()),
            'mobile_number' => rand(999, 789) . rand(345, 999) . rand(45, 77) . rand(03, 19),
            'password' => Hash::make('Ari#1234'),
            'sex' => $this->faker->randomElement(['male', 'female']),
            'is_owner' => 1,
            'status' => 1,
            'email_verified_at' => $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now', $timezone = 'UTC'),
            'mobile_verified_at'=> $this->faker->dateTimeBetween($startDate = '-5 years', $endDate = 'now', $timezone = 'UTC'),
            'signup_completed_at' => now(),
            'agree_signup_terms' => rand(0, 1),
            'profile_image' => $this->faker->imageUrl($width = 640, $height = 480)
        ];
    }
}
