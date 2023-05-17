<?php

namespace App\Service;



class Champs
{
    
    /**
     * Cette fonction permet de confugurer les champs de type text 
     *
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    public function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    
}
