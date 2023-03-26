<?php

/**
 * Génération de fil d'ariane
 */
namespace App\Service;

use InvalidArgumentException;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Twig\Environment;

class FormTypeGenerator
{

    const DEFAULT_NAMESPACE =  "Symfony\\Component\\Form\\Extension\\Core\\Type\\";
    const ENTITY_TYPE_NS = "Symfony\\Bridge\\Doctrine\\Form\\Type\\";

    public function getOptions($dataType, $extras = [])
    {
        $source = $extras['source'] ?? null;
        $valeurs = explode(',', ($extras['liste_valeurs'] ?? ''));
        $isRequired = $extras['required'] ?? false;
        $label = $extras['label'] ?? false;
        $id = $extras['id'] ?? false;

        $defaultProps = [
            'required' => $isRequired,
            'label' => $label
        ];


        if (isset($props['attr']['class'])) {
            $props['attr']['class'] .= ' field-'.$idColonne;
        } else {
            $props['attr']['class'] = ' field-'.$idColonne;
        }

        if ($id) {
            $defaultProps['attr']['data-field'] = $id;
        }
       

        /***
         * 
         * } elseif ($type == 'CheckboxType') {

                $type    = 'ChoiceType';
                $isArray = true;
                $props   = array_merge($props, [
                    'expanded' => true,
                    'multiple' => true,
                    'choices'  => $valeurs,
                ]); 
            } elseif ($type == 'ChoiceType') {
                $props   = array_merge($props, [
                    'expanded' => true,
                    'multiple' => false,
                    'choices'  => $valeurs,
                    'attr' => ['class' => 'has-select2']
                ]);
            } elseif ($type == 'RadioType') {
                $props   = array_merge($props, [
                    'expanded' => true,
                    'multiple' => false,
                    'choices'  => $valeurs,
                ]);
            }
         */
    }


    private function generateEntityType($options)
    {
        if (!isset($options['source'])) {
            throw new InvalidArgumentException(sprintf("L'option %s est obligatoire pour ce type de données", '[source]'));
        }

        $source = $options['source'];
        $fullType = self::ENTITY_TYPE_NS."EntityType";
        $props['class'] = $source;
        $props['required'] = false;
        $props['placeholder'] = '---';
        $props['attr'] = ['class' => 'has-select2'];
        $props['choice_label'] = function ($e) {
            return $e->{'get'.ucfirst($e::DEFAULT_CHOICE_LABEL)}();
        };

        return ['full_type' => $fullType, 'props' => $props];

    }


    private function generateChoiceType($options)
    {
        if (!isset($options['valeurs'])) {
            throw new InvalidArgumentException(sprintf("L'option %s est obligatoire pour ce type de données", '[valeur]'));
        }
        $valeurs = $options['valeurs'] ?? [];
        if ($valeurs) {
            $valeurs = $valeurs ? array_combine($valeurs, $valeurs): [];
        }

        $fullType = self::DEFAULT_NAMESPACE.'ChoiceType';
        $props = [
            'expanded' => true,
            'multiple' => false,
            'choices'  => $valeurs,
            'attr' => ['class' => 'has-select2']
        ];

         return ['full_type' => $fullType, 'props' => $props];
    }


    private function generateTextType($options = [])
    {

        $fullType = self::DEFAULT_NAMESPACE.'TextType';
        $props = [
        ];

        return ['full_type' => $fullType, 'props' => $props];
    }


    private function generateDateType($options = [])
    {
        $fullType = self::DEFAULT_NAMESPACE.'DateType';
        $props = [
            'attr' => ['class' => 'datepicker no-auto skip-init'],
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
        ];

        return ['full_type' => $fullType, 'props' => $props];
    }


    public function generateRadioType($options = [])
    {
        $fullType = self::DEFAULT_NAMESPACE.'ChoiceType';

        if (!isset($options['valeurs'])) {
            throw new InvalidArgumentException(sprintf("L'option %s est obligatoire pour ce type de données", '[valeur]'));
        }
        $valeurs = $options['valeurs'] ?? [];
        if ($valeurs) {
            $valeurs = $valeurs ? array_combine($valeurs, $valeurs): [];
        }
        $props   = [
            'expanded' => true,
            'multiple' => false,
            'choices'  => $valeurs,
        ];

        return ['full_type' => $fullType, 'props' => $props];
    }
}

