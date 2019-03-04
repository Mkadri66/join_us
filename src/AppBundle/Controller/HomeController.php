<?php

namespace AppBundle\Controller;


use AppBundle\Entity;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Homecontroller.
 *
 * @Route("/")
 */
class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
    
        return $this->render('home/homepage.html.twig');
    }

    // /**
    //  * Homepage for admin
    //  *
    //  * @Route("/", name="user_index")
    //  * @Security("has_role('ROLE_ADMIN')")
    //  * @Method("GET")
    //  */
    // public function HomeAction(Request $request)
    // {
        
    //     $em = $this->getDoctrine()->getManager();
    //     $users = $em->getRepository('AppBundle:User')->findAll();     
    //     return $this->render('user/index.html.twig', array(
    //         'users' => $users
    //     ));

    // }
}
