<?php

namespace App\Form;

use App\Entity\Rapportmission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Rapportmission1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('action')
            // ->add('mission')
            // ->add('opportunite')
            // ->add('difficulte')
            // ->add('prochaineetape')
            // ->add('UpdatedAt')
            // ->add('CreatedAt')
            
            // ->add('utilisateur')

            
            
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapportmission::class,
        ]);
    }
}
