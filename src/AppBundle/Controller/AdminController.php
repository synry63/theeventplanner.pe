<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/13/16
 * Time: 9:05 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Tendencia;
use AppBundle\Form\Type\TendenciaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;



class AdminController extends Controller
{
    /**
     * @param $text
     * @return mixed|string
     * slugify a text
     */
    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @Route("/admin/", name="admin_start")
     */
    public function adminZonaAction(){

        return $this->render(
            'admin/home.html.twig'
        );
    }
    /**
     * @Route("/admin/tendencias", name="admin_tendencias")
     */
    public function adminTendenciasAction(){
        $tendencias = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->findBy(
            array(),
            array('sort' => 'ASC')
        );
        return $this->render(
            'admin/tendencias.html.twig',
            array(
                'tendencias'=>$tendencias
            )
        );
    }
    /**
     * @Route("/admin/tendencia/delete/{id}", name="admin_tendencia_delete")
     */
    public function adminTendenciaDeleteAction(Request $request,$id){

    }
    /**
     * @Route("/admin/tendencia/add", name="admin_tendencia_add")
     */
    public function adminTendenciaAddAction(Request $request){
        $tendencia = new Tendencia();
        $form = $this->createForm(new TendenciaType(), $tendencia);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $slug = $this->slugify($tendencia->getNombre());
            $tendencia->setSlug($slug);
            //$logo->setProveedor($proveedor);
            $em->persist($tendencia);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your tendencia saved !');
            return $this->redirectToRoute('admin_tendencias');
        }
        return $this->render(
            'admin/tendencia_add.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
    /**
     * @Route("/admin/tendencia/{id}", name="admin_tendencias_edit")
     */
    public function adminTendenciaAction($id){
        $tendencia = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->find($id);

        return $this->render(
            'admin/tendencias.html.twig',
            array(
                'tendencia'=>$tendencia
            )
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