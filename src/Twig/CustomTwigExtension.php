<?php

namespace App\Twig;

use App\Entity\Employe;
use App\Service\RouterInfo;
use App\Service\Utils;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\WorkflowInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use TypeError;

class CustomTwigExtension extends AbstractExtension
{
   

    public function __construct(
        private FormRendererInterface $renderer
        , private EntityManagerInterface $em
        , private RequestStack $requestStack
        , private RouterInfo $routerInfo
        , private ParameterBagInterface $parameterBag
        , private Security $security
    )
    {
        
    }


    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('localize_number', [$this, 'localizeNumber']),
            new TwigFilter('localize_date', [$this, 'localizeDate']),
            new TwigFilter('merge_attrs', [$this, 'mergeAttributes']),
            new TwigFilter('time_duration', [$this, 'getDuration']),
            new TwigFilter('format_number', [$this, 'formatNumber']),
            new TwigFilter('intersect', 'array_intersect'),
            new TwigFilter('try_convert', [$this, 'tryConvert']),
            new TwigFilter('convert_date_num', [$this, 'convertDateNum']),
            new TwigFilter('add_time', [$this, 'addTime']),
            new TwigFilter('round_down', [$this, 'roundDown']),
            new TwigFilter('to_label', [$this, 'toLabel'])
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('instanceof', [$this, 'checkInstance']),
            new TwigFunction('initial_name', [Utils::class, 'getInitialFromNames']),
            new TwigFunction('form_row_inline', [$this, 'formRowInline'], ['is_safe' => ['html']]),
            new TwigFunction('variation_label', [$this, 'variationLabel'], ['is_safe' => ['html']]),
            new TwigFunction('get_menu_role', [$this, 'getMenuRole']),
            new TwigFunction('get_role_from_item_name', [$this, 'getRoleFromItemName']),
            new TwigFunction('is_menu_link_displayable', [$this, 'isMenuLinkDisplayable']),
            new TwigFunction('get_workflow_states', [$this, 'getWorkflowStates']),
            new TwigFunction('get_prop_value', [$this, 'getPropValue']),
            new TwigFunction('is_marked', [$this, 'isMarked']),
            new TwigFunction('ref_value', [$this, 'getRefValue']),
            new TwigFunction('is_instance', [$this, 'isInstanceOf']),
        ];
    }



    /**
     * @param $value
     * @return mixed
    */
    public function isInstanceOf($value, $class)
    {
        return $value instanceof $class;
    }


    public function roundDown($value)
    {
        return round($value, 0, PHP_ROUND_HALF_DOWN);
    }


    public function addTime(DateTimeInterface $date, $value, $type)
    {
        $clone = clone $date;
        return $clone->modify("+{$value} {$type}");
    }


    public function formatNumber($value, $decimal = 0, $sep = '.', $thousandSep = ' ', $default = null)
    {
        if ($value == 0 && $default) {
            return $default;
        }
        $value = $value ? strval($value) : '0';
        $decimalLength = $decimal;
        if (strpos($value, '.')) {
            [,$decimal] = explode('.', $value);
            if (substr_count($decimal, '0') != strlen($decimal)) {
                $decimalLength = strlen($decimal);
            }
        }
        try {
            $value = preg_replace('/\.00$/', '', number_format($value, $decimalLength, $sep, $thousandSep));
        } catch (TypeError $e) {
            $value = $default;
        }
        return $value;
    }

    

    public function localizeNumber($value)
    {
        $fmt = numfmt_create('fr_FR', \NumberFormatter::SPELLOUT);
        return $fmt->format($value);
    }

    

    public function checkInstance($value, $classname)
    {
        return $value instanceof $classname;
    }


    public function localizeDate($value, $time = false)
    {
        $fmt = new \IntlDateFormatter(
            'fr',
            \IntlDateFormatter::FULL,
            $time ? \IntlDateFormatter::FULL : \IntlDateFormatter::NONE
        );
        return $fmt->format($value instanceof \DateTimeInterface ? $value : new \DateTime($value));
    }


    public function getDuration($value, $date)
    {
        if (is_null($date)) {
            $date = new \DateTimeImmutable();
        }

        $interval = $value->diff($date);
        return $interval->d;
    }


    public function convertDateNum($value, $separator = '/')
    {
        $parts = explode($separator, $value);
        $length = count($parts);
        if ($length == 3) {
            [$jour, $mois, $annee] = $parts;
        } else if ($length == 2) {
            [$mois, $annee] = $parts;
        } else {
            $mois = null;
            $annee = null;
        }

        $label = [];
        if (isset($jour)) {
            $label[] = $jour;
        }

        if (isset($mois)) {
            $label[] = Utils::MOIS[intval($mois)];
        }

        if (isset($annee)) {
            $label[] = $annee;
        }

        return implode(' ', $label);
    }


       /**
     * @param FormView $view
     * @param array $variables
     * @return mixed
     */
    public function formRowInline(FormView $view, array $variables = [])
    {
        return $this->renderer->renderBlock($view, 'form_row_inline', $variables);
    }


    public function  mergeAttributes($value, $object, $props)
    {
        $results = [];
        foreach ($props as $prop) {
            $results[$prop] = $object->{'get'.ucfirst($prop)}();
        }
        return array_merge($value, $results);
    }


    public function variationLabel($value)
    {
        $value *= 100;
        $value = round($value, 2);
        if ($value < 0) {
            return '<span class="text-danger">-'.$value.' %</span>';
        } else if ($value > 0) {
            return '<span class="text-success">+ '.$value.' %</span>';
        }
        return '<span class="text-muted">'.$value.' %</span>';
    }


    public function getMenuRole($routeName, $moduleName, $child = null)
    {
        return $this->routerInfo->getRoleByRouteName($routeName, $moduleName, $child);
    }


    /**
     * Undocumented function
     *
     * @param string $module
     * @param string $itemName
     * @return void
     */
    public function getRoleFromItemName($module, $itemName)
    {
        [$controller, $roleName] = explode('.', $itemName);
        return 'role_'.$roleName.'_'.$module.'_'.$controller;
    }


    public function isMenuLinkDisplayable(ItemInterface $item, $moduleName, $childName, $as)
    {       
        /**
         * @var \App\Entity\Utilisateur $user
         */
        $user = $this->security->getUser();
        $namePrefix = $item->getParent()->getExtra('name_prefix');
        $name = str_replace($namePrefix, '', $item->getName());

        if (!$user) {
            return true;
        }

       
        return $user->hasRole('ROLE_ADMIN') ||
            $item->getExtra('no_check') ||
            !$moduleName ||
            $item->getExtra('workflow') ||
            $item->getExtra('is_title') ||
            $item->getChildren() ||
            $user->hasAllRoleOnModule('MANAGE', $moduleName, $name, $childName, $as) ||
            !$item->getUri();

    }


    public function toLabel($valeur, $typeDonnee, $source)
    {
        if ($typeDonnee != 'EntityType') {
            return $valeur;
        }

        $data = Utils::convertValue($valeur, $typeDonnee, $source, $this->em);
        if (is_object($data) && $data->getId()) {
           
            $labelProperty = $data::DEFAULT_CHOICE_LABEL;
            $method = 'get'.ucfirst($labelProperty);
            if (method_exists($data, $method)) {
               
                return $data->{$method}();
            }
           
        }
    }
}
