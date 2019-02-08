<?php

namespace PartieBundle\Controller;

use PartieBundle\PartieBundle;
use PartieBundle\Entity\Partie;
use Doctrine\ORM\EntityRepository;
use UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


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
     * Show all football parties
     *
     * @Route("/foot", name="partie_football")
     * @Method({"GET"})
     */
    public function showFootAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Partie::class);
            $partiesFoot =  $repository->findBy(
                array('sport' => 1)
            );

            return $this->render('partie/show_foot.html.twig', array(
                'parties' => $partiesFoot,
            ));
        } else {
            return $this->redirectToRoute('login');
        }

    }

    /**
     * Show all basketball parties
     *
     * @Route("/basketball", name="partie_basketball")
     * @Method({"GET"})
     */
    public function showBasketAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Partie::class);
            $partiesBasket =  $repository->findBy(
                array('sport' => 2 )
            );

            return $this->render('partie/show_basket.html.twig', array(
                'parties' => $partiesBasket,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * Show all tennis parties
     *
     * @Route("/tennis", name="partie_tennis")
     * @Method({"GET"})
     */
    public function showTennisAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Partie::class);
            $partiesTennis =  $repository->findBy(
                array('sport' => 3 )
            );

            return $this->render('partie/show_tennis.html.twig', array(
                'parties' => $partiesTennis,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * Show all padel parties
     *
     * @Route("/padel", name="partie_padel")
     * @Method({"GET"})
     */
    public function showPadelAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {       
            $repository = $this->getDoctrine()->getRepository(Partie::class);
            $partiesPadel =  $repository->findBy(
                array('sport' => 4 )
            );

            return $this->render('partie/show_padel.html.twig', array(
                'parties' => $partiesPadel,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * Show all handball parties
     *
     * @Route("/handball", name="partie_handball")
     * @Method({"GET"})
     */
    public function showHandAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Partie::class);
            $partiesHand =  $repository->findBy(
                array('sport' => 5 )
            );

            return $this->render('partie/show_hand.html.twig', array(
                'parties' => $partiesHand,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }



    /**
     * Creates a new partie entity.
     *
     * @Route("/new", name="partie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $partie = new Partie();
            $form = $this->createForm('PartieBundle\Form\PartieType', $partie);
            $form->handleRequest($request);

            // On va le cherche en base de données
            $utilisateur = $this->getUser();

            // Par défaut une partie n'est pas terminé
            $partie->setTermine( 0 );
            
            // On set l'utilisateur grace à son id stocké en session
            $partie->setOrganisateur($utilisateur);


            if ($form->isSubmitted() && $form->isValid()) {
                $partie->addUtilisateur( $utilisateur);
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
            return $this->redirectToRoute('login');
        }
        


    }

    /**
     * Finds and displays a partie entity.
     *
     * @Route("/{id}", name="partie_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Partie $partie, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) { 

            // Creation du formulaire pour rejoindre la partie
            $formParty = $this->createFormBuilder()
                                  ->add('submit', SubmitType::class)
                                  ->getForm();

            $formParty->handleRequest($request);

            // On va le cherche en base de données
            $utilisateur = $this->getUser();

            // Creation du formulaire pour supprimer la partie si on l'organisateur
            $deleteForm = $this->createDeleteForm($partie);
            
            $isOnParty = false ;
            $isOrganisateur = false;

            // Joueurs inscrits dans la partie
            $joueurs = $partie->getUtilisateurs()->toArray();

            $em = $this->getDoctrine()->getManager();

            $organisateur = $partie->getOrganisateur();

            if( $utilisateur === $organisateur ) {
                $isOrganisateur = true;
            }

            foreach ( $joueurs as $joueur ) {
                if( $joueur->getId() === $utilisateur->getId() ){
                    $isOnParty = true;
                } 
            }
        

            // Partie Ajax 

            if( $request->isXmlHttpRequest()) {
                if($isOnParty) {
                    $partie->removeUtilisateur($utilisateur);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($partie);
                    $em->flush();
                    $isOnParty = false;
                } else {
                    $partie->addUtilisateur($utilisateur);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($partie);
                    $em->flush();
                    $isOnParty = true;
                }

                $id = $request->get('id');
                $partie = $this->getDoctrine()->getRepository(Partie::class)->find($id);
                $utilisateurs = array_merge($partie->getUtilisateurs()->toArray());

                // On ajoute la variable isOnParty dans le retour json pour pouvoir l'exploiter en javascript
                $utilisateurs['isOnParty'] = $isOnParty;

                $encoder = new JsonEncoder();
                $normalizer = new ObjectNormalizer();

                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });

                $serializer = new Serializer(array($normalizer), array($encoder));
                
                $data =  $serializer->serialize($utilisateurs, 'json');
                $response = new Response($data);
                $response->headers->set('Content-Type', 'application/json');

                return $response;

            }

            return $this->render('partie/show.html.twig', array(
                'partie' => $partie,
                'utilisateur' => $utilisateur,
                'deleteForm' => $deleteForm->createView(),
                'formParty' => $formParty->createView(),
                'isOnParty' => $isOnParty, 
                'joueurs' => $joueurs,
                'organisateur' => $isOrganisateur
            
            )); 
        } else {
            return $this->redirectToRoute('login');
        }
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

        return $this->redirectToRoute('utilisateur_dashboard');
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
