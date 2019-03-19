<?php
// src/AppBundle/Controller/RegistrationController.php

namespace AppBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        /** @var $formFactory FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $file = $user->getAvatar();
                // var_dump($user);
                // die();
                // On nomme le fichier avec le nom d'user et l'extension du fichier uploader
                $fileName = $user->getUsername() . '.' . $file->guessExtension();
                $user->setUrl($fileName);
          
                // On déplace l'image dans le dossiers "avatars" 
                $file->move( $this->getParameter('avatar_directory'), $fileName);

                $userManager->updateUser($user);    


                // if (null === $response = $event->getResponse()) {
                //     $url = $this->generateUrl('fos_user_registration_confirmed');
                //     $response = new RedirectResponse($url);
                // }

                // $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                // return $response;

                $session = new Session();
                $session = $request->getSession();
                $session->start();
                $session->getFlashBag()->add('registration_success', 'Votre profil a bien été créé vous pouvez maintenant vous connecter.');

                return $this->redirectToRoute('fos_user_security_login');
            }

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

            if (null !== $response = $event->getResponse()) {
                return $response;
            }
        }

        return $this->render('@FOSUser/Registration/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}