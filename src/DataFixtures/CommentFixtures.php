<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $datas = [
            [
                "user" => "user1",
                "content" => "C'est trop classe!",
                "figure" => "mute"
            ],
            [
                "user" => "user2",
                "content" => "J'ai jamais réussi...",
                "figure" => "mute"
            ],
            [
                "user" => "user3",
                "content" => "Trop facile!",
                "figure" => "mute"
            ],
            [
                "user" => "user6",
                "content" => "Bien se pencher en avant pour y arriver.",
                "figure" => "360"
            ],
            [
                "user" => "user9",
                "content" => "La plus simple des rotations.",
                "figure" => "360"
            ],
            [
                "user" => "user8",
                "content" => "Comme sur un cheval!",
                "figure" => "Rodeo"
            ],
            [
                "user" => "user1",
                "content" => "La vidéo explique bien.",
                "figure" => "Rodeo"
            ],
            [
                "user" => "user1",
                "content" => "La base!",
                "figure" => "tail grab"
            ],
            [
                "user" => "user4",
                "content" => "J ai mangé la neige plus d'une fois en faisant ça.",
                "figure" => "tail grab"
            ],
            [
                "user" => "user1",
                "content" => "Mdr",
                "figure" => "tail grab"
            ],
        ];
        foreach ($datas as $data) {
            $comment = new Comment();
            $comment->setContent($data['content']);
            $comment->setUser($this->getReference($data['user']));
            $comment->setRelatedFigure($this->getReference(($data['figure'])));
            $comment->setDate(date_create());
            $manager->persist($comment);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            FiguresFixtures::class
        ];
    }
}
