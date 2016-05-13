<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/13/16
 * Time: 9:05 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;



class AdminController extends Controller
{
    /**
     * @Route("/admin/", name="admin_start")
     */
    public function adminZonaAction(){

        return $this->render(
            'admin/home.html.twig'
        );
    }
    /**
     * @Route("/admin/negocios/{page}", name="admin_negocios", defaults={"page" = 1})
     */
    public function adminNegociosShowAction(Request $request,$page){


        $proveedores_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresOrderRecent();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $proveedores_query,
            $page,
            2
        //array('wrap-queries'=>true)
        );

        return $this->render(
            'admin/negocios.html.twig',array(
                'proveedores'=>$pagination,
            )
        );

    }
    /**
     * @Route("/admin/negocio/disable/{id}", name="admin_negocios_disable",requirements={
     *     "id": "\d+",
     * })
     */
    public function adminNegociosDisableAction(Request $request,$id){


        $p =  $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($id);
        if($p!=NULL){
            $p->setIsActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Proveedor is disable !');

            return $this->redirectToRoute('admin_negocio_show',array('id'=>$id));
        }

    }
    /**
     * @Route("/admin/negocio/enable/{id}", name="admin_negocios_enable",requirements={
     *     "id": "\d+",
     * })
     */
    public function adminNegociosEnableAction(Request $request,$id){
        $p =  $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($id);
        if($p!=NULL){
            $p->setIsActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Proveedor is enable !');

            return $this->redirectToRoute('admin_negocio_show',array('id'=>$id));
        }
    }
    /**
     * @Route("/admin/negocio/{id}", name="admin_negocio_show",requirements={
     *     "id": "\d+",
     * })
     */
    public function adminNegocioShowAction(Request $request,$id){
        $p =  $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($id);

        return $this->render(
            'admin/negocio.html.twig',array(
                'proveedor'=>$p,
            )
        );

    }

}