<?php

namespace App\Form;

use App\Entity\Don;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Beneficiaire', CollectionType::class, [
            'entry_type' => BeneficiaireType::class,
            'entry_options' => [
                'label' => false,
            ],
            'allow_add' => true,
            'label' => false,
            'by_reference' => false,
            'allow_delete' => true,
            'prototype' => true,
            ])
            // ->add('dateremise')
            ->add('dateremise', DateType::class, [
                "label" => "Date de remise",
                "required" => false,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date']
                ])
            // ->add('remispar')
            ->add('remispar', TextType::class,[
                'label' =>'Remis par',
                'attr' => ['placeholder' => 'Saisir le nom de la personne ayant remis le don']
               ])
            // ->add('promesse')
        
            // ->add('statusdon')
            // ->add('mentions')
            // ->add('UpdatedAt')
            // ->add('CreatedAt')
            // ->add('utilisateur')
         
        cz
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}
