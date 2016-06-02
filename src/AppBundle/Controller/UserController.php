<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/2/16
 * Time: 12:01 PM
 */
namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class UserController extends Controller
{

    /**
     * @Route("/profile/comments", name="show_user_comments")
     */
    public function userCommentsShowAction()
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
        $ccoments_proveedores = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')->findBy(array('user'=>$user));
        $ccoments_inspiraciones = $this->getDoctrine()->getRepository('AppBundle:ComentarioInspiracion')->findBy(array('user'=>$user));
        return $this->render(
            'FOSUserBundle:Profile:show_comments.html.twig',
            array(
                'comentarios_proveedores'=>$ccoments_proveedores,
                'comentarios_inspiraciones'=>$ccoments_inspiraciones,
            )
        );

    }
    /**
     * @Route("/profile/fotos-favoritas-proveedores", name="show_user_favoritos_fotos")
     */
    public function userFavoritosFotosShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $fotos = $this->getDoctrine()->getRepository('AppBundle:FotoUserGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_fotos_favoritos.html.twig',
            array(
                'fotos_proveedor_favoritas'=>$fotos,
            )
        );
    }
    /**
     * @Route("/profile/fotos-favoritas-inspiraciones", name="show_user_favoritos_fotos_inspiraciones")
     */
    public function userFavoritosFotosInspiracionesShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $fotos = $this->getDoctrine()->getRepository('AppBundle:InspiracionUserGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_fotos_inspiraciones_favoritos.html.twig',
            array(
                'fotos_inspiraciones_favoritas'=>$fotos,
            )
        );
    }
    /**
     * @Route("/profile/proveedores-favoritos", name="show_user_favoritos_proveedores")
     */
    public function userFavoritosProveedoresShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $proveedores = $this->getDoctrine()->getRepository('AppBundle:UserProveedorGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_proveedores_favoritos.html.twig',
            array(
                'proveedores_favoritos'=>$proveedores,
            )
        );
    }

    /**
     * @Route("/profile/votos", name="show_user_votos")
     */
    public function userFavoritosVotosShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $votos = $this->getDoctrine()->getRepository('AppBundle:VotoUserGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_votos_favoritos.html.twig',
            array(
                'votos'=>$votos,
            )
        );
    }
    /**
     * @Route("/profile/tendencias", name="show_user_tendencias")
     */
    public function userFavoritosTendenciasShowAction(){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $tendiencias = $this->getDoctrine()->getRepository('AppBundle:TendenciaUserGusta')
            ->findBy(
                array('user'=>$user),
                array('updatedAt'=>'DESC')
            );
        return $this->render(
            'FOSUserBundle:Profile:show_tendencias_favoritos.html.twig',
            array(
                'tendiencias'=>$tendiencias,
            )
        );
    }
}