<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/**
 * Maka manga
 */
$factory->define(App\Manga::class, function (Faker\Generator $faker) {

    return [
        'manga_name' => $faker->name,
        'status' => (rand(0,1) == 0) ? 'full' : 'continue',
        'slug' => $faker->url,
        'description' => $faker->text,
        'thumbnail_uri' => $faker->url,
        'view_count' => $faker->numberBetween(0, 1000),
        'like_count' => $faker->numberBetween(0, 1000)
    ];
});

/*
 * make tag
 */
$factory->define(App\Tag::class, function (Faker\Generator $faker) {

    return [
        'tag_name' => $faker->name
    ];
});

/*
 * make author
 */
$factory->define(App\Author::class, function (Faker\Generator $faker) {

    return [
        'author_name' => $faker->name,
        'is_deleted' => 0
    ];
});

/*
 * make manga_tag
 */
$factory->define(App\MangaTag::class, function (Faker\Generator $faker) {

    return [
        'manga_id' => $faker->randomNumber(),
        'tag_id' => $faker->randomNumber()
    ];
});

/*
 * make manga_author
 */
$factory->define(App\MangaAuthor::class, function (Faker\Generator $faker) {

    return [
        'manga_id' => $faker->randomNumber(),
        'author_id' => $faker->randomNumber()
    ];
});
