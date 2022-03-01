<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $password = "Password1";
            $user = new User();
            $user->setName("user" . $i);
            $user->setEmail("test" . $i . "@mail.com");
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setMailIsValidate(1);
            $manager->persist($user);
            $this->addReference($user->getName(), $user);
        }


        $manager->flush();
    }
}
