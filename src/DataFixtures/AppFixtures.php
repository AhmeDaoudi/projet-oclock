<?php

namespace App\DataFixtures;

use App\DataFixtures\MyCustomNativeLoader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $om)
    {
        
        $loader = new MyCustomNativeLoader();
        $entities = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();
        
        foreach($entities as $entity) {

            $om->persist($entity);
        }

        $om->flush();
    }
}
