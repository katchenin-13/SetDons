<?php

namespace App\Form;

use App\Entity\Communaute;
use App\Entity\Contact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('nom')
            // ->add('fonction')
            // ->add('email')
            // ->add('numero')
            // ->add('observation')
            // ->add('code', null, [
            //     'label' => 'Code',
            //     'attr' => ['placeholder' => 'Saisir le code']])
            ->add('nom', TextType::class,[
                'label' =>'Nom et Prénom'
           ])
           ->add('fonction', TextType::class,[
            'label' =>'Fonction'
           ])
           ->add('email', EmailType::class,[
            'label' =>'Email', 
            
           ]) 
          ->add('numero', NumberType::class,[
            'label' =>'Numéro de téléphone'
           ])
          ->add('observation', TextareaType::class,[
            'label' =>'Observation'
           ])   
           
            // ->add('UpdatedAt')
            // ->add('CreatedAt')
            // ->add('utilisateur')
            ->add('communaute', EntityType::class, [
                'class'        => Communaute::class,
                'label'        => 'Communaute',
                'choice_label' => 'libelle',
                'multiple'     => false,
                'expanded'     => false,
                'placeholder' => 'Choisir une localité',
                'attr' => ['class' => 'has-select2'],
               
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
