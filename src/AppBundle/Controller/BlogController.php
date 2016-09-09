<?php

namespace AppBundle\Controller;


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
            $breadcrumbs = $this->get("white_october_breadcrumbs");
            $breadcrumbs->addItem($slug_site, $this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
            $breadcrumbs->addItem("Noticias", $this->get("router")->generate("noticias_start",array('slug_site'=>$slug_site)));
            $breadcrumbs->addItem($noticia->getNombre(), $this->get("router")->generate("noticia_start",
                array('slug_site'=>$slug_site,'slug_noticia'=>$slug_noticia)

            ));

            return $this->render(
                $slug_site.'/noticia.html.twig',
                array('noticia'=>$noticia)
            );
        }

    }
}
