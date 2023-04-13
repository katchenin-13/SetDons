<?php

namespace App\Form;

use App\Entity\Agenda;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' =>'Titre',
                'attr' => ['placeholder' => 'le libelle ']
               ])
            // ->add('description')
            // ->add('UpdatedAt')
            // ->add('CreatedAt')
            // ->add('utilisateur')

            // ->add('title')
            ->add('start',  DateType::class, [
                "label" => "Date de debut",
                "required" => false,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date']
                ])
            ->add('end', DateType::class, [
                "label" => "Date de fin",
                "required" => false,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date']
                ])

            ->add('description',TextType::class,[
                'label' =>'Description',
               ])
            ->add('all_day')
            ->add('background_color', ColorType::class,[
                'label' =>'Background color'
            ])
            ->add('border_color', ColorType::class,
            [
                'label' =>'Border color'
                ])
            ->add('text_color', ColorType::class,[
                'label' =>'Text color'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}
