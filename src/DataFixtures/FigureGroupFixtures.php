<?php

namespace App\DataFixtures;

use App\Entity\FigureGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FigureGroupFixtures extends Fixture
{
    public const GROUP_REFERENCE = 'figureGroup';



    public function load(ObjectManager $manager)
    {
        $datas = array("grab", "rotation", "flip");

        foreach ($datas as $data) {
            $figureGroup = new FigureGroup();
            $figureGroup->setName($data);


            $manager->persist($figureGroup);
            $this->addReference($data, $figureGroup);
        }

        $manager->flush();
    }
}
