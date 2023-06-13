<?php
 
 namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

 class Mission{
   
   public function __construct(private  $manager)
   {
    $this->manager = $manager;
   }

   public function getMision(){
      return 
   }
 }