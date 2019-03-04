<?php

namespace PartyBundle\Controller;

use PartyBundle\PartyBundle;
use PartyBundle\Entity\Party;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;
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
 * Party controller.
 *
 * @Route("party")
 */
class PartyController extends Controller
{
    /**
     * Lists all partie entities.
     *
     * @Route("/", name="party_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parties = $em->getRepository('PartyBundle:Party')->findAll();

        return $this->render('party/index.html.twig', array(
            'parties' => $parties,
        ));
    }
    
    /**
     * Show all football parties
     *
     * @Route("/foot", name="party_football")
     * @Method({"GET"})
     */
    public function showFootAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Party::class);
            $partiesFoot =  $repository->findBy(
                array('sport' => 1)
            );

            return $this->render('party/show_foot.html.twig', array(
                'parties' => $partiesFoot,
            ));
        } else {
            return $this->redirectToRoute('login');
        }

    }

    /**
     * Show all basketball parties
     *
     * @Route("/basketball", name="party_basketball")
     * @Method({"GET"})
     */
    public function showBasketAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Party::class);
            $partiesBasket =  $repository->findBy(
                array('sport' => 2 )
            );

            return $this->render('party/show_basket.html.twig', array(
                'parties' => $partiesBasket,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * Show all tennis parties
     *
     * @Route("/tennis", name="party_tennis")
     * @Method({"GET"})
     */
    public function showTennisAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Party::class);
            $partiesTennis =  $repository->findBy(
                array('sport' => 3 )
            );

            return $this->render('party/show_tennis.html.twig', array(
                'parties' => $partiesTennis,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * Show all padel parties
     *
     * @Route("/padel", name="party_padel")
     * @Method({"GET"})
     */
    public function showPadelAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {       
            $repository = $this->getDoctrine()->getRepository(Party::class);
            $partiesPadel =  $repository->findBy(
                array('sport' => 4 )
            );

            return $this->render('party/show_padel.html.twig', array(
                'parties' => $partiesPadel,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }

    /**
     * Show all handball parties
     *
     * @Route("/handball", name="party_handball")
     * @Method({"GET"})
     */
    public function showHandAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $repository = $this->getDoctrine()->getRepository(Party::class);
            $partiesHand =  $repository->findBy(
                array('sport' => 5 )
            );

            return $this->render('party/show_hand.html.twig', array(
                'parties' => $partiesHand,
            ));
        } else {
            return $this->redirectToRoute('login');
        }
    }



    /**
     * Creates a new partie entity.
     *
     * @Route("/new", name="party_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $party = new Party();
            $form = $this->createForm('PartyBundle\Form\PartyType', $party);
            $form->handleRequest($request);

            // On va le cherche en base de données
            $user = $this->getUser();

            // Par défaut une partie n'est pas terminé
            $party->setIsFinished( 0 );
            
            // On set l'utilisateur grace à son id stocké en session
            $party->setOrganiser($user);


            if ($form->isSubmitted() && $form->isValid()) {
                $party->addPlayer($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($party);
                $em->flush();
                return $this->redirectToRoute('party_show', array('id' => $party->getId()));
            }

            return $this->render('party/new.html.twig', array(
                'party' => $party,
                'form' => $form->createView(),
                'user' => $user
            ));
        } else {
            return $this->redirectToRoute('login');
        }
        


    }

    /**
     * Finds and displays a partie entity.
     *
     * @Route("/{id}", name="party_show")
     * @Method({"GET", "POST"})
     */
    public function showAction(Party $party, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) { 

            // Creation du formulaire pour rejoindre la partie
            $formParty = $this->createFormBuilder()
                                  ->add('submit', SubmitType::class)
                                  ->getForm();

            $formParty->handleRequest($request);

            // On va le cherche en base de données
            $user = $this->getUser();

            // Creation du formulaire pour supprimer la partie si on l'organisateur
            $deleteForm = $this->createDeleteForm($party);
            
            $isOnParty = false ;
            $isOrganiser = false;

            // Joueurs inscrits dans la partie
            $players = $party->getPlayers()->toArray();

            $em = $this->getDoctrine()->getManager();

            $organiser = $party->getOrganiser();

            if( $user === $organiser ) {
                $isOrganiser = true;
            }

            foreach ( $players as $player ) {
                if( $player->getId() === $user->getId() ){
                    $isOnParty = true;
                } 
            }
        
            // if($formParty->isSubmitted()){
            //     $party->addUtilisateur($user);
            //     $em = $this->getDoctrine()->getManager();
            //     $em->persist($party);
            //     $em->flush();
            //     $isOnParty = true;
            // }

            // Partie Ajax 

            if( $request->isXmlHttpRequest()) {
                if($isOnParty) {
                    $party->removePlayer($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($party);
                    $em->flush();
                    $isOnParty = false;
                } else {
                    $party->addPlayer($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($party);
                    $em->flush();
                    $isOnParty = true;
                }

                $id = $request->get('id');
                $party = $this->getDoctrine()->getRepository(Party::class)->find($id);
                $players = array_merge($party->getPlayers()->toArray());

                // On ajoute la variable isOnParty dans le retour json pour pouvoir l'exploiter en javascript
                $players['isOnParty'] = $isOnParty;

                $encoder = new JsonEncoder();
                $normalizer = new ObjectNormalizer();

                $normalizer->setCircularReferenceHandler(function ($object) {
                    return $object->getId();
                });

                $serializer = new Serializer(array($normalizer), array($encoder));
                
                $data =  $serializer->serialize($players, 'json');
                $response = new Response($data);
                $response->headers->set('Content-Type', 'application/json');

                return $response;

            }

            return $this->render('party/show.html.twig', array(
                'party' => $party,
                'user' => $user,
                'deleteForm' => $deleteForm->createView(),
                'formParty' => $formParty->createView(),
                'isOnParty' => $isOnParty, 
                'players' => $players,
                'organiser' => $isOrganiser
            
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
    public function editAction(Request $request, Party $party)
    {
        $deleteForm = $this->createDeleteForm($party);
        $editForm = $this->createForm('PartyBundle\Form\PartyType', $party);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('party_edit', array('id' => $party->getId()));
        }

        return $this->render('party/edit.html.twig', array(
            'party' => $party,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a partie entity.
     *
     * @Route("/{id}", name="party_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Party $party)
    {
        $form = $this->createDeleteForm($party);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($party);
            $em->flush();
        }

        return $this->redirectToRoute('user_dashboard');
    }

    /**
     * Creates a form to delete a partie entity.
     *
     * @param Party $party The party entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Party $party)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('party_delete', array('id' => $party->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
