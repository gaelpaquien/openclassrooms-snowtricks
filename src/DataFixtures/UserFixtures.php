<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {
        // Use of faker to generate data
        $faker = Faker\Factory::create('fr_FR');

        // Admin
        $admin = new User();
        $admin->setEmail('admin@email.com');
        $admin->setUsername('admin');
        $admin->setProfilePicture('default.webp');
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'password'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $manager->persist($admin);

        // User
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setUsername($faker->userName);
            $user->setProfilePicture('default.webp');
            $user->setPassword($this->passwordEncoder->hashPassword($user, 'password'));
            $user->setRoles([]);
            $user->setIsVerified(true);
            $manager->persist($user);

            // Add reference of a user
            $this->addReference('user-'.$i, $user);
        }

        $manager->flush();
    }
}
