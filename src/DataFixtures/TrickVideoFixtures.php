<?php

namespace App\DataFixtures;

use App\Entity\TrickVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TrickVideoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Use of faker to generate data
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 10; $i++) {
            $video = new TrickVideo;

            $video->setVideo($faker->imageUrl(640, 480, null, true));
            $trick = $this->getReference('trick-'.rand(1, 10));

            $video->setTrick($trick);

            $manager->persist($video);
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
