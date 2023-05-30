<?php

namespace App\Form;

use App\Entity\Rapportmission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RapportmissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            
            // ->add('libelle')
            ->add('libelle', TextType::class, [
                'label' => 'Titre de la mission',
                'attr' => ['placeholder' => 'Titre de la mission']
            ])
            ->add('chefmission', TextType::class, [
                'label' => 'Chef de Mission',
                'attr' => ['placeholder' => 'Nom du chef de missi']
            ])
            ->add('membredeg', TextType::class, [
                'label' => 'Membres de la délégation',
                'attr' => ['placeholder' => 'Noms des membres de la délégation']
            ])
            ->add('objectif', TextType::class, [
                'label' => 'Objectif (s) de la mission',
                'attr' => ['placeholder' => 'Saisir le text']
            ])
            ->add('action', TextType::class, [
                'label' => 'Action (s) réalisée (s)',
                'attr' => ['placeholder' => 'Saisir le text']
            ])
            ->add('opportunite', TextType::class, [
                'label' => 'Saisir un texte',
                'attr' => ['placeholder' => 'Saisir un texte']
            ])
            ->add('difficulte', TextType::class, [
                'label' => 'Difficulté (s) rencontrée ',
                'attr' => ['placeholder' => 'Saisir le text']
            ])
        
          
            ->add('etape')
             // ->add('UpdatedAt')
            // ->add('CreatedAt')
            //->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapportmission::class,
        ]);
    }
}