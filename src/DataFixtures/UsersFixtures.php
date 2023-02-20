<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger
    ){}

    public function load(ObjectManager $manager): void
    {
        // Use of faker to generate data
        $faker = Faker\Factory::create('fr_FR');

        // Create 1 admin
        $admin = new Users();

        $admin->setEmail('admin@email.com');
        $admin->setUsername('Admin');
        $admin->setProfilePicture($faker->imageUrl(640, 480, null, true));
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $admin->setResetToken('');

        $manager->persist($admin);

        // Create 10 users
        for($i = 1; $i <= 10; $i++) {
            $user = new Users();

            $user->setEmail($faker->email);
            $user->setUsername($faker->userName);
            $user->setProfilePicture($faker->imageUrl(640, 480, null, true));
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, 'password')
            );
            $user->setRoles([]);
            $user->setIsVerified(true);
            $user->setResetToken('');

            $manager->persist($user);

            // Add reference of a user
            $this->addReference('user-'.$i, $user);
        }

        $manager->flush();
    }
}
