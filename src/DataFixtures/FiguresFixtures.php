<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\FigureGroupFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FiguresFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $datas = array(
            array(
                "nom" => "mute",
                "description" => "Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.",
                "groupe" => "grab"
            ),
            array(
                "nom" => "melancholie",
                "description" => "Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.",
                "groupe" => "grab"
            ),
            array(
                "nom" => "indy",
                "description" => "Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.",
                "groupe" => "grab"
            ),
            array(
                "nom" => "360",
                "description" => "Trois six pour un tour complet.",
                "groupe" => "rotation"
            ),
            array(
                "nom" => "stalefish",
                "description" => "Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.",
                "groupe" => "grab"
            ),
            array(
                "nom" => "tail grab",
                "description" => "Saisie de la partie arrière de la planche, avec la main arrière.",
                "groupe" => "grab"
            ),
            array(
                "nom" => "720",
                "description" => "Sept deux pour deux tours complets.",
                "groupe" => "rotation"
            )
        );
        foreach ($datas as $data) {
            $article = new Figure();
            $article->setName($data["nom"]);
            $article->setDescription($data["description"]);
            $article->setAutor($this->getReference(random_int(1, 10)));
            $article->setFigureGroup($this->getReference($data["groupe"]));
            $manager->persist($article);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            FigureGroupFixtures::class
        );
    }
}
