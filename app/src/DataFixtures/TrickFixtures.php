<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    private $counter = 1;

    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->createTrick(
            'Stalefish',
            'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »',
            1,
            $manager
        );
        $this->createTrick(
            'Tail grab',
            'Saisie de la partie arrière de la planche, avec la main arrière. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »).',
            1,
            $manager
        );
        $this->createTrick(
            'Truck driver',
            'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture). Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »).',
            1,
            $manager
        );
        $this->createTrick(
            'Indy',
            'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »',
            1,
            $manager
        );
        $this->createTrick(
            'Mute',
            'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »',
            1,
            $manager
        );
        $this->createTrick(
            'Nose grab',
            'Saisie de la partie avant de la planche, avec la main avant. Un grab consiste à attraper la planche avec la main pendant le saut. Un grab est d\'autant plus réussi que la saisie est longue. De plus, le saut est d\'autant plus esthétique que la saisie du snowboard est franche, ce qui permet au rideur d\'accentuer la torsion de son corps grâce à la tension de sa main sur la planche. On dit alors que le grab est tweaké (le verbe anglais to tweak signifie « pincer » mais a également le sens de « peaufiner »).',
            1,
            $manager
        );
        $this->createTrick(
            '360 ou trois six',
            'Un tour complet, soit 360 degrés. On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. Une rotation peut être agrémentée d\'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu\'un autre.',
            2,
            $manager
        );
        $this->createTrick(
            '1080 ou big foot ',
            'Trois tours complet, soit 1080 degrés. On désigne par le mot « rotation » uniquement des rotations horizontales ; les rotations verticales sont des flips. Le principe est d\'effectuer une rotation horizontale pendant le saut, puis d\'attérir en position switch ou normal. Une rotation peut être agrémentée d\'un grab, ce qui rend le saut plus esthétique mais aussi plus difficile car la position tweakée a tendance à déséquilibrer le rideur et désaxer la rotation. De plus, le sens de la rotation a tendance à favoriser un sens de grab plutôt qu\'un autre.',
            2,
            $manager
        );
        $this->createTrick(
            'Front flip',
            'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation. Les flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées. Néanmoins, en dépit de la difficulté technique relative d\'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks..',
            3,
            $manager
        );
        $this->createTrick(
            'Back flip',
            'Un flip est une rotation verticale. On distingue les front flips, rotations en avant, et les back flips, rotations en arrière. Il est possible de faire plusieurs flips à la suite, et d\'ajouter un grab à la rotation. Les flips agrémentés d\'une vrille existent aussi (Mac Twist, Hakon Flip...), mais de manière beaucoup plus rare, et se confondent souvent avec certaines rotations horizontales désaxées. Néanmoins, en dépit de la difficulté technique relative d\'une telle figure, le danger de retomber sur la tête ou la nuque est réel et conduit certaines stations de ski à interdire de telles figures dans ses snowparks..',
            3,
            $manager
        );
        for ($i = 1; $i <= 35; $i++) {
            $this->createTrick(
                'Trick de test n°' . $i,
                'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                rand(1, 4),
                $manager
            );
        }

        $manager->flush();
    }

    public function createTrick(string $title, string $description, int $category, ObjectManager $manager): Trick
    {
        $trick = new Trick;

        $trick->setTitle($title);
        $trick->setSlug(strtolower($this->slugger->slug($trick->getTitle())));
        $trick->setDescription($description);

        $category = $this->getReference('category-' . $category);
        $trick->setCategory($category);

        $user = $this->getReference('user-' . rand(1, 10));
        $trick->setAuthor($user);

        $manager->persist($trick);

        // Adds a reference to each trick created
        $this->addReference('trick-' . $this->counter, $trick);
        $this->counter++;

        return $trick;
    }

    // Indicates dependent fixtures with DependentFixtureInterface
    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
