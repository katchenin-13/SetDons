<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Communaute;
use App\Entity\Localite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunauteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('code')
            // ->add('libelle')
            // ->add('nbestmember')
            // ->add('createdup')
            // ->add('updatedup')
            // ->add('categorie')
            // ->add('localite')
            // ->add('utilisateur')
            ->add('code', TextType::class,[
                'label' =>'Code',
               'attr' => ['placeholder' => 'Saisir le code  de la communauté']
           ])
            ->add('libelle', TextType::class,[
                'label' =>'Nom de la communauté',
               'attr' => ['placeholder' => 'Saisir le nom de la communauté']
           ])
           ->add('nompointfocal', TextType::class,[
            'label' =>'Nom du point focal',
            'attr' => ['placeholder' => 'Saisir le nom de la communauté']
           ])
          ->add('numero', NumberType::class,[
            'label' =>'Numéro de téléphone',
            'attr' => ['placeholder' => 'Saisir le numéro de téléphone']
           ])
          ->add('nombreestimatifmemb', TextType::class,[
            'label' =>'Nombre estimatif des membres',
            'attr' => ['placeholder' => 'Saisir le nombre estimatif des membres']
           ])   
           ->add('email', EmailType::class,[
            'label' =>'Email',
            'attr' => ['placeholder' => 'Saisir une adresse mail']
           ]) 
               
            ->add('categorie', EntityType::class, [
                    'class'        => Categorie::class,
                    'label'        => 'Catégorie',
                    'choice_label' => 'libelle',
                    'multiple'     => false,
                    'expanded'     => false,
                    'placeholder' => 'Sélectionner une catégorie',
                    'attr' => ['class' => 'has-select2'],
                   
                ])
           ->add('localite', EntityType::class, [
                    'class'        => Localite::class,
                    'label'        => 'Localite',
                    'choice_label' => 'libelle',
                    'multiple'     => false,
                    'expanded'     => false,
                    'placeholder' => 'Choisir une localité',
                    'attr' => ['class' => 'has-select2'],
                   
                ])
                ->add('createdup')
                ->add('updatedup')
                // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Communaute::class,
        ]);
    }
}
