<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateurs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $utilisateur = new Utilisateurs();
        $utilisateur->setFullname('Amine SBAI');
        $utilisateur->setUsername('aminesbai1');
        $utilisateur->setPassword(
            $this->hasher->hashPassword(
                $user,
                'motdepasse123'
            )
        );
        $utilisateur->setRoles(['ROLE_USER']);
        $manager->persist($utilisateur);

        $manager->flush();
    }
}
