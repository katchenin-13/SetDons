<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class,[
                'label' =>'Nom de la Catégorie',
               'attr' => ['placeholder' => 'Saisir le nom de la Catégorie']
           ])
            ->add('code', TextType::class,[
                'label' =>'Code',
               'attr' => ['placeholder' => 'Saisir le code  de la Catégorie']
           ])
            ->add('createdup')
            ->add('updatedup')
            // ->add('utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
