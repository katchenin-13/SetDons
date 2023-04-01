<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\Communaute;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeneficiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('communaute', EntityType::class, [
            'class'        => Communaute::class,
            'label'        => 'Communaute',
            'choice_label' => 'libelle',
            'multiple'     => false,
            'expanded'     => false,
            'placeholder' => 'Choisir une localité',
            'attr' => ['class' => 'has-select2'],
           
        ])
        ->add('nom', TextType::class,[
            'label' =>'Nom de récipiendaire',
            'attr' => ['placeholder' => 'Saisir le nom du récipiendaire']
           ])
    ->add('numero', TextType::class,[
            'label' => 'Numéro de téléphone',
            'attr' => ['placeholder' => ' Saisir un numéro de téléphone']
           ])
    ->add('email', EmailType::class,[
            'label' =>'Email',
            'attr' => ['placeholder' => 'Saisir une adresse mail
            ']
           ])
            // ->add('nom')
            // ->add('numero')
            // ->add('email')
            // ->add('UpdatedAt')
            // ->add('CreatedAt')
       
            // ->add('utilisateur')
            // ->add('don')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Beneficiaire::class,
        ]);
    }
}
