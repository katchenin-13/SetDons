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
            ->add('qte')
            ->add('nature')
            ->add('motif')
            ->add('montant')
            ->add('UpdatedAt')
            ->add('CreatedAt')
            ->add('typedon')
            ->add('promesse')
            ->add('utilisateur')

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

            ->add('naturedon', TextType::class, [
                'label' => false,
            ])

            ->add('motifdon', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Motif']
            ])

            ->add('montantdon', NumberType::class, [
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
