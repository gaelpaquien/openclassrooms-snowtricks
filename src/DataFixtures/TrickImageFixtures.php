<?php

namespace App\DataFixtures;

use App\Entity\TrickImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 45; $i++) {
            $image = new TrickImage;

            $image->setName('TrickFixture.jpg');

            $trick = $this->getReference('trick-' . $i);
            $image->setTrick($trick);

            $manager->persist($image);
        }

        $manager->flush();
    }

    // Indicates dependent fixtures with DependentFixtureInterface
    public function getDependencies(): array
    {
        return [
            TrickFixtures::class
        ];
    }
}
