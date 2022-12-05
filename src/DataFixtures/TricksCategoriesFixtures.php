<?php

namespace App\DataFixtures;

use App\Entity\TricksCategories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TricksCategoriesFixtures extends Fixture
{
    private $counter = 1;

    public function load(ObjectManager $manager): void
    {
        // Create 4 tricks categories
        $this->createTricksCategory('Grabs', $manager);
        $this->createTricksCategory('Rotations', $manager);
        $this->createTricksCategory('Flips', $manager);
        $this->createTricksCategory('Slides', $manager);

        $manager->flush();
    }

    public function createTricksCategory(string $name, ObjectManager $manager): TricksCategories
    {
        $category = new TricksCategories;

        $category->setName($name);

        $manager->persist($category);

        // Adds a reference to each category created
        $this->addReference('category-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
