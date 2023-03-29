<?php

namespace App\Form;

use App\Entity\PointFocal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointFocalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class,[
                    'label' =>false,
                    'attr' => ['placeholder' => 'Saisir le nom du point focal']
                   ])
            ->add('numeropf', NumberType::class,[
                    'label' => false,
                    'attr' => ['placeholder' => 'Saisir le numéro de téléphone']
                   ])
            ->add('emailpf', EmailType::class,[
                    'label' =>false,
                    'attr' => ['placeholder' => 'Saisir le nom du point focal']
                   ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PointFocal::class,
        ]);
    }
}
