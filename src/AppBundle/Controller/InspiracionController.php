<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/20/16
 * Time: 8:12 AM
 */
namespace AppBundle\Controller;

use AppBundle\Entity\InspiracionUserGusta;
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
    public function inspiracionesStartAction(){
        return $this->render(
            'wedding/inspiraciones-categorias.html.twig'
        );
    }

    /**
     * @Route("/{slug_site}/inspiraciones/fotos",name="inspiraciones_fotos",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesFotosAction(){
        $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(array(),array('order'=>'ASC'));
        return $this->render(
            'wedding/inspiraciones-fotos.html.twig',
            array('inspiraciones'=>$inspiraciones)
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
     * @Route("/{slug_site}/inspiraciones/fotos-proveedores",name="inspiraciones_fotos_proveedores",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesFotosProveedoresAction($slug_site){

        $fotos_proveedores = $this->getDoctrine()->getRepository('AppBundle:Foto')->findBy(array(),array('updatedAt'=>'DESC'));

        return $this->render(
            'wedding/inspiraciones-fotos.html.twig',
            array('fotos_proveedores'=>$fotos_proveedores)
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/videos",name="inspiraciones_videos",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesVideoAction(){

        return $this->render(
            'wedding/inspiraciones-video.html.twig'
        );
    }
    /**
     * @Route("/{slug_site}/inspiraciones/musica",name="inspiraciones_music",requirements={
     *      "slug_site": "wedding|dinner|kids|party"
     *
     * })
     */
    public function inspiracionesMusicAction(){

        return $this->render(
            'wedding/inspiraciones-music.html.twig'
        );
    }

    /**
     * @Route("/inspiraciones/{slug_type}/{page}",name="inspiraciones_type", defaults={"page" = 1}, requirements={
     *     "page": "\d+"
     *
     * })
     */
    public function inspiracionesAction($slug_type,$page)
    {

        $inspiraciones_query = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(array('type'=>$slug_type));
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $inspiraciones_query,
            $page,
            2
        );

        return $this->render(
            'wedding/inspiraciones-list.html.twig',array(
                'inspiraciones'=>$pagination
            )
        );
    }
    /**
     * @Route("/inspiraciones/{slug_type}/{page}", requirements={
     *     "page": "\d+"
     * })
     */
    public function inspiracionesPagiAction($slug_category,$page)
    {

        $inspiraciones_query = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(array('type'=>$slug_category));
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $inspiraciones_query,
            $page,
            2
        );

        return $this->render(
            'wedding/proveedores-list.html.twig',array(
                'inspiraciones'=>$pagination
            )
        );
    }

    /**
     * @Route("/inspiraciones/{slug_type}/{slug_inspiracion}", name="inspiracion_detail")
     */
    public function inspiracionShowAction($slug_type,$slug_inspiracion){

        $inspiracion = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findOneBy(array('slug'=>$slug_inspiracion));

        return $this->render(
            'wedding/inspiraciones-detail.html.twig',array(
                'inspiracion'=>$inspiracion
            )
        );

    }
}