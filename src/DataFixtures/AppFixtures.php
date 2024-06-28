<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $password = $this->hasher->hashPassword($user, '1234');
        $user
            ->setUsername('admin')
            ->setRoles(['ROLE_USER','ROLE_ADMIN'])
            ->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
