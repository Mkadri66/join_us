<?php 

namespace ContactBundle\Controller;

use ContactBundle\ContactBundle;
use ContactBundle\Entity\Contact;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        
        return $this->render('contact/contact.html.twig', array(
            'form' => $form->createView(),
        ));
    }
 }