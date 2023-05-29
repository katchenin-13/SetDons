<?php

namespace App\Form;

use App\Entity\Nompf;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NompfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('libelle', TextType::class,[
            'label' =>false,
             "required" => true,
               ])
        // ->add('libelle')
        
        // ->add('UpdatedAt')
        // ->add('CreatedAt')
        // ->add('utilisateur')
        // ->add('communaute')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nompf::class,
        ]);
    }
}
