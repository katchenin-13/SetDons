<?php

namespace App\Form;

use App\Entity\Promesse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromesseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('numero')
            ->add('email')
            ->add('statusdon')
            ->add('communaute')
            ->add('dateremise')
            ->add('CreatedAt')
            ->add('UpdatedAt')
          
            ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promesse::class,
        ]);
    }
}
