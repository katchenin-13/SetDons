<?php

namespace App\Form;

use App\Entity\Communaute;
use App\Entity\Demande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('nom')
            // ->add('prenom')
            // ->add('numero')
            // ->add('lieu_habitation')
            // ->add('communaute')
            // ->add('motif')
            // ->add('daterencontre')
            // ->add('statusdemande')

        ->add('motif', TextareaType::class, [
            'label' => 'Motif de la demande d’audience',
            'attr' => ['placeholder' => 'Motif de la demande d’audience']
        ])
            ->add('daterencontre', DateType::class, [
                "label" => "Date de rencontre souhaitée*",
                "required" => true,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => [
                    'class' => 'date',
                    'placeholder' => 'Date de rencontre souhaitée'
                ]
            ])

            ->add('communaute', EntityType::class, [
                'class'        => Communaute::class,
                'label'        => 'Communaute',
                'choice_label' => 'libelle',
                'multiple'     => false,
                'expanded'     => false,
                'required'=>tr,
                'placeholder' => 'Sélectionner une communauté',
                'attr' => ['class' => 'has-select2'],

            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom et Prénom(s) du dmandeur',
                'attr' => ['placeholder' => 'Nom et Prénom(s) du dmandeur']
            ])
           
            ->add('numero', NumberType::class, [
                'label' => 'Nnuméro de téléphone',
                'attr' => ['placeholder' => ' Saisir un numéro de téléphone']
            ])
            ->add('lieu_habitation', TextType::class, [
                'label' => 'Village/Ville',
                'attr' => ['placeholder' => ' Saisir le nom de la ville ou du village']
            ])
           
           
           
            // ->add('CreatedAt')
            // ->add('UpdatedAt')
            // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
}
