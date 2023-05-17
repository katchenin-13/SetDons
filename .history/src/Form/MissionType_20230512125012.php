<?php

namespace App\Form;

use App\Entity\Mission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('code')
            // ->add('ordremission')
            // ->add('chefmission')
            // ->add('objectif')
            // ->add('debut')
            // ->add('CreatedAd')
            // ->add('UpdatedAt')
            // ->add('communaute')
            // ->add('utilisateur')

            ->add('code')
            ->add('debut')
            ->add('ordremission', TextType::class, [
                'label' => 'Titre de la mission',
                'attr' => ['placeholder' => 'Titre de la mission']
            ])
            ->add('chefmission', TextType::class, [
                'label' => 'Chef de Mission',
                'attr' => ['placeholder' => 'Nom du chef de mission']
            ])
            ->add('membredeg', TextType::class, [
                'label' => 'Membres de la délégation',
                'attr' => ['placeholder' => 'Noms des membres de la délégation']
            ])
            ->add('objectif', TextType::class, [
                'label' => 'Objectif (s) de la mission',
                'attr' => ['placeholder' => 'Saisir le text']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
