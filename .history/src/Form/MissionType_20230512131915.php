<?php

namespace App\Form;

use App\Entity\Communaute;
use App\Entity\Mission;
use App\Service\Champs;
use COM;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    

   
    public function buildForm(FormBuilderInterface $builder, array $options,Champs $champs): void
    {
        $builder
 
            ->add('code', TextType::class, v->getConfiguration("Code de la mission", "code de la mission"))

            ->add('debut', DateType::class, [
            "label" => "Date de debut de la mission",
            "required" => false,
            "widget" => 'single_text',
            "input_format" => 'Y-m-d',
            "by_reference" => true,
            "empty_data" => '',
            'attr' => ['class' => 'date']
            ])

            ->add('communaute', EntityType::class, [
                'class'        => Communaute::class,
                'label'        => 'Communaute',
                'choice_label' => 'libelle',
                'multiple'     => false,
                'expanded'     => false,
                'placeholder' => 'Choisir une localité',
                'attr' => ['class' => 'has-select2'],

            ])
            ->add('ordremission', TextType::class, $champs->getConfiguration("Titre de la mission", "Titre de la mission"))
            ->add('chefmission', TextType::class, $champs->getConfiguration("Chef de Mission", "Nom du chef de mission"))
            ->add('membredeg', TextType::class,$champs->getConfiguration("Membres de la délégation", "Noms des membres de la délégation"))
            ->add('objectif', TextType::class, $champs->getConfiguration("Objectif (s) de la mission", "l'objectif de la mission"))

              // ->add('CreatedAd')
            // ->add('UpdatedAt')
             // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
