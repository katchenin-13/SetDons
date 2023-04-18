<?php

namespace App\Controller;

use App\Repository\ModuleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ModuleGroupePermitionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{  private $security;

    public function __construct(Security $security)
    {

            $this->security = $security;

            
            // dd( $security);

    }

    #[Route(path: '/', name: 'app_default')]
    public function index(Request $request,ModuleRepository $mod,ModuleGroupePermitionRepository $rep): Response
    {
        // dd($rep->afficheModule(1));
        return $this->render('home/index.html.twig');
    }
}