<?php

namespace App\Form;

use App\Entity\Localite;
use App\Entity\Categorie;
use App\Entity\Communaute;
use Gedmo\Mapping\Annotation\Tree;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CommunauteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('code')
            // ->add('libelle')
            // ->add('nbestmember')
            // ->add('categorie')
            // ->add('localite')
            ->add('code', null, [
                'label' => 'Code',
                'required' => True,
                'attr' => ['placeholder' => 'Saisir le code']])
            ->add('libelle', TextType::class,[
                'label' =>'Nom de la communauté',
                'required' => True,
               'attr' => ['placeholder' => 'Saisir le nom de la communauté']
           ])
        //    ->add('nompointfocal', TextType::class,[
        //     'label' =>'Nom du point focal',
        //     'attr' => ['placeholder' => 'Saisir le nom de la communauté']
        //    ])
        //   ->add('numero', NumberType::class,[
        //     'label' =>'Numéro de téléphone',
        //     'attr' => ['placeholder' => 'Saisir le numéro de téléphone']
        //    ])
          ->add('nbestmember', TextType::class,[
            'label' =>'Nombre estimatif des membres',
            'required' => false,
            'attr' => ['placeholder' => 'Saisir le nombre estimatif des membres']
           ])   
        //    ->add('email', EmailType::class,[
        //     'label' =>'Email', 
        //     'attr' => ['placeholder' => 'Saisir une adresse mail']
        //    ]) 
               
            ->add('categorie', EntityType::class, [
                    'class'        => Categorie::class,
                    'label'        => 'Catégorie',
                    'required' => True,
                    'choice_label' => 'libelle',
                    'multiple'     => false,
                    'expanded'     => false,
                    'placeholder' => 'Sélectionner une catégorie',
                    'attr' => ['class' => 'has-select2'],
                   
                ])
           ->add('localite', EntityType::class, [
                    'class'        => Localite::class,
                    'label'        => 'Localite',
                    'required' => True,
                    'choice_label' => 'libelle',
                
                    'multiple'     => false,
                    'expanded'     => false,
                    'placeholder' => 'Choisir une localité',
                    'attr' => ['class' => 'has-select2'],
                   
                ])
          

            ->add('nompfs', CollectionType::class, [
                'entry_type' => NompfType::class,
                'entry_options' => [
                    'label' => false,
                    'required'=>True
                    
                ],
                'allow_add' => true,
                'label' => false,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
                ])
            ->add('numeropfs',CollectionType::class, [
                    'entry_type' => NumeropfType::class,
                    'entry_options' => [
                        'label' => false,
                    ],
                    'allow_add' => true,
                    'label' => false,
                    'by_reference' => false,
                    'allow_delete' => true,
                    'prototype' => true,
                    'attr' => ['placeholder' => '+2250154865252']
                    ])
            ->add('emailpfs', CollectionType::class, [
                        'entry_type' => EmailpfType::class,
                        'entry_options' => [
                            'label' => false,
                        ],
                        'allow_add' => true,
                        'label' => false,
                        'by_reference' => false,
                        'allow_delete' => true,
                        'prototype' => true,
                        ])
                
        
            // ->add('utilisateur')
            // ->add('CreatedAt')
            // ->add('UpdatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Communaute::class,
        ]);
    }
}
