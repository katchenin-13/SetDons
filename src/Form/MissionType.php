<?php

namespace App\Form;

use App\Entity\Communaute;
use App\Entity\Mission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{


    /**
     * Cette fonction permet de confugurer les champs de type text 
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
 
            ->add('code', TextType::class, $this->getConfiguration("Code de la mission", "Code de la mission"))

            ->add('debut', DateType::class, [
            "label" => "Date de debut de la mission",
            "required" => true,
            "widget" => 'single_text',
            "input_format" => 'Y-m-d',
            "by_reference" => true,
            "empty_data" => '',
            'attr' => ['class' => 'date'],
            ])

            ->add('dateretour', DateType::class, [
                "label" => "Date de fin de la mission",
                "required" => true,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date'],
            ])

            ->add('communaute', EntityType::class, [
                'class'        => Communaute::class,
                'label'        => 'Communaute',
                'choice_label' => 'libelle',
                'multiple'     => false,
                'expanded'     => false,
                'placeholder' => 'Choisir une localité',
                'attr' => ['class' => 'has-select2'],

            ])
            ->add('ordremission', TextType::class, $this->getConfiguration("Titre de la mission", "Titre de la mission"))
            ->add('chefmission', TextType::class, $this->getConfiguration("Chef de Mission", "Nom du chef de mission"))
            // ->add('membredeg', TextType::class,$this->getConfiguration("Membres de la délégation", "Noms des membres de la délégation"))
            ->add('objectif', TextType::class, $this->getConfiguration("Objectif (s) de la mission", "l'objectif de la mission"))

              // ->add('CreatedAd')
            // ->add('UpdatedAt')
             // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
