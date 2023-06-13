<?php

namespace App\Form;

use App\Entity\Mission;
use App\Entity\Rapportmission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportmissionType extends AbstractType
{
     
    public function __construct(
      private Mission $mission,
    ) {
      
    }

    public function buildForm(FormBuilderInterface $builder, array $options,Rapportmission $rapportmission): void
    {
        $builder
            // ->add('action')
            // ->add('mission')
            // ->add('opportunite')
            // ->add('difficulte')
            // ->add('prochaineetape')
            // ->add('UpdatedAt')
            // ->add('CreatedAt')

            // ->add('utilisateur')


            ->add('mission',EntityType::class, [
                'class'        => Mission::class,
                'label'        => 'Code la mission',
                'choice_label' => 'code',
                'multiple'     => false,
                'expanded'     => false,
                'placeholder' => 'Choisir le code de la mission',
                'attr' => ['class' => 'has-select2'],

            ])
            ->add('dateretour', DateType::class, [
                "label" => "Date de retour de la mission",
                "required" => true,
                "widget" => 'single_text',
                "input_format" => 'Y-m-d',
                "by_reference" => true,
                "empty_data" => '',
                'attr' => ['class' => 'date'],
            ])
           
         
            ->add('action', TextareaType::class, [
                'label' => 'Action (s) réalisée (s)',
                'attr' => ['placeholder' => 'Saisir le text']
            ])
            ->add('opportunite', TextareaType::class, [
                'label' => 'Opportunite',
                 'required'=>false,
                'attr' => ['placeholder' => 'Saisir un texte']
            ])
            ->add('difficulte', TextareaType::class, [
                'label' => 'Difficulté (s) rencontrée',
                'required' => false ])

           ->add('prochaineetape', TextType::class, [
            'label' => 'Saisir la prochaine étape']);

        $missions = $this->mission->getDateretour();
       
        if (!$missions) {
            throw new \LogicException(
                'The FriendMessageFormType cannot be used without an authenticated user!'
            );
        }

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($missions) {
            if (null !== $event->getData()->getDta()) {
                // we don't need to add the friend field because
                // the message will be addressed to a fixed friend
                return;
            }

            $form = $event->getForm();

            $formOptions = [
                'class' => User::class,
                'choice_label' => 'fullName',
                'query_builder' => function (UserRepository $userRepository) use ($user) {
                    // call a method on your repository that returns the query builder
                    // return $userRepository->createFriendsQueryBuilder($user);
                },
            ];

            // create the field, this is similar the $builder->add()
            // field name, field type, field options
            $form->add('friend', EntityType::class, $formOptions);
        });

        // $formModifier = function (FormInterface $form, Mission $mission = null) {

        //     $form->add('justification', TextareaType::class, [
        //         'label' => 'Justication de votre retard',
        //         'attr' => ['placeholder' => 'Saisir le text']
        //     ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rapportmission::class,
        ]);
    }
}
