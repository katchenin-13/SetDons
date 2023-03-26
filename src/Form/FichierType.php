<?php

namespace App\Form;

use App\Entity\Fichier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class FichierType extends AbstractType
{

    const DEFAULT_MIME_TYPES = [
        'text/plain'
        , 'application/octet-stream'
        , 'application/pdf'
        , 'application/vnd.ms-excel'
        , 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        , 'application/msword'
        , 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['doc_options']['mime_types'])) {
            $mimeTypes = $options['doc_options']['mime_types'];
            unset($options['doc_options']['mime_types']);
        } else {
             $mimeTypes = $options['mime_types'] ?: self::DEFAULT_MIME_TYPES;
        }
       
        $attr = array_merge(['accept' => implode(',', $mimeTypes)], $options['attrs']);
        $attr = array_merge($attr, $options['doc_options']['attrs'] ?? []);

        if (isset($options['doc_options']['attrs'])) {
             unset($options['doc_options']['attrs']);
        }


        
        
        $builder
        //->add('alt', UrlType::class, ['attr' => ['class' => 'input-alt', 'placeholder' => 'URL'], 'required' => false])*/
        ->add('file', FileType::class, [
            'label' => false
            //,'data_class' => Fichier::class
            , 'required' => $options['required']
            , 'attr' => $attr

            , 'constraints' => [

                new File(
                    ['mimeTypes' => $mimeTypes]
                ),
            ],
        ]);

        if ($options['doc_options']) {

            //dump($options['doc_options']);exit;

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
                $data      = $event->getData();
                $form      = $event->getForm();
                $nonMapped = ['uploadDir'];

                

                if (!empty($data['file'])) {
                    foreach ($options['doc_options'] as $option => $value) {
                        /*if (is_object($value)) {
                            $form->add($option, EntityType::class, [
                                'class' => Directory::class
                                , 'choice_label' => 'title',
                            ]);
                        } else {
                            
                        }*/

                        $form->add($option, TextType::class, ['mapped' => !in_array($option, $nonMapped)]);

                        $data[$option] = is_object($value) ? $value->getId() : $value;
                    }

                    $form->add('path', TextType::class);
                    $form->add('filePrefix', TextType::class);

                   
                        //$data['alt'] = null;
                        
                    $parts = explode('/public/uploads/', $options['doc_options']['uploadDir']);
                    $path = $parts[1] ?? '';

                    

                    $data['path'] = $path ?? null;
                    if (!empty($options['doc_options']['file_prefix'])) {
                        $data['filePrefix'] = $options['doc_options']['file_prefix'];
                    }
                         
                   

                   

                   $event->setData($data);

                }

                

            });
        }
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class,
            'doc_options' => [],
            'attrs' => [],
            'mime_types' => self::DEFAULT_MIME_TYPES,
            'required' => false,
            'validation_groups' => ['Default', 'FileRequired']
        ]);

        $resolver->setRequired('doc_options');
        $resolver->setRequired('mime_types');
        $resolver->setRequired('attrs');
        $resolver->setRequired('required');
    }


}
