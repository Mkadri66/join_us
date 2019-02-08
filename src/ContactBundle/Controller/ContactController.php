<?php 

namespace ContactBundle\Controller;

use ContactBundle\ContactBundle;
use ContactBundle\Entity\Contact;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Contact Controller 
 * 
 * @Route ("contact")
 */

 class ContactController extends Controller {


    /**
     * Display form contact
     * 
     * @Route("/", name="contact")
     * 
     *
     */

    public function ContactAction(Request $request){

        $form = $this->createForm('ContactBundle\Form\ContactType');

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $contactMessage = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactMessage);
            $em->flush();

            // Ajout d'un message de succes
            $session = new Session();
         
            $session->getFlashBag()->add('contact_success', 'Votre message a bien été envoyé :D ');
    
            return $this->redirectToRoute('contact');
        }
        
        return $this->render('contact/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
 }