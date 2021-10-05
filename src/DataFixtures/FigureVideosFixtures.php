<?php

namespace App\DataFixtures;

use App\Entity\FigureVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FigureVideosFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $datas = [
            [
                "figure" => "mute",
                "link" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/CflYbNXZU3Q" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ],
            [
                "figure" => "indy",
                "link" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/iKkhKekZNQ8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ],
            [
                "figure" => "360",
                "link" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/JJy39dO_PPE" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ],
            [
                "figure" => "melancholie",
                "link" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/KEdFwJ4SWq4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ],
            [
                "figure" => "stalefish",
                "link" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/f9FjhCt_w2U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ],
            [
                "figure" => "tail grab",
                "link" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/id8VKl9RVQw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ],
            [
                "figure" => "720",
                "link" => '<iframe width="560" height="315" src="https://www.youtube.com/embed/1vtZXU15e38" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
            ]
        ];
        foreach ($datas as $data) {
            $video = new FigureVideo();
            $video->setLink($data['link']);
            $video->setRelatedFigure($this->getReference($data["figure"]));
            $manager->persist($video);
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
