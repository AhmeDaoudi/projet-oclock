<?php

namespace App\DataFixtures;

use App\DataFixtures\Faker\CategoryProvider;
use App\DataFixtures\Faker\PlatformProvider;
use App\DataFixtures\Faker\DeveloperProvider;
use App\DataFixtures\Faker\GameProvider;
use Faker\Factory as FakerGeneratorFactory;
use Faker\Generator as FakerGenerator;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;

class MyCustomNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = FakerGeneratorFactory::create(parent::LOCALE);
        $generator->addProvider(new AliceProvider());
        $generator->addProvider(new PlatformProvider($generator));
        $generator->addProvider(new DeveloperProvider($generator));
        $generator->addProvider(new CategoryProvider($generator));
        $generator->addProvider(new GameProvider($generator));
        $generator->seed($this->getSeed());
        return $generator;
    }
}