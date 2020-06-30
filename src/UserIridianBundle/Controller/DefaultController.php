<?php

namespace UserIridianBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserIridianBundle\Form\RegistrationEnterType;

class DefaultController extends Controller
{
    /**
     * @Route("/useriridian")
     */
    public function indexAction()
    {
        return $this->render('UserIridianBundle:Default:index.html.twig');
    }

    /**
     * @Route("/registro", name="registro")
     */
    public function registroAction()
    {
        return $this->render('UserIridianBundle:Default:registro.html.twig');
    }

    /**
     * @Route("/registro-empresa", name="registro-empresa")
     */
    public function registro_empresaAction(Request $request)
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
        $user->setUsername($request->request->get("fos_user_registration_form['email']"));
        $user->setEnabled(false);
        $user->setEsEmpresa(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }


        $form = $this->createForm(RegistrationEnterType::class, $user);
        $form->setData($user);

        $form->handleRequest($request);
        $captcha=false;
        $registrado=false;
        if ($form->isValid() && $this->captchaverify($request->get('g-recaptcha-response')) ) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            //dump($user);

            $file = $user->getCertificadoCamara();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('path_empresas'),
                $fileName
            );
            $user->setCertificadoCamara($fileName);

            $file = $user->getEstadosFinancieros();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('path_empresas'),
                $fileName
            );
            $user->setEstadosFinancieros($fileName);

            $file = $user->getDeclaracion1();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('path_empresas'),
                $fileName
            );
            $user->setDeclaracion1($fileName);

            $file = $user->getDeclaracion2();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('path_empresas'),
                $fileName
            );
            $user->setDeclaracion2($fileName);

            $file = $user->getCedulaRepresentante();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('path_empresas'),
                $fileName
            );
            $user->SetCedulaRepresentante($fileName);

            $file = $user->getRut();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('path_empresas'),
                $fileName
            );
            $user->SetRut($fileName);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                //$url = $this->generateUrl('fos_user_registration_confirmed');
                //if(strpos($ruta, 'registro_login') !== false )
                $url = $this->generateUrl('carrito');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            //return $response;
            $registrado=true;

            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath().$this->container->getParameter('app.path.empresas')."/";

            $qi=$this->get("qi");
            $subject=$qi->getTextoDB("asunto_registro_cliente");
            $from=$qi->getSettingDB("mail_envio");
            $to=$user->getEmail();
            $mensaje=$qi->getTextoBIgDB("mail_registro_cliente");
            $qi->sendMail($subject,$from,$to,null,$mensaje);

            $subject=$qi->getTextoDB("asunto_registro_interno");
            $from=$qi->getSettingDB("mail_envio");
            $to=$qi->getSettingDB("mail_registro_empresas");
            $mensaje=$qi->getTextoBIgDB("mail_registro_empresas_interno");
            $mensaje = str_replace("%razon%",$user->getNombre(),$mensaje);
            $mensaje = str_replace("%nit%",$user->getNit(),$mensaje);
            $mensaje = str_replace("%verificacion%",$user->getDVerificacion(),$mensaje);
            $mensaje = str_replace("%correo%",$user->getEmail(),$mensaje);
            $mensaje = str_replace("%telefono%",$user->getTelefono(),$mensaje);
            $mensaje = str_replace("%celular%",$user->getCelular(),$mensaje);
            $mensaje = str_replace("%ciudad%",$user->getCiudad(),$mensaje);
            $mensaje = str_replace("%cert_camara%",'<a target="_blank" href="'.$baseurl.$user->getCertificadoCamara().'">ver</a>',$mensaje);
            $mensaje = str_replace("%financieros%",'<a target="_blank" href="'.$baseurl.$user->getEstadosFinancieros().'">ver</a>',$mensaje);
            $mensaje = str_replace("%renta1%",'<a target="_blank" href="'.$baseurl.$user->getDeclaracion1().'">ver</a>',$mensaje);
            $mensaje = str_replace("%renta2%",'<a target="_blank" href="'.$baseurl.$user->getDeclaracion2().'">ver</a>',$mensaje);
            $mensaje = str_replace("%cedula%",'<a target="_blank" href="'.$baseurl.$user->getCedulaRepresentante().'">ver</a>',$mensaje);
            $mensaje = str_replace("%rut%",'<a target="_blank" href="'.$baseurl.$user->getRut().'">ver</a>',$mensaje);
            $qi->sendMail($subject,$from,$to,null,$mensaje);
        }

        if($form->isSubmitted() &&  $form->isValid() && !$this->captchaverify($request->get('g-recaptcha-response'))){

            $captcha=true;
        }

        //return $this->render('UserIridianBundle:Default:registro_login.html.twig', array('form' => $form->createView()));
        return $this->render('UserIridianBundle:Default:registro_empresa.html.twig', array('registrado'=>$registrado,'form' => $form->createView(), 'csrf_token' => $csrfToken, 'pasos'=>true,"captcha"=>$captcha));


    }

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/actulizar-datos-empresa", name="actulizar-datos-empresa")
     */
    public function actulizardatos_empresaAction()
    {
        return $this->render('UserIridianBundle:Default:actulizar_datos_empr.html.twig');
    }

    /**
     * @Route("/convenios/{codigo}", name="convenios")
     */
    public function conveniosAction(Request $request,$codigo)
    {
        $convenio=$this->getDoctrine()->getRepository("CarroiridianBundle:Convenio")->findOneBy(array("codigo"=>$codigo,"activo"=>1));
        $ret = array("ok"=>false,"data"=>"0");
        if($convenio){
            $ret = array("ok"=>true,"data"=>$convenio->getId());
        }
        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($ret,'json');
        return new Response($json);

    }

    /**
     * @Route("/registro-persona", name="registro-persona")
     */
    public function registro_pesonaAction(Request $request)
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
        $user->setUsername($request->request->get("fos_user_registration_form['email']"));
        $user->setEnabled(true);
        $user->setEsEmpresa(false);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);
        $captcha=false;
        $registrado=false;
        if ($form->isValid() && $this->captchaverify($request->get('g-recaptcha-response')) ) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            $user->setUpdatedAt(new \DateTime());
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                //$url = $this->generateUrl('fos_user_registration_confirmed');
                //if(strpos($ruta, 'registro_login') !== false )
                $url = $this->generateUrl('carrito');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            $registrado=true;

            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath().$this->container->getParameter('app.path.empresas')."/";

            $qi=$this->get("qi");
            $subject=$qi->getTextoDB("asunto_registro_cliente");
            $from=$qi->getSettingDB("mail_envio");
            $to=$user->getEmail();
            $mensaje=$qi->getTextoBIgDB("mail_registro_cliente");
            $qi->sendMail($subject,$from,$to,null,$mensaje);

            $subject=$qi->getTextoDB("asunto_registro_interno");
            $from=$qi->getSettingDB("mail_envio");
            $to=$qi->getSettingDB("mail_registro_personas");
            $mensaje=$qi->getTextoBIgDB("mail_registro_personas_interno");
            $mensaje = str_replace("%nombre%",$user->getNombre(),$mensaje);
            $mensaje = str_replace("%tipodoc%",$user->getTipodoc()->getNombreEs(),$mensaje);
            $mensaje = str_replace("%documento%",$user->getDocumento(),$mensaje);
            $mensaje = str_replace("%correo%",$user->getEmail(),$mensaje);
            $mensaje = str_replace("%telefono%",$user->getTelefono(),$mensaje);
            $mensaje = str_replace("%celular%",$user->getCelular(),$mensaje);
            $mensaje = str_replace("%convenio%",$user->getConvenio(),$mensaje);
            $qi->sendMail($subject,$from,$to,null,$mensaje);
            //return $response;
        }

        if($form->isSubmitted() &&  $form->isValid() && !$this->captchaverify($request->get('g-recaptcha-response'))){

            $captcha=true;
        }

        //return $this->render('UserIridianBundle:Default:registro_login.html.twig', array('form' => $form->createView()));
        return $this->render('UserIridianBundle:Default:registro_persona.html.twig', array('registrado'=>$registrado,'form' => $form->createView(), 'csrf_token' => $csrfToken, 'pasos'=>true,"captcha"=>$captcha));

    }

    function captchaverify($recaptcha){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret"=>"6Ld2pdMUAAAAAC1wjO5PtYoofvztGXa1CSQrm9C0","response"=>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }

    /**
     * @Route("/actulizar-datos-persona", name="actulizar-datos-persona")
     */
    public function actulizardatos_personaAction()
    {
        return $this->render('UserIridianBundle:Default:actualizar_datos_persona.html.twig');
    }
}
