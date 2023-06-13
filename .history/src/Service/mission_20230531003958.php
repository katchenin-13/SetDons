<?php
 
 namespace App\Service;

use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;

 class Mission{
   
   public function __construct(private Mission $manager)
   {
    $this->manager = $manager;
   }

   public function getMision(){
      return $this->manager->get();
   }
 }