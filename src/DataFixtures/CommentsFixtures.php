<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Use of faker to generate data
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $comment = new Comments();

            $comment->setContent($faker->text(50));

            $user = $this->getReference('user-'.rand(1, 10));
            $comment->setAuthor($user);

            $trick = $this->getReference('trick-'.rand(1, 10));
            $comment->setTrick($trick);

            $manager->persist($comment);
        }

        $manager->flush();
    }

    // Indicates dependent fixtures with DependentFixtureInterface
    public function getDependencies(): array
    {
        return [
            UsersFixtures::class,
            TricksFixtures::class
        ];
    }
}
