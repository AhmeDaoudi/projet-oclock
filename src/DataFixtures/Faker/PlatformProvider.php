<?php

namespace App\DataFixtures\Faker;

use Faker\Provider\Base;

class PlatformProvider extends Base {

    protected static $platforms = [
        'PC',
        'PlayStation 1',
        'PlayStation 2',
        'PlayStation 3',
        'PlayStation 4',
        'Nintendo Wii',
        'Nintendo Switch',
        'PSP',
        'GameBoy',
        'GameBoy Color',
        'GameBoy Advanced SP',
        'Xbox',
        'Xbox 360',
        'Xbox One',
        'PS VITA',
        'GameBoy Advanced',
    ];

    public static function platform() {

        return static::randomElement(self::$platforms);
    }
}