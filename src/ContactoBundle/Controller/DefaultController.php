<?php

namespace ContactoBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\ContactList;
use AppBundle\Form\Type\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/contacto", name="contacto")
     */
    public function contactoAction(Request $request)
    {
        $contacto = new ContactList();
        $lc = $this->get('translator')->getLocale();
        $form = $this->createForm(ContactType::class, $contacto,[
            "qi" => $this->get("qi"),
            'locale' => $request->getLocale()
        ]);

        $form->handleRequest($request);
        $gracias = false;
        if ($form->isValid()) {
            $gracias = true;
            $qi = $this->get('qi');
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacto);
            $em->flush();
            //ENVIAR MENSAJE

            $from = $qi->getSettingDB('mail_envio');
            $interno = $qi->getSettingDB('mail_recepcion');
            $mensaje_cliente = $qi->getTextoBigDB('mail_contacto_cliente');
            //$qi->sendMail($qi->getTextoDB('mail_contacto_cliente_asunto'), $from, $contacto->getEmail(),array(), $mensaje_cliente );

            $contacto = new ContactList();
            $lc = $this->get('translator')->getLocale();
            $form = $this->createForm(ContactType::class, $contacto, [
                "qi" => $this->get("qi"),
                'locale' => $request->getLocale()
            ]);

            return $this->render('ContactoBundle:Default:contacto.html.twig', array( "form" => $form->createView(), 'gracias'=> $gracias));
        }
        return $this->render('ContactoBundle:Default:contacto.html.twig', array( "form" => $form->createView(), 'gracias'=> $gracias));

    }
}