<?php
 
 namespace App\Service;

use App\Entity\Mission;

 class Mission{
   
   public function __construct(private Missio $manager)
   {
    $this->manager = $manager;
   }

   public function getMision(){
 
      $ro= $this->manager->findAll();
      return $ro;

   }
 }