<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Groupe;
use App\Entity\ParametreConfiguration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParametreConfigurationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('primaryColor',ColorType::class)
            ->add('secondaryColor',ColorType::class)
            ->add('logo',
                FichierType::class,
                ['label' => 'Photo',  'doc_options' => $options['logo']['doc_options'], 'required' => false])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'denomination',
                'label' => 'Catégorie avis',
                'attr' => ['class' => 'has-select2 form-select']
            ])
            /*->add('entreprise')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ParametreConfiguration::class,
            'doc_required' => false,
            'doc_options' => [],
        ]);

        $resolver->setRequired('doc_required');
        $resolver->setRequired('doc_options');
        $resolver->setRequired('logo');
    }
}
