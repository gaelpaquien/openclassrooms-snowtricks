<?php

namespace App\DataFixtures;

use App\Entity\TrickCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickCategoryFixtures extends Fixture
{
    private $counter = 1;

    public function load(ObjectManager $manager): void
    {
        $this->createTrickCategory('Grabs', $manager);
        $this->createTrickCategory('Rotations', $manager);
        $this->createTrickCategory('Flips', $manager);
        $this->createTrickCategory('Slides', $manager);

        $manager->flush();
    }

    public function createTrickCategory(string $name, ObjectManager $manager): TrickCategory
    {
        $category = new TrickCategory;

        $category->setName($name);

        $manager->persist($category);

        // Adds a reference to each category created
        $this->addReference('category-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
