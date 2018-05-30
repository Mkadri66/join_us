<?php

namespace UtilisateurBundle\Controller;

use UtilisateurBundle\Entity\Utilisateur;
use RoleBundle\Entity\Role;
use ImageBundle\Entity\Image;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        
        $session = new Session();
        $session = $request->getSession();

        $id_user = $session->get('id');

        $id_role = $session->get('role');
        
        if( $id_role === 1 ) {

            $em = $this->getDoctrine()->getManager();

            $utilisateurs = $em->getRepository('UtilisateurBundle:Utilisateur')->findAll();

            return $this->render('utilisateur/index.html.twig', array(
                'utilisateurs' => $utilisateurs,
            ));
        } else {
            return $this->redirectToRoute('utilisateur_login');
        }

    }

    /**
     * A form to login an user.
     * 
     *
     * 
     * @Route("/login", name="utilisateur_login")
     * 
     * @Method({"GET", "POST"})
     * 
     */
    public function loginAction(Request $request)
    {
        $session = new Session();
        $session = $request->getSession();

        $id_user = $session->get('id');

        if( !$id_user ) { 

            $utilisateur = new Utilisateur;

            $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $utilisateur);

            $formBuilder

                ->add('pseudo',     TextType::class)
                ->add('mdp',        PasswordType::class)
                ->add('valider',    SubmitType::class);

            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            $data = $request->request->get('form'); 

            $user = $this->getDoctrine()->getRepository( Utilisateur::class )->findOneBy(
                array(
                    'pseudo'=> $data['pseudo'],
                    'mdp'=> $data['mdp']
                )
            );

            $message = "";

            if( $form->isSubmitted()) {
                if ( !empty( $user )) {
                    $session = new Session();
                    $session = $request->getSession();
                    $session->start();

                    $session->set('id', $user->getId());

                    $id_user = $session->get('id');

                    $user_connect = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id_user);

                    $request->getSession()->getFlashBag()->add('notice', 'Connexion reussie');
                    return $this->redirectToRoute('utilisateur_dashboard');
                } else {
                    $message = "Mauvaise combinaison pseudo/mot de passe";
                }
            
            } 

            return $this->render('utilisateur/login.html.twig', array(
                'form' => $form->createView(),
                'message'=> $message
            ));

        } else {
            return $this->redirectToRoute('utilisateur_dashboard');            
        }

    }

    /**
     * Logout an user.
     * @Route("/logout", name="utilisateur_logout")
     * 
     * @Method({"GET", "POST"})
     * 
     */
    public function logoutAction(Request $request)
    {
        $session = new Session();
        $session = $request->getSession();
        $session->clear();
        return $this->redirectToRoute('homepage');
    }

    /**
     * Creates a new utilisateur entity.
     *
     * @Route("/new", name="utilisateur_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $session = new Session();
        $session = $request->getSession();

        $id_user = $session->get('id');

        if( !$id_user ) { 

            // on definit le role par defaut à utilisateur ( role 1 : admin)
            $role = $this->getDoctrine()->getRepository(Role::class)->find(2);


            $utilisateur = new Utilisateur();

            $form = $this->createForm('UtilisateurBundle\Form\UtilisateurType', $utilisateur);

            $form->handleRequest($request);

            $utilisateur->setRole($role);

            if ($form->isSubmitted() && $form->isValid()) {

                $utilisateur->setRole($role);


                $em = $this->getDoctrine()->getManager();

                $em->persist($utilisateur);

                $em->flush();

                return $this->redirectToRoute('utilisateur_login');
            }



            return $this->render('utilisateur/new.html.twig', array(
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
                'role' => $role
            ));
        } else {
            return $this->redirectToRoute('utilisateur_dashboard');
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
        $session = new Session();
        $session = $request->getSession();
        $session->start();

        $id_user = $session->get('id');

        if( $id_user ) {

            $user_connect = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id_user);

            $role = $user_connect->getId();

            $session->set('role', $role);

            $id_role = $session->get('role');

            return $this->render('utilisateur/dashboard.html.twig', array(
                'user_connect' => $user_connect,
                'role' => $id_role
            ));
        } else {
            return $this->redirectToRoute('utilisateur_login');
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
        $deleteForm = $this->createDeleteForm($utilisateur);
        $editForm = $this->createForm('UtilisateurBundle\Form\UtilisateurType', $utilisateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $session = $request->getSession();


            $session->getFlashBag()->add('info', 'Votre profil à bien était mis à jour :D ');

            return $this->redirectToRoute('utilisateur_dashboard');
        }

        return $this->render('utilisateur/edit.html.twig', array(
            'utilisateur' => $utilisateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
