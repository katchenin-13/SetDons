<?php

namespace App\Form;

use App\Entity\Don;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Don1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('nemero')
            ->add('email')
            //->add('dateremise')
            //->add('remispar')
            ->add('statusdon')
            ->add('UpdatedAt')
            ->add('CreatedAt')

            ->add('dateremise', DateType::class, [
                "label" => "Date de remise",
                "required" => false,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date']
            ])
            // ->add('remispar')
            ->add('remispar', TextType::class, [
                'label' => 'Remis par',
                'attr' => ['placeholder' => 'Saisir le nom de la personne ayant remis le don']
            ])

            
           
            ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}
