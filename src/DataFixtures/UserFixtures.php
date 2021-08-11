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
            $user = new User();
            $user->setUsername('username '.$i);
            $user->setPassword('test1234');
            $user->setEmail($i. ' @live.fr');
            $manager->persist($user);
        }
        $manager->flush();
    }
}