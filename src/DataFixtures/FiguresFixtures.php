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
                "groupe" => "grab",
                "user" => "user1"
            ],

            [
                "nom" => "melancholie",
                "description" => "Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant.",
                "groupe" => "grab",
                "user" => "user3"
            ],

            [
                "nom" => "indy",
                "description" => "Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.",
                "groupe" => "grab",
                "user" => "user9"
            ],

            [
                "nom" => "360",
                "description" => "Trois six pour un tour complet.",
                "groupe" => "rotation",
                "user" => "user2"
            ],

            [
                "nom" => "stalefish",
                "description" => "Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.",
                "groupe" => "grab",
                "user" => "user2"
            ],

            [
                "nom" => "tail grab",
                "description" => "Saisie de la partie arrière de la planche, avec la main arrière.",
                "groupe" => "grab",
                "user" => "user5"
            ],

            [
                "nom" => "720",
                "description" => "Sept deux pour deux tours complets.",
                "groupe" => "rotation",
                "user" => "user1"
            ],

            [
                "nom" => "Backside Rodeo 1080",
                "description" => "Trois tours avec une rotation désaxée (Rodeo).",
                "groupe" => "rotation",
                "user" => "user3"
            ],

            [
                "nom" => "Rodeo",
                "description" => "Le rodeo est une rotation désaxée, qui se reconnaît par son aspect vrillé.",
                "groupe" => "rotation",
                "user" => "user5"
            ],

            [
                "nom" => "Cork",
                "description" => "Un cork est une rotation horizontale plus ou moins désaxée, selon un mouvement d'épaules effectué juste au moment du saut.",
                "groupe" => "rotation",
                "user" => "user8"
            ]

        ];
        foreach ($datas as $data) {
            $article = new Figure();
            $article->setName($data["nom"]);
            $article->setDescription($data["description"]);
            $article->setAutor($this->getReference($data["user"]));
            $article->setFigureGroup($this->getReference($data["groupe"]));
            $article->setCreatedAt(date_create());
            $article->setModifiedAt(date_create());
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
