<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $suer = new User();
            $suer->setUsername('username '.$i);
            $suer->setPassword('Test1234');
            $suer->setEmail($i. '@live.fr');
            $manager->persist($suer);
        }

        $manager->flush();
    }
}
