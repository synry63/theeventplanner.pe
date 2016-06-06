<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/2/16
 * Time: 12:01 PM
 */
namespace AppBundle\Controller;


use AppBundle\Entity\FotoProfile;
use AppBundle\Form\Type\FotoProfileType;
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
     * @Route("/profile/foto", name="change_user_foto")
     */
    public function userFotoProfileShowAction(Request $request){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $profile = $user->getProfile();

        if($profile==NULL){
            $profile = new FotoProfile();
            $profile->setUser($user);
        }

        $form = $this->createForm(new FotoProfileType(), $profile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($profile);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your foto had change !');
            return $this->redirectToRoute('change_user_foto');
        }

        return $this->render(
            'FOSUserBundle:Profile:change_foto.html.twig',
            array(
                'profile' => $profile,
                'form' => $form->createView())
        );
    }
}