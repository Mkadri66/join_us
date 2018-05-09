<?php

namespace PartieBundle\Controller;

use PartieBundle\Entity\Partie;
use UtilisateurBundle\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Partie controller.
 *
 * @Route("partie")
 */
class PartieController extends Controller
{
    /**
     * Lists all partie entities.
     *
     * @Route("/", name="partie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parties = $em->getRepository('PartieBundle:Partie')->findAll();

        return $this->render('partie/index.html.twig', array(
            'parties' => $parties,
        ));
    }

    /**
     * Creates a new partie entity.
     *
     * @Route("/new", name="partie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {


        $partie = new Partie();
        $form = $this->createForm('PartieBundle\Form\PartieType', $partie);
        $form->handleRequest($request);

        $session = new Session();
        $session = $request->getSession();
        $session->start();



        if( $session->get('id') ) {
            // On recupere l'id de l'utilisateur connecté 
            $id_user = $session->get('id');

            // On va le cherche en base de données
            $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id_user);

            // Par défaut une partie n'est pas terminé
            $partie->setTermine( 0 );
            
            // On set l'utilisateur grace à son id stocké en session
            $partie->setOrganisateur($utilisateur);

            $user = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id_user);

            $partie->addUtilisateur( $user);

            if ($form->isSubmitted() && $form->isValid()) {
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($partie);
                $em->flush();

                return $this->redirectToRoute('partie_show', array('id' => $partie->getId()));
            }

            return $this->render('partie/new.html.twig', array(
                'partie' => $partie,
                'form' => $form->createView(),
                'utilisateur' => $utilisateur
            ));
        } else {
            return $this->redirectToRoute('utilisateur_login');
        }


    }

    /**
     * Finds and displays a partie entity.
     *
     * @Route("/{id}", name="partie_show")
     * @Method("GET")
     */
    public function showAction(Partie $partie)
    {
        $deleteForm = $this->createDeleteForm($partie);

        return $this->render('partie/show.html.twig', array(
            'partie' => $partie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing partie entity.
     *
     * @Route("/{id}/edit", name="partie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Partie $partie)
    {
        $deleteForm = $this->createDeleteForm($partie);
        $editForm = $this->createForm('PartieBundle\Form\PartieType', $partie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('partie_edit', array('id' => $partie->getId()));
        }

        return $this->render('partie/edit.html.twig', array(
            'partie' => $partie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a partie entity.
     *
     * @Route("/{id}", name="partie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Partie $partie)
    {
        $form = $this->createDeleteForm($partie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($partie);
            $em->flush();
        }

        return $this->redirectToRoute('partie_index');
    }

    /**
     * Creates a form to delete a partie entity.
     *
     * @param Partie $partie The partie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Partie $partie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('partie_delete', array('id' => $partie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
