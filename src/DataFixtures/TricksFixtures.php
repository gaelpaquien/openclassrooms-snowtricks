<?php

namespace App\DataFixtures;

use App\Entity\Tricks;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class TricksFixtures extends Fixture implements DependentFixtureInterface
{
    private $counter = 1;

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        // Create 10 tricks
        $this->createTricks(
            'Stalefish', 
            'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière.', 
            1, 
            $manager
        );
        $this->createTricks(
            'Tail grab',
            'Saisie de la partie arrière de la planche, avec la main arrière.',
            1,
            $manager
        );
        $this->createTricks(
            'Truck driver',
            'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture).',
            1,
            $manager
        );
        $this->createTricks(
            'Indy',
            'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière',
            1,
            $manager
        );
        $this->createTricks(
            'Mute',
            'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.',
            1,
            $manager
        );
        $this->createTricks(
            'Nose grab',
            'Saisie de la partie avant de la planche, avec la main avant',
            1,
            $manager
        );
        $this->createTricks(
            '360 ou trois six',
            'Un tour complet, soit 360 degrés.',
            2,
            $manager
        );
        $this->createTricks(
            '1080 ou big foot ',
            'Trois tours complet, soit 1080 degrés.',
            2,
            $manager
        );
        $this->createTricks(
            'Front flip',
            'Rotation verticale en avant de un tour complet.',
            3,
            $manager
        );
        $this->createTricks(
            'Back flip',
            'Rotation verticale en arrière de un tour complet.',
            3,
            $manager
        );
        $manager->flush();
    }

    public function createTricks(string $title, string $description, int $category, ObjectManager $manager): Tricks {
        $trick = new Tricks;

        $trick->setTitle($title);
        $trick->setSlug(strtolower($this->slugger->slug($trick->getTitle())));
        $trick->setDescription($description);

        $category = $this->getReference('category-'.$category);
        $trick->setCategory($category);

        $user = $this->getReference('user-'.rand(1, 10));
        $trick->setAuthorId($user);

        $manager->persist($trick);

        // Adds a reference to each trick created
        $this->addReference('trick-'.$this->counter, $trick);
        $this->counter++;

        return $trick;
    }

    // Indicates dependent fixtures with DependentFixtureInterface
    public function getDependencies(): array
    {
        return [
            UsersFixtures::class
        ];
    }
}
