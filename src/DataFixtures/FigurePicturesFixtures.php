<?php

namespace App\DataFixtures;

use App\DataFixtures\FiguresFixtures;
use App\Entity\FigurePicture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FigurePicturesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $datas = [
            [
                "figure" => "mute",
                "filename" => "mute1.jpg",
                "main" => 1
            ],
            [
                "figure" => "mute",
                "filename" => "mute2.jpg",
                "main" => 0
            ],
            [
                "figure" => "mute",
                "filename" => "mute3.jpg",
                "main" => 0
            ],
            [
                "figure" => "indy",
                "filename" => "indy.jpg",
                "main" => 1
            ],
            [
                "figure" => "indy",
                "filename" => "indy2.jpg",
                "main" => 0
            ],
            [
                "figure" => "360",
                "filename" => "360.jpg",
                "main" => 1
            ],
            [
                "figure" => "melancholie",
                "filename" => "melancholy.jpg",
                "main" => 1
            ],
            [
                "figure" => "stalefish",
                "filename" => "stalefish.jpg",
                "main" => 1
            ],
            [
                "figure" => "stalefish",
                "filename" => "stalefish1.jpg",
                "main" => 0
            ],
            [
                "figure" => "tail grab",
                "filename" => "tailgrab.jpg",
                "main" => 1
            ],
            [
                "figure" => "tail grab",
                "filename" => "tailgrab1.jpg",
                "main" => 0
            ],
            [
                "figure" => "720",
                "filename" => "720.jpg",
                "main" => 1
            ],
            [
                "figure" => "Rodeo",
                "filename" => "rodeo.jpg",
                "main" => 1
            ],
            [
                "figure" => "Cork",
                "filename" => "cork.jpg",
                "main" => 1
            ],
            [
                "figure" => "Backside Rodeo 1080",
                "filename" => "Backsiderodeo.jpg",
                "main" => 1
            ]
        ];
        foreach ($datas as $data) {
            $picture = new FigurePicture();
            $picture->setFilename($data['filename']);
            $picture->setRelatedFigure($this->getReference($data["figure"]));
            $picture->setMain($data['main']);
            $manager->persist($picture);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            FiguresFixtures::class
        );
    }
}
