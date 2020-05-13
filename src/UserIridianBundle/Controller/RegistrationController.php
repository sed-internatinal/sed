<?php

namespace UserIridianBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use RuntimeException;


class RegistrationController extends BaseController
{
    /**
     * @Route("/registro-login", name="registro_login")
     */
    public function registerAction(Request $request)
    {

        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $url = $this->generateUrl('carrito');
            $response = new RedirectResponse($url);
            return $response;
        }
        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
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

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                //$url = $this->generateUrl('fos_user_registration_confirmed');
                //if(strpos($ruta, 'registro_login') !== false )
                $url = $this->generateUrl('carrito');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        //return $this->render('UserIridianBundle:Default:registro_login.html.twig', array('form' => $form->createView()));
        return $this->render('CarroiridianBundle:Default:datos.html.twig', array('form' => $form->createView(), 'csrf_token' => $csrfToken, 'pasos'=>true));
    }

    /**
     * @Route("/registro-login-inicial", name="registro_login_inicial")
     */
    public function registerNewAction(Request $request)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $url = $this->generateUrl('homepage');
            $response = new RedirectResponse($url);
            return $response;
        }
        $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
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

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                //$url = $this->generateUrl('fos_user_registration_confirmed');
                $url = $this->generateUrl('homepage');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        //return $this->render('UserIridianBundle:Default:registro_login.html.twig', array('form' => $form->createView()));
        return $this->render('CarroiridianBundle:Default:datos.html.twig', array('form' => $form->createView(), 'csrf_token' => $csrfToken, 'pasos'=>false));
    }


    public function loginpartialAction()
    {
        try {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } catch (RuntimeException $e) {
            $csrfToken = null;
        }
        return $this->render(
            'default/LoginPartial.html.twig',
            array('csrf_token' => $csrfToken)
        );
    }
}