<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UtilisateurFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEFAULT_USER_REFERENCE = 'default-user';

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {
        $utilisateur = new Utilisateur();
        $utilisateur->addGroupe($this->getReference(GroupeFixtures::ADMIN_GROUP_REFERENCE));
        $utilisateur->setUsername('soro');
        $utilisateur->setPassword($this->hasher->hashPassword($utilisateur, 'soro'));
        $utilisateur->setEmploye($this->getReference(EmployeFixtures::ADMIN_EMPLOYE_REFERENCE));
        // $product = new Product();
        // $manager->persist($product);
        $manager->persist($utilisateur);

        $manager->flush();

        $this->addReference(self::DEFAULT_USER_REFERENCE, $utilisateur);
    }


    public function getDependencies()
    {
        return [
            EmployeFixtures::class,
            GroupeFixtures::class,
        ];
    }
}
