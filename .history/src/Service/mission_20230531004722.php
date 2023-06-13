<?php
 
 namespace App\Service;

use App\Entity\Mission as EntityMission;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;

 class Mission{
   
   public function __construct(private  $manager)
   {
    $this->manager = $manager;
   }

   public function getMision(){
 
      $ro= $this->manager;
      return $ro;

   }
 }