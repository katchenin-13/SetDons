<?php

namespace App\Form;

use App\Entity\Fielpromesse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FielpromesseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('typedon')
            // ->add('promesse')
            // ->add('qte')
            // ->add('nature')
            // ->add('motif')
            // ->add('montant')
            // ->add('UpdatedAt')
            // ->add('CreatedAt')
            // ->add('utilisateur')

        ->add('typedon', EntityType::class, [
            'class'        => Typedon::class,
            'label'        => false,
            'choice_label' => 'libelle',
            'multiple'     => false,
            'expanded'     => false,
            'attr' => ['class' => 'has-select2'],

        ])

            ->add('qte', NumberType::class, [
                'label' => false,
            ])

            ->add('nature', TextType::class, [
                'label' => false,
            ])

            ->add('motif', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Motif']
            ])

            ->add('montant', NumberType::class, [
                'label' => false,
                'attr' => ['placeholder' => ' Montant / Valeur']
            ])
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fielpromesse::class,
        ]);
    }
}