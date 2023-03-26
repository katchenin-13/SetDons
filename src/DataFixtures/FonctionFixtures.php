<?php

namespace App\DataFixtures;

use App\Entity\Fonction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FonctionFixtures extends Fixture
{
    public const DEFAULT_FONCTION_EMPLOYE = 'default-fonction';
    public function load(ObjectManager $manager): void
    {
        $fonction = new Fonction();
        $fonction->setLibelle('Administrateur');
        $fonction->setCode('ADM');
        $manager->persist($fonction);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();

        $this->addReference(self::DEFAULT_FONCTION_EMPLOYE, $fonction);
    }
}
