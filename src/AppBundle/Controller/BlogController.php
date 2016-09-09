<?php

namespace AppBundle\Controller;


use AppBundle\Entity\ComentarioNoticia;
use AppBundle\Form\Type\ComentarioNoticiaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\CotizacionType;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;


class BlogController extends Controller
{
    /**
     * @Route("/{slug_site}/noticias", name="noticias_start",defaults={"page" = 1},requirements={
     *     "page": "\d+",
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function indexAction($slug_site,$page,Request $request)
    {
        $noticias = $this->getDoctrine()->getRepository('AppBundle:Noticia')->getNoticiasWithCountComments($slug_site);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site, $this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Noticias", $this->get("router")->generate("noticias_start",array('slug_site'=>$slug_site)));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $noticias,
            $page,
            6
        //array('wrap-queries'=>true)
        );

        return $this->render(
            $slug_site.'/noticias.html.twig',
            array(
                'noticias'=>$pagination,
            )
        );
    }
    /**
     * @Route("/{slug_site}/noticia/{slug_noticia}", name="noticia_start",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function noticiaAction($slug_site,$slug_noticia,Request $request)
    {

        $noticia = $this->getDoctrine()->getRepository('AppBundle:Noticia')->findOneBy(array('slug'=>$slug_noticia));
        if($noticia!=NULL){
            $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioNoticia')->getAllComments($noticia);

            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem($slug_site, $this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
            $breadcrumbs->addItem("Noticias", $this->get("router")->generate("noticias_start",array('slug_site'=>$slug_site)));
            $breadcrumbs->addItem($noticia->getNombre(), $this->get("router")->generate("noticia_start",
                array('slug_site'=>$slug_site,'slug_noticia'=>$slug_noticia)

            ));

            $renderOut = array(
                'noticia'=>$noticia,
                'comentarios'=>$comments,
            );

            $user = $this->container->get('security.context')->getToken()->getUser();

            if(is_object($user)){
                $comentarioNoticia = $this->getDoctrine()->getRepository('AppBundle:ComentarioNoticia')
                    ->findOneBy(array('noticia'=>$noticia,'user'=>$user));

                $renderOut['myc'] = $comentarioNoticia;
            }


            return $this->render(
                $slug_site.'/noticia.html.twig',
                $renderOut
            );
        }

    }
    /**
     * @Route("/user-action/{slug_site}/noticia/{slug_noticia}/comentar", name="noticia_me_comentar",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function comentarNoticiaUserAction($slug_site,$slug_noticia,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $noticia = $this->getDoctrine()->getRepository('AppBundle:Noticia')->findOneBy(array('slug'=>$slug_noticia));
        if(is_object($user)){
            //$comentarioProveedor = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')
            // ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));
            $comentarioNoticia = new ComentarioNoticia();

            $form = $this->createForm(new ComentarioNoticiaType(),$comentarioNoticia,array(
                'action' => $this->generateUrl('noticia_me_comentar',array('slug_site' => $slug_site,'slug_noticia'=>$slug_noticia)),
                'method' => 'POST',
            ));
            //$form = $this->createForm(new ComentarioProveedorType(), $comentarioProveedor);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if($form->isValid()){
                    $comentarioNoticia->setUser($user);
                    $comentarioNoticia->setNoticia($noticia);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comentarioNoticia);
                    $em->flush();

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    //return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    $url = $this->generateUrl('noticia_start',array('slug_site'=>$slug_site,'slug_noticia'=>$slug_noticia));
                    $response = new JsonResponse();
                    $response->setData(array(
                        'success' => $url
                    ));
                    return $response;
                    //return $this->redirectToRoute('task_success');
                    //$this->redirect($request->getReferer());
                }
                else{
                    $errors = $this->get('form_serializer')->serializeFormErrors($form, true, true);
                    $response = new JsonResponse();
                    $response->setData(array(
                        'errors' => $errors
                    ));
                    return $response;
                }
            }
            //$renderOut['form'] = $form->createView();
            return $this->render(
                $slug_site.'/comentario_add_noticia.html.twig',array(
                    'form'=>$form->createView()
                )
            );
        }
    }
}
