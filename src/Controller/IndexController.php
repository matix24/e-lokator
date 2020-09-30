<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{

    /**
     * Główny widok aplikacji
     * @Route("/", name="index_index")
     */    
    public function mainPage(){
        if($this->getUser()){
            $redirectToRoute = 'app_index';
        }else{
            $redirectToRoute = 'app_login';
        }
        return $this->redirectToRoute($redirectToRoute);
    }// end mainPage

    /**
     * Strona główna po zalogowaniu
     * @Route("/app", name="app_index")
     * @#IsGranted("ROLE_USER")
     */
    public function index() : Response{
        return $this->render('app/index.html.twig');
    }

}// end class
