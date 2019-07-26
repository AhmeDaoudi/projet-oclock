<?php

namespace App\DataFixtures\Faker;

use Faker\Provider\Base;

class GameProvider extends Base {

    protected static $games = [
        'The Elder Scrolls III : Morrowind',
        'The Elder Scrolls IV : Oblivion',
        'The Elder Scrolls V : Skyrim',
        'Fallout 3',
        'Fallout Shelter',
        'Fallout 4',
        'The Lost Vikings',
        'Warcraft: Orcs and Humans',
        'Warcraft 2 : Orcs and Humans',
        'Diablo II',
        'Warcraft 3 Reforged',
        'Ben 10: Ultimate Alien - Cosmic Destruction',
        'Ben Hur: Blood of Braves',
        'Berserk: Millennium Falcon Hen Seima Senki no Shō',
        'Beverly Hills Cop',
        'Beyond Good and Evil',
        'Bible Game',
        'Bienvenue chez les Robinson',
        'Big Mutha Truckers',
        'Big Mutha Truckers 2: Truck Me Harder',
        'Bigs',
        'Bigs 2',
        'Biker Mice from Mars',
        'Bikkuri Mouse',
        'Bilbo le Hobbit',
        'Billy et Mandy, aventuriers de l\'au-delà',
        'Billy the Wizard: Rocket Broomstick Racing',
        'Bionicle',
        'Bionicle Heroes',
        'Black',
        'Black and Bruised',
        'Black Buccaneer',
        'Black Cat',
        'Black Market Bowling',
        'Black/Matrix 2',
        'Blade II',
        'Blazing Souls',
        'Bleach: Erabareshi Tamashii',
        'Blitz: The League',
        'Blood: The Last Vampire (Gekan et Jōkan)',
        'Blood Omen 2: Legacy of Kain',
        'Blood Will Tell',
        'BloodRayne',
        'BloodRayne 2',
        'Bloody Roar 3',
    ];

    public static function game() {

        return static::randomElement(self::$games);
    }
}