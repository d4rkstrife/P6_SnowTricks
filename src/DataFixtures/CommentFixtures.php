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
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "mute"
            ],
            [
                "user" => "user2",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "mute"
            ],
            [
                "user" => "user3",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "mute"
            ],
            [
                "user" => "user6",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "360"
            ],
            [
                "user" => "user9",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "360"
            ],
            [
                "user" => "user8",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "Rodeo"
            ],
            [
                "user" => "user1",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "Rodeo"
            ],
            [
                "user" => "user1",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "tail grab"
            ],
            [
                "user" => "user4",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
                "figure" => "tail grab"
            ],
            [
                "user" => "user1",
                "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer metus urna, condimentum ut turpis ultrices, semper porta orci. Nulla at efficitur augue. Vivamus semper elit vitae mollis convallis. Etiam risus lectus, aliquet non lacus quis, euismod viverra ipsum. Nulla porta urna vitae sagittis scelerisque.",
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
