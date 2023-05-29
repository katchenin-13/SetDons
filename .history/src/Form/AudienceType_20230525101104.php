<?php

namespace App\Form;

use App\Entity\Audience;
use App\Entity\Communaute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AudienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('motif')
            // ->add('daterencontre')
            // ->add('nomchef')
            // ->add('numero')
            // ->add('nombreparticipant')
            // ->add('nomdesparticipant')
            // ->add('observation')
            // ->add('statusaudience')
            // ->add('mentions')
            ->add('motif', TextType::class,[
                'label' =>'Motif de la demande d’audience',
                'attr' => ['placeholder' => 'Motif de la demande d’audience']
               ])
            ->add('daterencontre', DateType::class, [
                "label" => "Date de rencontre souhaitée",
                "required" => true,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => [
                    'class' => 'date',
                    'placeholder' => 'Date de rencontre souhaitée']
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
            ->add('nomchef', TextType::class,[
                'label' =>'Nom du chef de délégation',
                'attr' => ['placeholder' => 'Nom du chef de délégation']
               ])
            ->add('numero', NumberType::class,[
                'label' => 'Nnuméro de téléphone',
                'attr' => ['placeholder' => '+25']
               ])
            ->add('email', EmailType::class,[
                'label' =>'Email',
                 'required'=>false,
                'attr' => ['placeholder' => 'Saisir une adresse mail
                ']
               ])
            ->add('nombreparticipant', NumberType::class,[
                'label' => 'Nombre de participants',
                'attr' => ['placeholder' => ' Saisir le nombre de participants']
               ])

            ->add('observation', TextareaType::class,[
                'label' =>'Observation',
                'required'=>false
               ])  
            // ->add('UpdatedAt')
            // ->add('CreatedAt')
            // ->add('communaute')
            // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Audience::class,
        ]);
    }
}
