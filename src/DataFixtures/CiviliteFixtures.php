<?php

namespace App\DataFixtures;

use App\Entity\Civilite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CiviliteFixtures extends Fixture
{
    public const DEFAULT_CIVILITE_REFERENCE = 'default-civilite';
    public function load(ObjectManager $manager): void
    {
        $civilite = new Civilite();
        $civilite->setLibelle('Monsieur');
        $civilite->setCode('M.');
        // $product = new Product();
        $manager->persist($civilite);

        $manager->flush();

        $this->addReference(self::DEFAULT_CIVILITE_REFERENCE, $civilite);
    }
}
