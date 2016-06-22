<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/11/16
 * Time: 3:05 PM
 */
namespace AppBundle\Controller;


use AppBundle\Entity\CategoriaListado;
use AppBundle\Entity\Foto;
use AppBundle\Entity\FotoUserGusta;
use AppBundle\Entity\Proveedor;
use AppBundle\Form\Type\FotoType;
use AppBundle\Form\Type\ProveedorChangePasswordType;
use AppBundle\Form\Type\ProveedorChangeLogoType;
use AppBundle\Form\Type\ProveedorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class FotoController extends Controller
{
    /**
     * @Route("/negocio/zona/imagenes", name="negocio_zona_imagenes")
     */
    public function zonaImagenesShowAction(Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        $fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->getProveedorFotos($proveedor);
        $foto = new Foto();
        $form = $this->createForm(new FotoType(),$foto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the Foto !
            $foto->setProveedor($proveedor);
            $foto->setIsListado(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($foto);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your added an image !');

            return $this->redirectToRoute('negocio_zona_imagenes');
        }

        return $this->render(
            'negocio/fotos.html.twig',
            array(
                'fotos'=>$fotos,
                'form'=>$form->createView()
            )
        );
    }
    /**
     * @Route("/negocio/zona/imagenes/delete/{id}", name="negocio_zona_imagenes_delete",requirements={
     *     "id": "\d+"
     * })
     */
    public function zonaImagenesDeleteAction(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $foto = $this->getDoctrine()->getRepository('AppBundle:Foto')->find($id);

        if($foto!=null){
            $em->remove($foto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Your deleted an image !');
            return $this->redirectToRoute('negocio_zona_imagenes');
        }

    }
    /**
     * @Route("/user-action/{slug_site}/proveedor/{slug_proveedor}/foto/gusta/{id}", name="proveedor_foto_me_gusta",requirements={
     *     "slug_site": "wedding|dinner|kids|party",
     *     "id": "\d+"
     * })
     */
    public function updateGustaProveedorFotoUserAction($slug_site,$slug_proveedor,$id,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $foto = $this->getDoctrine()->getRepository('AppBundle:Foto')->find($id);
            $userGustaFoto = $this->getDoctrine()->getRepository('AppBundle:FotoUserGusta')
                ->findOneBy(array('foto'=>$foto,'user'=>$user));

            $em = $this->getDoctrine()->getManager();
            if($userGustaFoto==NULL){
                $userGustaFoto = new FotoUserGusta();
                $userGustaFoto->setUser($user);
                $userGustaFoto->setFoto($foto);
                $em->persist($userGustaFoto);
                $em->flush();
            }
            else{
                $em->remove($userGustaFoto);
                $em->flush();
            }
            return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
        }
    }
    /**
     * @Route("/user-action/{slug_site}/inspiraciones/foto/gusta/{id}/page/{page}", name="inspiraciones_proveedor_foto_me_gusta",requirements={
     *     "slug_site": "wedding|dinner|kids|party",
     *     "id": "\d+",
     *     "page": "\d+",
     * })
     */
    public function updateGustaInspiracionesProveedorFotoUserAction($slug_site,$id,$page,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $foto = $this->getDoctrine()->getRepository('AppBundle:Foto')->find($id);
            $userGustaFoto = $this->getDoctrine()->getRepository('AppBundle:FotoUserGusta')
                ->findOneBy(array('foto'=>$foto,'user'=>$user));

            $em = $this->getDoctrine()->getManager();
            if($userGustaFoto==NULL){
                $userGustaFoto = new FotoUserGusta();
                $userGustaFoto->setUser($user);
                $userGustaFoto->setFoto($foto);
                $em->persist($userGustaFoto);
                $em->flush();
            }
            else{
                $em->remove($userGustaFoto);
                $em->flush();
            }
            return $this->redirectToRoute('inspiraciones_fotos_proveedores',array('slug_site'=>$slug_site,'page'=>$page));
        }
    }
}