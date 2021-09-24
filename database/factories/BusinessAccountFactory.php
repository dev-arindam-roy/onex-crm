<?php

namespace Database\Factories;

use App\Models\BusinessAccount;
use Illuminate\Database\Eloquent\Factories\Factory;
use OnexHelper;

class BusinessAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BusinessAccount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'account_id' => OnexHelper::generateAccountID(),
            'organization_name' => $this->faker->text(20),
            'business_name' => $this->faker->word,
            'business_description' => $this->faker->paragraph,
            'registered_address' => $this->faker->address,
            'official_email_id' => $this->faker->safeEmail,
            'official_contact_number' => $this->faker->phoneNumber,
            'official_fax_number' => $this->faker->tollFreePhoneNumber,
            'business_logo' => $this->faker->imageUrl($width = 640, $height = 480)
        ];
    }
}
