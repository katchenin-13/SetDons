<?php

namespace App\Form\DataTransformer;

use App\Entity\Issue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ThousandNumberTransformer implements DataTransformerInterface
{
    private $decimal = 0;

    public function __construct(?int $decimal = 0)
    {
        $this->decimal = $decimal;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param string $issue
     */
    public function transform($numberWithSep)
    {
        
        try {
            $decimalLength = $this->decimal;
            if (strpos($numberWithSep, '.')) {
                [,$decimal] = explode('.', $numberWithSep);
                if (substr_count($decimal, '0') != strlen($decimal)) {
                    $decimalLength = strlen($decimal);
                }
            }
            return $numberWithSep ? number_format($numberWithSep, $decimalLength, '.', ' '): '0';
        } catch (\TypeError $e) {
            return $numberWithSep;
        }
        
    }

    /**
     * Transforms a number to a string
     *
     * @param  string $numberWithSep
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($number)
    {
        $number = strtr(trim($number), [' ' => '', ',' => '.']);
        return $this->decimal == 0 ? floatval($number): number_format($number, $this->decimal, '.', '');
    }
}
