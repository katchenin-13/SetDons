<?php

namespace App\Form;

use App\Entity\Civilite;
use App\Entity\Entreprise;
use App\Entity\Fonction;
use App\Entity\Employe;
use App\Entity\EmployeService;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', EntityType::class, [
                'class' => Civilite::class,
                'choice_label' => 'code',
                'label' => 'Civilité',
                'attr' => ['class' => 'has-select2']
            ])
            ->add('matricule', null, ['label' => 'Matricule'])
            ->add('contact', null, ['label' => 'Contact(s)'])
            ->add('adresseMail', EmailType::class, ['label' => 'Adresse E-mail', 'required' => false, 'empty_data' => ''])
            ->add('nom', null, ['label' => 'Nom'])
            ->add('prenom', null, ['label' => 'Prénoms'])
            ->add('fonction', EntityType::class, [
                'class' => Fonction::class,
                'choice_label' => 'libelle',
                'label' => 'Fonction',
                'attr' => ['class' => 'has-select2']
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'libelle',
                'label' => 'Direction',
                'attr' => ['class' => 'has-select2']
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'denomination',
                'label' => 'Entreprise',
                'attr' => ['class' => 'has-select2']
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
