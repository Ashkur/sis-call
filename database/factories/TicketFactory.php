<?php

use App\Ticket;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    return [
        'description' => $faker->realText,
        'status' => 'RESOLVIDO',
    ];
});
