<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'password' => 'password', // password
        'email' => $faker->unique()->safeEmail,
    ];
});

$factory->define(\App\Course::class, function (Faker $faker) {
   return [
     'name' => $faker->sentence,
     'description' => $faker->text,
     'difficulty' => rand(1, 4)
   ];
});

$factory->define(\App\Lesson::class, function (Faker $faker) {
   $type = rand(0, 1);
    return [
        'name' => $faker->sentence,
        'type' => $type,
        'link' => $type == 0 ? 'pdfs/example.pdf' : 'https://www.youtube.com/embed/q-YWNUO31kk',
        'content' => $faker->realText(500),
        'required_time' => rand(10, 120) / 10.0
   ];
});

$factory->define(\App\Question::class, function (Faker $faker) {
   return [
       'question' => $faker->sentence,
       'answer_1' => 'Correct',
       'answer_2' => $faker->sentence,
       'answer_3' => $faker->sentence,
       'answer_4' => $faker->sentence,
       'answer_5' => $faker->sentence,
       'correct' => 1
   ];
});
