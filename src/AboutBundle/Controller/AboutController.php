<?php

namespace AboutBundle\Controller;
use AboutBundle\AboutBundle;
use AboutBundle\Entity\About;
use AboutBundle\Form\AboutType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AboutController extends Controller

/**
 * About Controller 
 * 
 * @Route ("about")
 * 
 */
{
    /**
     * @Route("/", name="about")
     */
    public function AboutAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $about = $em->getRepository('AboutBundle:About')->find(1);
        $form = $this->createForm(AboutType::class, $about);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            // Ajout d'un message de succes
            $session = new Session();
         
            $session->getFlashBag()->add('about_success', 'Le contenu a bien été mis à jour.');
    
            return $this->redirectToRoute('about');
        }

        return $this->render('about/about.html.twig', array(
            'about' => $about,
            'form' => $form->createView(),
        ));
    }
}
