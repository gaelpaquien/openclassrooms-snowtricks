<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Use of faker to generate data
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 1000; $i++) {
            $comment = new Comment();

            $comment->setContent($faker->text(50));

            $user = $this->getReference('user-'.rand(1, 10));
            $comment->setAuthor($user);

            $trick = $this->getReference('trick-'.rand(1, 45));
            $comment->setTrick($trick);

            $manager->persist($comment);
        }

        $manager->flush();
    }

    // Indicates dependent fixtures with DependentFixtureInterface
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TrickFixtures::class
        ];
    }
}
