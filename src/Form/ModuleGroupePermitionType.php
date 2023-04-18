<?php

namespace App\Form;

use App\Entity\Fonction;
use App\Entity\GroupeModule;
use App\Entity\Module;
use App\Entity\ModuleGroupePermition;
use App\Entity\Permition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleGroupePermitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ordre',IntegerType::class,[
                'label'=>false
            ])
            ->add('ordreGroupe',TextType::class,[
                'label'=>false
            ])
            ->add('groupeModule', EntityType::class, [
                'class' => GroupeModule::class,
                'choice_label' => 'titre',
                'label'=>false,
                //'label' => 'Groupe module',
                'attr' => ['class' => 'has-select2 form-select']
            ])
            ->add('permition', EntityType::class, [
                'class' => Permition::class,
                'choice_label' => 'libelle',
                'label' => false,
                'attr' => ['class' => 'has-select2 form-select']
            ])
            ->add('module', EntityType::class, [
                'class' => Module::class,
                'choice_label' => 'titre',
                'label' => false,
                'attr' => ['class' => 'has-select2 form-select']
            ])

            /*->add('groupeUser')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModuleGroupePermition::class,
        ]);
    }
}
