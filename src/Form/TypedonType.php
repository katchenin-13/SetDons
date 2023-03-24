<?php

namespace App\Form;

use App\Entity\Typedon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypedonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('libelle', TextType::class,[
            'label' =>'Nom de le Type de don',
           'attr' => ['placeholder' => 'Saisir le nom de le Type de don']
       ])
        ->add('code', TextType::class,[
            'label' =>'Code',
           'attr' => ['placeholder' => 'Saisir le code  de le Type de don']
       ])
            ->add('createdup')
            ->add('updatedup')
            // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Typedon::class,
        ]);
    }
}
