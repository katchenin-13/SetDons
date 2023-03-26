<?php

namespace App\Service;

use App\Attribute\Source;
use App\Entity\Colonne;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

class Utils
{
    

    const MOIS = [
        1 => 'Janvier',
        'Février',
        'mars',
        'avril',
        'mai',
        'juin',
        'juillet',
        'août',
        'septembre',
        'octobre', 
        'novembre',
        'décembre'
    ];




    public static function formatNumber($value, $decimal = 0, $sep = '.', $thousandSep = ' ')
    {
        $value = $value ? strval($value) : '0';
        $decimalLength = $decimal;
        if (strpos($value, '.')) {
            [,$decimal] = explode('.', $value);
            if (substr_count($decimal, '0') != strlen($decimal)) {
                $decimalLength = strlen($decimal);
            }
        }

        return preg_replace('/\.00$/', '', number_format($value, $decimalLength, $sep, $thousandSep));
    }


    public static function getIdValue($value)
    {
        if (is_object($value)) {
            return $value->getId();
        }
        return $value;
    }


    public static function getFromArray(array $array, string $key)
    {
        return array_map(function ($row) use ($key) {
            return $row[$key];
        },$array);
    }


    public static function getValue($data, ?string $prop = null)
    {
        if ($data instanceof DateTime) {
            return $data->format('d/m/Y');
        }

        return $data && $prop ? $data->{"get".ucfirst(strtolower($prop))}() : null;
    }



    public static function getInitialFromNames($nom, $prenom)
    {
        $prenom = trim(str_replace(['epoux', 'épouse', 'epouse', 'épse', 'epse', 'epx'], '', $prenom));
        $nom = trim(str_replace(['epoux', 'épouse', 'epouse'], '', $nom));
        preg_match_all('/\b\w/u', $prenom.' '.$nom, $matches);
        return mb_strtoupper(implode('', $matches[0]));
    }


    public static function reverseFormat($string)
    {
        $value = floatval(strtr(trim($string), [' ' => '', ',' => '.']));
        return preg_replace('/[\.,]00$/', '', $value);
    }


    public static function  localizeDate($value, $time = false)
    {
        $fmt = new \IntlDateFormatter(
            'fr',
            \IntlDateFormatter::FULL,
            $time ? \IntlDateFormatter::FULL : \IntlDateFormatter::NONE
        );
        return $fmt->format($value instanceof \DateTimeInterface ? $value : new \DateTime($value));
    }


    public static function getAllSources(EntityManagerInterface $em): array
    {
        $entities = $em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames();
        $sources = [];
        foreach ($entities as $entity) {
            $refClass = new ReflectionClass($entity);
           
            if ($refClass->getAttributes(Source::class)) {
                $sources[class_basename($entity)] = $entity;
            }
        }

        return $sources;
    }


    public static function convertValue($value, $typeDonnee, $source = null, EntityManagerInterface $em = null)
    {
        if ($typeDonnee == 'EntityType') {
            return $em ? $em->getRepository($source)->find($value) : '';
        } elseif ($typeDonnee == 'DateType') {
            return new \DateTime($value);
        } elseif ($typeDonnee == 'NumberType') {
            return intval($value);
        } else {
            return $value;
        }
    }


    public static function getFieldByColonne(Colonne $colonne)
    {
        $type = $colonne->getTypeDonnee();
            
        $source = $colonne->getSource();
        $valeurs = explode(',', $colonne->getListeValeur());
        $id = $colonne->getId();

            
        $props = [
            'required' => $colonne->getRequired(),
            'label' => $colonne->getLibelle()
        ];

        $valeurs = $valeurs ? array_combine($valeurs, $valeurs): [];

        $namespace = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\';
         
        $fullType = $namespace.$type;
        if ($type == 'EntityType') {
            $fullType = "Symfony\\Bridge\\Doctrine\\Form\\Type\\{$type}";
            $props['class'] = $source;
            $props['required'] = false;
            $props['placeholder'] = '---';
            $props['attr'] = ['class' => 'has-select2'];
            $props['choice_label'] = function ($e) {
                return $e->{'get'.ucfirst($e::DEFAULT_CHOICE_LABEL)}();
            };
        } elseif ($type == 'CheckboxType') {
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
        } elseif ($type == 'DateType') {
            $props = array_merge($props, [
                'attr' => ['class' => 'datepicker no-auto skip-init'],
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ]);
        }

        $idColonne = $colonne->getId();

        if (in_array($type, ['NumberType'])) {
            if (isset($props['attr']['class'])) {
                $props['attr']['class'] .= ' text-end';
            } else {
                $props['attr']['class'] = ' text-end';
            }
        }

        if (isset($props['attr']['class'])) {
            $props['attr']['class'] .= ' field-'.$idColonne.' '.$colonne->getCode();
        } else {
            $props['attr']['class'] = ' field-'.$idColonne.' '.$colonne->getCode();
        }

        $props['attr']['data-field'] = $idColonne;

        return new FieldInfo($id, $fullType, $props);
    }


    public static function toLabel($valeur, $typeDonnee, $source, $em)
    {
        if ($typeDonnee != 'EntityType') {
            return $valeur;
        }

        $data = static::convertValue($valeur, $typeDonnee, $source, $em);
        if (is_object($data) && $data->getId()) {
           
            $labelProperty = $data::DEFAULT_CHOICE_LABEL;
            $method = 'get'.ucfirst($labelProperty);
            if (method_exists($data, $method)) {
               
                return $data->{$method}();
            }
           
        }
    }

}