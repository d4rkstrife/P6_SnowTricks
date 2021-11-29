<?php

namespace App\DataFixtures;

use App\Entity\ProfilPicture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProfilPictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $datas = [
            [
                "file" => "01w.jpg",
                "user" => "user1"
            ],
            [
                "file" => "02m.jpg",
                "user" => "user2"
            ],
            [
                "file" => "03w.jpg",
                "user" => "user3"
            ],
            [
                "file" => "04w.jpg",
                "user" => "user4"
            ],
            [
                "file" => "05w.jpg",
                "user" => "user5"
            ],
            [
                "file" => "06w.jpg",
                "user" => "user6"
            ],
            [
                "file" => "07w.jpg",
                "user" => "user7"
            ],
            [
                "file" => "08m.jpg",
                "user" => "user8"
            ],
            [
                "file" => "09w.jpg",
                "user" => "user9"
            ],
            [
                "file" => "10w.jpg",
                "user" => "user10"
            ]
        ];
        $i = 1;

        foreach ($datas as $data) {
            $profilPicture = new ProfilPicture();
            $profilPicture->setLink($data['file']);
            $profilPicture->setRelatedUser($this->getReference($data['user']));
            $manager->persist($profilPicture);
        }


        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
