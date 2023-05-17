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
            ->add('action')
            ->add('opportunite')
            ->add('difficulte')
            ->add('UpdatedAt')
            ->add('CreatedAt')
            x
            ->add('utilisateur')
            ->add('mission')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapportmission::class,
        ]);
    }
}
