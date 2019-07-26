<?php

namespace App\DataFixtures\Faker;

use Faker\Provider\Base;

class DeveloperProvider extends Base {

    protected static $developers = [
        'Avalanche Studios',
        'Avanquest Software',
        'Backbone Entertainment',
        'Bandai',
        'Bullfrog Production\'s',
        'Bungie Studios',
        'Buzz Monkey Software',
        'Camelot Software Planning',
        'Capcom',
        'Double Eleven',
        'Double Fine Productions',
        'Double Helix Games',
        'DreamFactory',
        'Easy Studios',
        'Eat Sleep Play',
        'Eden Games',
    ];

    public static function developer() {

        return static::randomElement(self::$developers);
    }
}