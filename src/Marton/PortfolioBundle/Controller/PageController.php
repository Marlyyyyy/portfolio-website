<?php

namespace Marton\PortfolioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Marton\PortfolioBundle\Classes\Scanner;
use Marton\PortfolioBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;


class PageController extends Controller {

    public function indexAction(){

        return $this->render('MartonPortfolioBundle:Default:Pages/home.html.twig');

    }

    public function aboutAction(){

        $personal_details = array(
            'Name: ' => 'Márton Széles',
            'Location: ' => 'Edinburgh, United Kingdom',
            'Studies: ' => 'University of Edinburgh',
            'Occupation: ' => 'Software Engineer',
            '' => 'Amateur Photographer'
        );

        $repository = $this->getDoctrine()->getRepository('MartonPortfolioBundle:Project');
        $projects = $repository-> findAllProjects();

        return $this->render('MartonPortfolioBundle:Default:Pages/biography.html.twig', array(
            'personal_details' => $personal_details,
            'projects' => $projects
        ));
    }

    public function photographyAction($album){

        if ($album == "index"){
            // Get all albums
            $repository = $this->getDoctrine()->getRepository('MartonPortfolioBundle:Photography');
            $albums = $repository-> findAllAlbums();

            return $this->render('MartonPortfolioBundle:Default:Pages/photography.html.twig', array(
                'albums' => $albums
            ));
        }else{
            // Get photos inside album
            $scanner = new Scanner();
            $scanned_photos = $scanner->getPhotos($album);
            $photos = array();
            $photos_tn = array();

            foreach ($scanned_photos as $photo){
                $target = strpos($photo,".");
                $temp = substr_replace($photo, "_tn", $target, 0);
                if ($target != 0){
                    array_push($photos_tn, $temp);
                    array_push($photos, $photo);
                }
            }

            $repository = $this->getDoctrine()
                ->getRepository('MartonPortfolioBundle:Photography');
            $album_details = $repository->findOneBy(array("dir_path" => $album));

            return $this->render('MartonPortfolioBundle:Default:Pages/Subpages/photography_album.html.twig', array(
                'photos' => $photos,
                'photos_tn' => $photos_tn,
                'album'  => $album,
                'album_details' => $album_details
            ));
        }



    }

    public function drawingAction(){

        // Get all drawings
        $repository = $this->getDoctrine()->getRepository('MartonPortfolioBundle:Drawing');
        $drawings = $repository-> findAllDrawings();

        return $this->render('MartonPortfolioBundle:Default:Pages/drawing.html.twig', array(
            'drawings' => $drawings
        ));
    }

    public function contactAction(Request $request){

        $contact = new Contact();

        $session = $this->get('session');

        $form = $this->createFormBuilder($contact)
            ->add('name',    'text',     array('label' => 'Full Name:'))
            ->add('email',   'email',    array('label' => 'E-mail address:'))
            ->add('subject', 'text',     array('label' => 'Subject:'))
            ->add('message', 'textarea', array('label' => 'Message:'))
            ->add('save',    'submit',   array('label' => 'Send'))
            ->getForm();

        if($session->has('isSubmitted')){
            $isSubmitted = $session->get('isSubmitted');

            if($isSubmitted){
                // Allow user to come back for another email
                $session->set('isSubmitted', false);

                // Don't allow user to resubmit by refreshing
                return $this->render('MartonPortfolioBundle:Default:Pages/contact.html.twig', array(
                    'success' => true
                ));
            }
        }

        $form->handleRequest($request);

        if ($form->isValid()) {
            // Check if the form has been submitted

            $session->set('isSubmitted', true);

            $name = $form->get('name')->getData();
            $email = $form->get('email')->getData();
            $subject = $form->get('subject')->getData();
            $message = $form->get('message')->getData();

            $response = $this->redirect($this->generateUrl("marton_portfolio_contact"));

            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom(array($email => $name))
                ->setTo('mlt.residence@gmail.com')
                ->setBody($message);

            $this->get('mailer')->send($message);

            return $response;
        }

        return $this->render('MartonPortfolioBundle:Default:Pages/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

} 