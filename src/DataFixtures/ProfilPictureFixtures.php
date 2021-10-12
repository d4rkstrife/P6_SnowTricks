<?php

namespace App\DataFixtures;

use App\Entity\ProfilPicture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilPictureFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $datas = [
            [
                "file" => "01w.jpg",
                "user" => "user1"
            ],
            [
                "file" => "02w.jpg",
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
                "file" => "08w.jpg",
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
            $profilPicture->setRelatedUser($this->getReference($i));
            $i++;
            $manager->persist($profilPicture);
        }

        // $manager->persist($product);

        $manager->flush();
    }
}
