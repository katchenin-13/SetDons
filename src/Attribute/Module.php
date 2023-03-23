<?php

namespace App\Attribute;
use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("CLASS")
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Module
{
    /**
     * @Required
     * @var string
     */
    public $name;


    /*
     * @var string
     */
    public $title;

     /**
     * @var string
     */
    public $controller;

    /**
     * @var array
     */
    public $roles = [];

    /**
     * @var array
     */
    public $methods = [];
}