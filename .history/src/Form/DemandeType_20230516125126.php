<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
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

        ->add('motif', TextType::class, [
            'label' => 'Motif de la demande d’audience',
            'attr' => ['placeholder' => 'Motif de la demande d’audience']
        ])
            ->add('daterencontre', DateType::class, [
                "label" => "Date de rencontre souhaitée*",
                "required" => false,
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
                'placeholder' => 'Sélectionner une communauté                ',
                'attr' => ['class' => 'has-select2'],

            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom du chef de délégation',
                'attr' => ['placeholder' => 'Nom du chef de délégation']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Prénom']
            ])
            ->add('numero', NumberType::class, [
                'label' => 'Nnuméro de téléphone',
                'attr' => ['placeholder' => ' Saisir un numéro de téléphone']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Saisir une adresse mail
                ']
            ])
            ->add('nombreparticipant', NumberType::class, [
                'label' => 'Nombre de participants',
                'attr' => ['placeholder' => ' Saisir le nombre de participants']
            ])

            ->add('observation', TextareaType::class, [
                'label' => 'Observation'
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
