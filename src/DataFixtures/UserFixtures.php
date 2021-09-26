<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setName("user" . $i);
            $user->setMail("test" . $i . "@mail.com");
            $user->setPassword("password");
            $user->setMailIsValidate(1);
            $manager->persist($user);
            $this->addReference($i, $user);
        }


        $manager->flush();
    }
}
