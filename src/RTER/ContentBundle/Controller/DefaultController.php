<?php

namespace RTER\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('RTERContentBundle:Default:continentList.html.twig');
    }


//    /**
//     * @Route("/conkktent")
//     */
//    public function indexAction()
//    {
//        return $this->render('RTERContentBundle:Default:index.html.twig');
//   }
}
