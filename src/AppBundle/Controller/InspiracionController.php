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
        return $this->render(
            $slug_site.'/inspiraciones-categorias.html.twig'
        );
    }

    /**
     * @Route("/{slug_site}/inspiraciones/fotos",name="inspiraciones_fotos",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesFotosAction($slug_site){
        $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(array(),array('order'=>'ASC'));
        return $this->render(
            $slug_site.'/inspiraciones-fotos.html.twig',
            array('inspiraciones'=>$inspiraciones)
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/tendencias",name="inspiraciones_tendencias",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesTendenciasAction($slug_site){
        $tendencias = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->findBy(array(),array('order'=>'ASC'));
        return $this->render(
            $slug_site.'/inspiraciones-tendencias.html.twig',
            array('tendencias'=>$tendencias)
        );
    }
    /**
     * @Route("/{slug_site}/inspiracion/gusta/{id}", name="inspiracion_me_gusta",requirements={
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
            return $this->redirectToRoute('inspiraciones_fotos',array('slug_site'=>$slug_site));
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

        $fotos_proveedores = $this->getDoctrine()->getRepository('AppBundle:Foto')->findBy(array(),array('updatedAt'=>'DESC'));
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

        return $this->render(
            $slug_site.'/inspiraciones-video.html.twig'
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/musica",name="inspiraciones_music",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesMusicAction($slug_site){

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

        $votos = $this->getDoctrine()->getRepository('AppBundle:Voto')->findBy(array(),array('order'=>'ASC'));
        return $this->render(
            $slug_site.'/inspiraciones-votos.html.twig',
            array('votos'=>$votos)
        );
    }
    /**
     * @Route("/{slug_site}/voto/gusta/{id}", name="voto_me_gusta",requirements={
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
    /**
     * @Route("/{slug_site}/tendencia/gusta/{id}", name="tendencia_me_gusta",requirements={
     *     "slug_site": "wedding|dinner|kids|party",
     *     "id": "\d+"
     * })
     */
    public function updateGustaTendenciaUserAction($slug_site,$id,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $tendencia = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->find($id);
            $userGustaTendencia = $this->getDoctrine()->getRepository('AppBundle:TendenciaUserGusta')
                ->findOneBy(array('tendencia'=>$tendencia,'user'=>$user));
            $em = $this->getDoctrine()->getManager();
            if($userGustaTendencia==NULL){
                $userGustaTendencia = new TendenciaUserGusta();
                $userGustaTendencia->setUser($user);
                $userGustaTendencia->setTendencia($tendencia);
                $em->persist($userGustaTendencia);
                $em->flush();
            }
            else{
                $em->remove($userGustaTendencia);
                $em->flush();
            }
            return $this->redirectToRoute('inspiraciones_tendencias',array('slug_site'=>$slug_site));
        }
    }

}