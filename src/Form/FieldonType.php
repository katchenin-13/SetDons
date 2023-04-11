<?php

namespace App\Form;

use App\Entity\Fieldon;
use App\Entity\Typedon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('qte')
            // ->add('naturedon')
            // ->add('motifdon')
            // ->add('montantdon')
            // ->add('typedon')
            ->add('typedon', EntityType::class, [
                'class'        => Typedon::class,
                'label'        => 'Type',
                'choice_label' => 'libelle',
                'multiple'     => false,
                'expanded'     => false,
                'attr' => ['class' => 'has-select2'],
               
            ])
            
            ->add('qte', NumberType::class,[
                'label' =>'Qté',
               ])

           ->add('naturedon', TextType::class,[
            'label' =>'Nature du don',
               ])
            
           ->add('motifdon', TextType::class,[
            'label' =>'Motif du don',
            'attr' => ['placeholder' => 'Motif du don']
               ])

           ->add('montantdon', NumberType::class,[
                'label' =>' Montant / Valeur Estimative',
                'attr' => ['placeholder' => ' Montant / Valeur']
               ])
       

            // ->add('UpdatedAt')
            // ->add('CreatedAt')
            // ->add('typedon')
            // ->add('utilisateur')
            // ->add('don')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fieldon::class,
        ]);
    }
}
