<?php

namespace App\DataFixtures\Faker;

use Faker\Provider\Base;

class CategoryProvider extends Base {

    protected static $categories = [
        'FPS',
        'TPS',
        'Action',
        'Aventure',
        'Course',
        'Gestion',
        'Stratégie',
        'RTS',
        'Combat',
        'Simulation',
        'Infiltration',
        'Réflexion',
        'RPG',
        'MOBA',
        'MMO',
        'Hack\'n Slash',
        'Party Game',
        'Créativité',
        'Solo',
        'Multi',
        'Coop 2',
    ];

    public static function category() {

        return static::randomElement(self::$categories);
    }
}