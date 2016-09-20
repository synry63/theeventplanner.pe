<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/20/16
 * Time: 8:12 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\InspiracionUserGusta;
use AppBundle\Entity\TendenciaUserGusta;
use AppBundle\Entity\VotoUserGusta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InspiracionController extends Controller
{
    /**
     * @Route("/{slug_site}/inspiraciones/",name="inspiraciones_start",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesStartAction($slug_site){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site, $this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Inspiraciones", $this->get("router")->generate("inspiraciones_start",array('slug_site'=>$slug_site)));

        return $this->render(
            $slug_site.'/inspiraciones-categorias.html.twig'
        );
    }

    /**
     * @Route("/{slug_site}/inspiraciones/{tendencia_slug}/fotos",name="inspiraciones_fotos",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesFotosAction($slug_site,$tendencia_slug){

        $tendencia = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->findOneBy(array('slug'=>$tendencia_slug));

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site,$this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Inspiraciones",$this->get("router")->generate("inspiraciones_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Tendencias", $this->get("router")->generate("inspiraciones_tendencias",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem($tendencia->getNombre(), $this->get("router")->generate("inspiraciones_fotos",
            array(
                'slug_site'=>$slug_site,
                'tendencia_slug'=>$tendencia_slug
            ))
        );


        $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(
            array('tendencia'=>$tendencia),
            array('sort'=>'ASC'));
        return $this->render(
            $slug_site.'/inspiraciones-fotos.html.twig',
            array('inspiraciones'=>$inspiraciones,
                  'tendencia'=>$tendencia
            )
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/tendencias",name="inspiraciones_tendencias",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesTendenciasAction($slug_site){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site,$this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Inspiraciones",$this->get("router")->generate("inspiraciones_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Tendencias", $this->get("router")->generate("inspiraciones_tendencias",array('slug_site'=>$slug_site)));


        $tendencias = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->findBy(
            array('type'=>$slug_site),
            array('sort'=>'ASC'));
        return $this->render(
            $slug_site.'/inspiraciones-tendencias.html.twig',
            array('tendencias'=>$tendencias)
        );
    }
    /**
     * @Route("/user-action/{slug_site}/inspiracion/gusta/{id}", name="inspiracion_me_gusta",requirements={
     *     "slug_site": "wedding|dinner|kids|party",
     *     "id": "\d+"
     * })
     */
    public function updateGustaInspiracionUserAction($slug_site,$id,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $inspiracion = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->find($id);
            $userGustaInspiracion = $this->getDoctrine()->getRepository('AppBundle:InspiracionUserGusta')
                ->findOneBy(array('inspiracion'=>$inspiracion,'user'=>$user));
            $em = $this->getDoctrine()->getManager();
            if($userGustaInspiracion==NULL){
                $userGustaInspiracion = new InspiracionUserGusta();
                $userGustaInspiracion->setUser($user);
                $userGustaInspiracion->setInspiracion($inspiracion);
                $em->persist($userGustaInspiracion);
                $em->flush();
            }
            else{
                $em->remove($userGustaInspiracion);
                $em->flush();
            }
            return $this->redirectToRoute('inspiraciones_fotos',array('slug_site'=>$slug_site,
                'tendencia_slug'=>$inspiracion->getTendencia()->getSlug()));
        }
    }

    /**
     * @Route("/{slug_site}/inspiraciones/fotos-proveedores/{page}",name="inspiraciones_fotos_proveedores",defaults={"page" = 1},requirements={
     *     "page": "\d+",
     *     "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesFotosProveedoresAction($slug_site,$page){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site,$this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Inspiraciones",$this->get("router")->generate("inspiraciones_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Fotos Proveedores",$this->get("router")->generate("inspiraciones_fotos_proveedores",array('slug_site'=>$slug_site)));


        $cate = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_site));
        $fotos_proveedores = $this->getDoctrine()->getRepository('AppBundle:Foto')->getFotosByCate($cate);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $fotos_proveedores,
            $page,
            12
        //array('wrap-queries'=>true)
        );
        return $this->render(
            $slug_site.'/inspiraciones-fotos-proveedores.html.twig',
            array('fotos_proveedores'=>$pagination)
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/videos",name="inspiraciones_videos",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesVideoAction($slug_site){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site,$this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Inspiraciones",$this->get("router")->generate("inspiraciones_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Videos",$this->get("router")->generate("inspiraciones_videos",array('slug_site'=>$slug_site)));

        $videos = $this->getDoctrine()->getRepository('AppBundle:Video')->findBy(
            array('type'=>$slug_site),
            array('sort' => 'ASC')
        );


        return $this->render(
            $slug_site.'/inspiraciones-video.html.twig',
            array('videos'=>$videos)
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/musica",name="inspiraciones_music",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesMusicAction($slug_site){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site,$this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Inspiraciones",$this->get("router")->generate("inspiraciones_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Canciones",$this->get("router")->generate("inspiraciones_music",array('slug_site'=>$slug_site)));

        return $this->render(
            $slug_site.'/inspiraciones-music.html.twig'
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/votos",name="inspiraciones_votos",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesVotosAction($slug_site){

        $votos = $this->getDoctrine()->getRepository('AppBundle:Voto')->findBy(array(),array('sort'=>'ASC'));

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site,$this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Inspiraciones",$this->get("router")->generate("inspiraciones_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Votos",$this->get("router")->generate("inspiraciones_votos",array('slug_site'=>$slug_site)));

        return $this->render(
            $slug_site.'/inspiraciones-votos.html.twig',
            array('votos'=>$votos)
        );
    }
    /**
     * @Route("/user-action/{slug_site}/voto/gusta/{id}", name="voto_me_gusta",requirements={
     *     "slug_site": "wedding|dinner|kids|party",
     *     "id": "\d+"
     * })
     */
    public function updateGustaVotoUserAction($slug_site,$id,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $voto = $this->getDoctrine()->getRepository('AppBundle:Voto')->find($id);
            $userGustaVoto = $this->getDoctrine()->getRepository('AppBundle:VotoUserGusta')
                ->findOneBy(array('voto'=>$voto,'user'=>$user));
            $em = $this->getDoctrine()->getManager();
            if($userGustaVoto==NULL){
                $userGustaVoto = new VotoUserGusta();
                $userGustaVoto->setUser($user);
                $userGustaVoto->setVoto($voto);
                $em->persist($userGustaVoto);
                $em->flush();
            }
            else{
                $em->remove($userGustaVoto);
                $em->flush();
            }
            return $this->redirectToRoute('inspiraciones_votos',array('slug_site'=>$slug_site));
        }
    }

}