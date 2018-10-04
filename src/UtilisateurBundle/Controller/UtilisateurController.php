<?php

namespace UtilisateurBundle\Controller;

use RoleBundle\Entity\Role;
use ImageBundle\Entity\Image;
use SportBundle\Entity\Sport;
use VilleBundle\Entity\Ville;
use ImageBundle\Form\ImageType;
use UtilisateurBundle\Entity\Utilisateur;
use UtilisateurBundle\Form\UtilisateurType;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Utilisateur controller.
 *
 * @Route("utilisateur")
 */
class UtilisateurController extends Controller
{
    /**
     * Lists all utilisateur entities for the admin.
     *
     * @Route("/", name="utilisateur_index")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $utilisateurs = $em->getRepository('UtilisateurBundle:Utilisateur')->findAll();     
        return $this->render('utilisateur/index.html.twig', array(
            'utilisateurs' => $utilisateurs
        ));

    }

    /**
     * A form to login an user.
     * 
     * @Route("/login", name="login")
     * 
     * @Method({"GET", "POST"})
     * 
     */
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('utilisateur_dashboard');
        } else {
            
            $authenticationUtils = $this->get('security.authentication_utils');
            return $this->render('utilisateur/login.html.twig', array(
                'last_username' => $authenticationUtils->getLastUsername(),
                'error'         => $authenticationUtils->getLastAuthenticationError(),
            ));
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
     * Creates a new utilisateur entity.
     *
     * @Route("/new", name="utilisateur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $encoder = null)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('utilisateur_dashboard');
        } else {        
            $passwordEncoder = $this->get('security.password_encoder');
            $utilisateur = new Utilisateur();
            $form = $this->createForm(UtilisateurType::class, $utilisateur);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $utilisateur = $form->getData();
                // $utilisateur->setRoles(array('ROLE_USER'));
                $password = $passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword());
                $utilisateur->setPassword($password);
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                return $this->redirectToRoute('login');
            }
            return $this->render('utilisateur/new.html.twig', array(
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
            ));

        }


        
        
    }


    /**
     * Dashboard for the connect user.
     *
     * @Route("/dashboard", name="utilisateur_dashboard")
     * @Method({"GET", "POST"})
     */
    public function dashboardAction(Request $request)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            // Si l'utilisateur est connecté on peut le recuperer
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $sports = $em->getRepository('SportBundle:Sport')->findAll();

            return $this->render('utilisateur/dashboard.html.twig', array(
                'user' => $user,
                'sports' => $sports
            ));
        } else {
            return $this->redirectToRoute('login');
        }

    }

    /**
     * Finds and displays a utilisateur entity.
     *
     * @Route("/{id}", name="utilisateur_show")
     * @Method("GET")
     */
    public function showAction(Utilisateur $utilisateur)
    {
        $deleteForm = $this->createDeleteForm($utilisateur);

        return $this->render('utilisateur/show.html.twig', array(
            'utilisateur' => $utilisateur,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing utilisateur entity.
     *
     * @Route("/{id}/edit", name="utilisateur_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Utilisateur $utilisateur)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) { 
            $user_connect = $this->getUser();
            if( $user_connect->getId() === $utilisateur->getId() ) {
                $editForm = $this->createFormBuilder($utilisateur)
                    
                    ->add('nom',        TextType::class)
                    ->add('prenom',     TextType::class)
                    ->add('mail',       TextType::class)
                    ->add('username',     TextType::class)
                    ->add('ville',      EntityType::class, array('label' => 'ville','class'=> Ville::class, 'choice_label'=> 'libelle'))
                    ->add('valider',    SubmitType::class)
                    ->getForm();

                $editForm->handleRequest($request);

                if ($editForm->isSubmitted() && $editForm->isValid()) {

                    $this->getDoctrine()->getManager()->flush();
                    $session = new Session();
                    $session = $request->getSession();
                    $session->start();
                    $session->getFlashBag()->add('info', 'Votre profil à bien était mis à jour :D ');
                    return $this->redirectToRoute('utilisateur_dashboard');
                }

                return $this->render('utilisateur/edit.html.twig', array(
                    'utilisateur' => $utilisateur,
                    'edit_form' => $editForm->createView(),
                ));
            } else {
                return $this->redirectToRoute('utilisateur_dashboard');
            } 

        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * 
     *
     * @Route("/{id}/edit_avatar", name="edit_avatar")
     * @Method({"GET", "POST"})
     */
    public function editAvatarAction(Request $request, Utilisateur $utilisateur)
    {

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) { 
            $user_connect = $this->getUser();
            if( $user_connect->getId() === $utilisateur->getId() ) {
                $editAvatar = $this->createFormBuilder($utilisateur)
                    ->add('avatar',     ImageType::class)
                    ->add('valider',    SubmitType::class)
                    ->getForm();

                $editAvatar->handleRequest($request);

                if ($editAvatar->isSubmitted() && $editAvatar->isValid()) {

                    $this->getDoctrine()->getManager()->flush();
                    $session = new Session();
                    $session = $request->getSession();
                    $session->start();
                    $session = $request->getSession();
                    $session->getFlashBag()->add('info', 'Votre profil à bien était mis à jour :D ');
                    return $this->redirectToRoute('utilisateur_dashboard');
                }

                return $this->render('utilisateur/edit_avatar.html.twig', array(
                    'utilisateur' => $utilisateur,
                    'edit_avatar' => $editAvatar->createView(),
                ));
            } else {
                return $this->redirectToRoute('utilisateur_dashboard');
            } 

        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * Deletes a utilisateur entity.
     *
     * @Route("/{id}", name="utilisateur_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Utilisateur $utilisateur)
    {
        $form = $this->createDeleteForm($utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($utilisateur);
            $em->flush();
        }

        return $this->redirectToRoute('utilisateur_index');
    }


    /**
     * Creates a form to delete a utilisateur entity.
     *
     * @param Utilisateur $utilisateur The utilisateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Utilisateur $utilisateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('utilisateur_delete', array('id' => $utilisateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }




    
}
