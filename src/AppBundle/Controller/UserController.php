<?php

namespace AppBundle\Controller;

use CityBundle\Entity\City;
use SportBundle\Entity\Sport;
use ImageBundle\Form\ImageType;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * Usercontroller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Lists all users entities for the admin.
     *
     * @Route("/", name="user_index")
     * 
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository('AppBundle:User')->findAll();     
            return $this->render('user/index.html.twig', array(
                'users' => $users
            ));
        } else {
            return $this->redirectToRoute('user_dashboard');
        }

    }

    /**
     * Login check .
     * @Route("/login_check", name="login_check")
     * 
     * @Method({"GET", "POST"})
     * 
     */
    public function logincheckAction(Request $request)
    {
      
    }

    /**
     * Logout an user.
     * @Route("/logout", name="logout")
     * 
     * 
     * 
     */
    public function logoutAction(Request $request)
    {
        
    }

  
    /**
     * Dashboard for the connect user.
     *
     * @Route("/dashboard", name="user_dashboard")
     * @Method({"GET", "POST"})
     */
    public function dashboardAction(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            // Si l'utilisateur est connecté on peut le recuperer
            $user = $this->getUser();
            $parties = $user->getParties();
            $em = $this->getDoctrine()->getManager();
            $sports = $em->getRepository('SportBundle:Sport')->findAll();

            return $this->render('user/dashboard.html.twig', array(
                'user' => $user,
                'sports' => $sports,
                'parties' => $parties
            ));
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }

    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="fos_user_profile_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {

        // if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) { 
        //     $user_connect = $this->getUser();
        //     if( $user_connect->getId() === $user->getId() ) {
        //         $editForm = $this->createFormBuilder($user)              
        //             ->add('mail',       TextType::class)
        //             ->add('username',     TextType::class)
        //             ->add('city',      EntityType::class, array('label' => 'ville','class'=> City::class, 'choice_label'=> 'name'))
        //             ->add('valider',    SubmitType::class)
        //             ->getForm();

        //         $editForm->handleRequest($request);

        //         if ($editForm->isSubmitted() && $editForm->isValid()) {

        //             $this->getDoctrine()->getManager()->flush();
        //             $session = new Session();
        //             $session = $request->getSession();
        //             $session->start();
        //             $session->getFlashBag()->add('info', 'Votre profil a bien été mis à jour :D ');
        //             return $this->redirectToRoute('user_dashboard');
        //         }

        //         return $this->render('user/edit.html.twig', array(
        //             'user' => $user,
        //             'edit_form' => $editForm->createView(),
        //         ));
        //     } else {
        //         return $this->redirectToRoute('user_dashboard');
        //     } 

        // } else {
        //     return $this->redirectToRoute('login');
        // }

        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        // $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        

        return $this->render('@FOSUser/Profile/edit_content.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    // /**
    //  * 
    //  *
    //  * @Route("/{id}/edit_avatar", name="edit_avatar")
    //  * @Method({"GET", "POST"})
    //  */
    // public function editAvatarAction(Request $request, Utilisateur $utilisateur)
    // {

    //     if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) { 
    //         $user_connect = $this->getUser();
    //         if( $user_connect->getId() === $utilisateur->getId() ) {
    //             $editAvatar = $this->createFormBuilder($utilisateur)
    //                 ->add('avatar',     FileType::class)
    //                 ->add('valider',    SubmitType::class)
    //                 ->getForm();

    //             $editAvatar->handleRequest($request);

    //             if ($editAvatar->isSubmitted() && $editAvatar->isValid()) {


    //                 $file = $utilisateur->getAvatar();
    //                 $fileName = $utilisateur->getUsername() . '.' . $file->guessExtension();    
    //                 $utilisateur->setUrl($fileName);
                    
    //                 // On déplace l'image dans le dossiers "avatars" 
    //                 $file->move( $this->getParameter('avatar_directory'), $fileName);



    //                 $this->getDoctrine()->getManager()->flush();
    //                 $session = new Session();
    //                 $session = $request->getSession();
    //                 $session->start();
    //                 $session = $request->getSession();


    //                 $session->getFlashBag()->add('info', 'Votre profil à bien était mis à jour :D ');
    //                 return $this->redirectToRoute('utilisateur_dashboard');
    //             }

    //             return $this->render('utilisateur/edit_avatar.html.twig', array(
    //                 'utilisateur' => $utilisateur,
    //                 'edit_avatar' => $editAvatar->createView(),
    //             ));
    //         } else {
    //             return $this->redirectToRoute('utilisateur_dashboard');
    //         } 

    //     } else {
    //         return $this->redirectToRoute('login');
    //     }
    // }

    // /**
    //  * Deletes a utilisateur entity.
    //  *
    //  * @Route("/{id}", name="utilisateur_delete")
    //  * 
    //  */
    // public function deleteAction(Request $request, Utilisateur $utilisateur)
    // {

    //     if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
    //         $delete_form = $this->createDeleteForm($utilisateur);
    //         $delete_form->handleRequest($request);
    //         $utilisateur = $this->getUser();

    //         if ($delete_form->isSubmitted() && $delete_form->isValid()) {
    //             $em = $this->getDoctrine()->getManager();
    //             $em->remove($utilisateur);
    //             $em->flush();
    //             $session = new Session();
    //             $session = $request->getSession();
    //             $session->start();
    //             $session->getFlashBag()->add('delete', 'Votre compte à bien été supprimé ! ');
    //             return $this->redirectToRoute('homepage');

    //         }

    //         return $this->render('utilisateur/delete.html.twig', array(
    //             'utilisateur' => $utilisateur,
    //             'delete_form' => $delete_form->createView(),
    //         ));
    //     } else {
    //         return $this->redirectToRoute('homepage');
    //     }
    // }


    // /**
    //  * Creates a form to delete a utilisateur entity.
    //  *
    //  * @param Utilisateur $utilisateur The utilisateur entity
    //  *
    //  * @return \Symfony\Component\Form\Form The form
    //  */
    // private function createDeleteForm(Utilisateur $utilisateur)
    // {
    //     return $this->createFormBuilder()
    //         ->setAction($this->generateUrl('utilisateur_delete', array('id' => $utilisateur->getId())))
    //         ->setMethod('DELETE')
    //         ->getForm()
    //     ;
    // }




    
}
