<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

#use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
//https://github.com/fzaninotto/Faker
namespace Database\Factories;
use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
class GuestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Guest::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => rand(1,3),
            'prefix' => $this->faker->title(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->safeEmail(),
            'mobile' => $this->faker->phoneNumber(),
            'phone' => $this->faker->e164PhoneNumber(),
            'zip_code' => $this->faker->postcode(),
            'is_newsletter' => 1,
            'comments' => $this->faker->text(rand(100,250)),
            'status' => rand(0,3),
        ];
    }
}
/*$factory->define(Guests::class, function (Faker $faker) {
    return [
        'client_id' => $faker->numberBetween(1,3),
        'prefix' => $faker->title($gender = null|'male'|'female'),
        'first_name' => $faker->firstName($gender = null|'male'|'female'),
        'last_name' => $faker->lastName($gender = null|'male'|'female'),
        'email' => $faker->unique()->safeEmail,
        'mobile' => $faker->phoneNumber(),
        'phone' => $faker->e164PhoneNumber(),
        'zip_code' => $faker->postcode(),
        'is_newsletter' => 1,
        'comments' => $faker->text($faker->numberBetween(100,200)),
        'status' => $faker->numberBetween(0,3),
    ];
});*/
