<?php

namespace Marton\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MartonPortfolioBundle:Default:index.html.twig', array('name' => $name));
    }
}
