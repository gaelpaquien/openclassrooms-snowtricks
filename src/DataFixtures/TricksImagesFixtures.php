<?php

namespace App\DataFixtures;

use App\Entity\TricksImages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TricksImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Use of faker to generate data
        $faker = Faker\Factory::create('fr_FR');

        // Create 10 tricks_images
        for ($i = 1; $i <= 10; $i++) {
            $image = new TricksImages;

            $image->setImage($faker->imageUrl(640, 480, null, true));

            $trick = $this->getReference('trick-'.rand(1, 10));
            $image->setTricks($trick);

            $manager->persist($image);
        }

        $manager->flush();
    }

    // Indicates dependent fixtures with DependentFixtureInterface
    public function getDependencies(): array
    {
        return [
            TricksFixtures::class
        ];
    }
}
