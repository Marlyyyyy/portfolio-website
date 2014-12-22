<?php
/**
 * Created by PhpStorm.
 * User: Marci
 * Date: 2014.09.02.
 * Time: 22:52
 */

namespace Marton\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class FormController extends Controller{

    public function messageAction(){

        return $this->render('MartonPortfolioBundle:Default:Pages/contact.html.twig', array(
            'success' => true
        ));

    }

} 