<?php

namespace App\Form;

use App\Entity\GroupeModule;
use App\Service\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GroupeModuleType extends AbstractType
{
    private $menu;
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }
   
    public function buildForm(FormBuilderInterface $builder, array $options ): void
    {
        $builder
            ->add('titre')
            ->add('ordre')
            ->add('lien',
                ChoiceType::class,
                [
                    'placeholder' => 'Choisir un lien',
                    'label' => 'Lien',
                    'required'     => false,
                    'expanded'     => false,
                    'attr' => ['class' => 'has-select2'],
                    'multiple' => true,
                    'choices'  => array_flip($this->menu->listeGroupe()),
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupeModule::class,
        ]);
    }
}
