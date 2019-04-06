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

    /**
    * Homepage for admin
    *
    * @Route("/admin", name="admin")
    * 
    */
    public function AdminAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll(); 
            $parties = $em->getRepository('PartyBundle:Party')->findAll(); 
            $contacts = $em->getRepository('ContactBundle:Contact')->findAll(); 

            $numberOfUsers = count($users);
            $numberOfParties = count($parties);   

            return $this->render('user/admin.html.twig', array(
                'number_of_users' => $numberOfUsers,
                'number_of_parties' => $numberOfParties, 
                'contacts' => $contacts
            ));
            
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_dashboard');
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }

    }

    /**
    * Message received from contact form
    *
    * @Route("/admin/message", name="admin_message")
    * 
    */
    public function MessageAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll(); 
            $contacts = $em->getRepository('ContactBundle:Contact')->findAllMessages(); 
            return $this->render('user/admin_message.html.twig', array(
                'users' => $users,
                'contacts' => $contacts
            ));
            
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_dashboard');
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }

    }

    
    /**
    * All users of application
    *
    * @Route("/admin/users", name="admin_users")
    * 
    */
    public function UsersAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll(); 
            return $this->render('user/admin_users.html.twig', array(
                'users' => $users,
            ));
            
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_dashboard');
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }

    }
}
