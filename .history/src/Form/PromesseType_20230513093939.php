<?php

namespace App\Form;

use App\Entity\Communaute;
use App\Entity\Promesse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromesseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('nom')
            // ->add('numero')
            // ->add('email')
            // ->add('communaute')

            //->add('statusdon')
            //->add('dateremise')
            //->add('CreatedAt')
            //->add('UpdatedAt')

            //->add('utilisateur')
            ->add('communaute', EntityType::class, [
                'class'        => Communaute::class,
                'label'        => 'Communaute',
                'choice_label' => 'libelle',
                'multiple'     => false,
                'expanded'     => false,
                'placeholder' => 'Choisir une localité',
                'attr' => ['class' => 'has-select2'],

            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom de récipiendaire',
                'attr' => ['placeholder' => 'Saisir le nom du récipiendaire']
            ])
            ->add('numero', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => ['placeholder' => ' Saisir un numéro de téléphone']
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Saisir une adresse mail
            ']
            ])

            ->add('dateremise', DateType::class, [
                "label" => "Date à laquelle  ",
                "required" => false,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date']
            ])

            ->add('fieldon', CollectionType::class, [
                'entry_type' => FieldonType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'label' => false,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promesse::class,
        ]);
    }
}
