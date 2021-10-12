<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\FigureGroupFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FiguresFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct()
    {
        $this->slugger = new AsciiSlugger();
    }
    public function load(ObjectManager $manager)
    {
        $datas = [
            [
                "nom" => "mute",
                "description" => "Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.",
                "groupe" => "grab"
            ],

            [
                "nom" => "melancholie",
                "description" => "Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.",
                "groupe" => "grab"
            ],

            [
                "nom" => "indy",
                "description" => "Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.",
                "groupe" => "grab"
            ],

            [
                "nom" => "360",
                "description" => "Trois six pour un tour complet.",
                "groupe" => "rotation"
            ],

            [
                "nom" => "stalefish",
                "description" => "Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.",
                "groupe" => "grab"
            ],

            [
                "nom" => "tail grab",
                "description" => "Saisie de la partie arrière de la planche, avec la main arrière.",
                "groupe" => "grab"
            ],

            [
                "nom" => "720",
                "description" => "Sept deux pour deux tours complets.",
                "groupe" => "rotation"
            ],

            [
                "nom" => "Backside Rodeo 1080",
                "description" => "Trois tours avec une rotation désaxée (Rodeo).",
                "groupe" => "rotation"
            ],

            [
                "nom" => "Rodeo",
                "description" => "Le rodeo est une rotation désaxée, qui se reconnaît par son aspect vrillé.",
                "groupe" => "rotation"
            ],

            [
                "nom" => "Cork",
                "description" => "Un cork est une rotation horizontale plus ou moins désaxée, selon un mouvement d'épaules effectué juste au moment du saut.",
                "groupe" => "rotation"
            ]

        ];
        foreach ($datas as $data) {
            $article = new Figure();
            $article->setName($data["nom"]);
            $article->setDescription($data["description"]);
            $article->setAutor($this->getReference(random_int(1, 10)));
            $article->setFigureGroup($this->getReference($data["groupe"]));
            $article->setCreatedAt(date_create());
            $article->setSlug($this->slugger->slug($data["nom"]));
            $manager->persist($article);
            $this->addReference($data['nom'], $article);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            FigureGroupFixtures::class
        ];
    }
}
