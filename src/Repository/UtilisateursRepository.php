<?php

namespace App\Repository;

use App\Entity\Utilisateurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UtilisateursRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateurs::class);
    }

    /**
     * Utilisé pour upgrade (rehash) le mot de passe de l'utilisateur automatiquement au fil du tps
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $utilisateur, string $newHashedPassword): void
    {
        if (!$utilisateur instanceof Utilisateurs) {
            throw new UnsupportedUserException(sprintf('"%s" pas supporté', $utilisateur::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($utilisateur);
        $this->getEntityManager()->flush();
    }

}
