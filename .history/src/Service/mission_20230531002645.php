<?php
 
 namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

 class Mission{
   
   public function __construct(prEntityManagerInterface $manager)
   {
    $this->manager = $manager;
   }

   public function getMision(){

   }
 }