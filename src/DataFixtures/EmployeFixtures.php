<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EmployeFixtures extends Fixture implements DependentFixtureInterface
{
    public const ADMIN_EMPLOYE_REFERENCE = 'admin-employe';
    public function load(ObjectManager $manager): void
    {
        $employe = new Employe();
        $employe->setNom('Soro');
        $employe->setPrenom('Katchenin');
        $employe->setCivilite($this->getReference(CiviliteFixtures::DEFAULT_CIVILITE_REFERENCE));
        $employe->setFonction($this->getReference(FonctionFixtures::DEFAULT_FONCTION_EMPLOYE));
        $employe->setContact('00000000');
        $employe->setMatricule('00000000');
        $employe->setAdresseMail('admin@text.com');
        // $product = new Product();
        $manager->persist($employe);

        $manager->flush();

        $this->addReference(self::ADMIN_EMPLOYE_REFERENCE, $employe);
    }

    public function getDependencies()
    {
        return [
            CiviliteFixtures::class,
            FonctionFixtures::class
        ];
    }
}
