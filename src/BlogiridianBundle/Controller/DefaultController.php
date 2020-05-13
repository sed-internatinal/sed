<?php

namespace BlogiridianBundle\Controller;

use BlogiridianBundle\Entity\Comentario;
use BlogiridianBundle\Form\Type\ComentarioType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/blog", name="the_blog")
     */
    public function indexAction(Request $request)
    {
        $qi = $this->get('qi');
        $term = $request->get('q');
        $year = $request->get('year');
        $month = $request->get('month');
        $repo_post = $this->getDoctrine()->getRepository('BlogiridianBundle:Post');
        if($term)
            $ultimos = $qi->getResultadosBlog($term,null,$year,$month);
        else
            $ultimos = $qi->getResultadosBlog(null,20,$year,$month);
        $todos = $repo_post->findBy(array('visible'=>true),array('fecha'=>'desc'),15);
        return $this->render('BlogiridianBundle:Default:index.html.twig',array('ultimos'=>$ultimos,'todos'=>$todos));
    }

    /**
     * @Route("/post/{id}/{name}", name="post")
     */
    public function postAction(Request $request,$id)
    {
        $repo_post = $this->getDoctrine()->getRepository('BlogiridianBundle:Post');
        $repo_gal = $this->getDoctrine()->getRepository('BlogiridianBundle:GaleriaPost');
        $post = $repo_post->findOneBy(array('visible'=>true,'id'=>$id),array('fecha'=>'desc'),2);
        $gals = $repo_gal->findBy(array('post'=>$id,'visible'=>true),array('orden'=>'asc'));
        $imagenes = array();
        array_push($imagenes,$post->getImage());
        foreach ($gals as $gal){
            array_push($imagenes,$gal->getImagen());
        }

        $gracias = false;
        $comentario = new Comentario();
        $comentario->setPost($post);
        $form = $this->createForm(ComentarioType::class,$comentario);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $gracias = true;
            $qi = $this->get('qi');
            $em = $this->getDoctrine()->getManager();
            $em->persist($comentario);
            $em->flush();
            $comentario = new Comentario();
            $form = $this->createForm(ComentarioType::class,$comentario);
        }
        $comentarios = $this->getDoctrine()->getRepository('BlogiridianBundle:Comentario')->findBy(array('post'=>$post,'visible'=>true), array('createdAt'=>'desc'));
        $todos = $repo_post->findBy(array('visible'=>true),array('fecha'=>'desc'),15);
        return $this->render('BlogiridianBundle:Default:the_blog.html.twig',array('post'=>$post,'imagenes'=>$imagenes,'form'=>$form->createView(),'gracias'=>$gracias, 'comentarios'=>$comentarios,'todos'=>$todos));
    }
}
