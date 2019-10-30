<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstname);
            $user->setLastname($faker->lastname);
            $user->setEmail($faker->mail);
            $user->setAddress($faker->address);
            $user->setZipcode($faker->firstname);
            $user->setCity($faker->firstname);
            $user->setCountry($faker->firstname);
       
            $manager->persist($product);
        }

        $manager->flush();
    }
}
