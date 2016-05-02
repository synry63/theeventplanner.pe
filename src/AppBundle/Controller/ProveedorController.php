<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 6:10 PM
 */
namespace AppBundle\Controller;


use AppBundle\Entity\CategoriaListado;
use AppBundle\Entity\Proveedor;
use AppBundle\Form\Type\ProveedorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ProveedorController extends Controller
{
    /**
     * @Route("/negocio/registrar", name="register_negocio_start")
     */
    public function registerAction(Request $request)
    {

        $proveedor = new Proveedor();
        //$categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findAll();


        //$proveedor->test($categoria);
        $in = array();
        $categorias = array();
        // weddings
        $categoria_wdding = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'wedding'));
        $in['wedding'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_wdding);
        // dinners
        $categoria_dinner = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'dinner'));
        $in['dinner'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_dinner);
        // kids
        $categoria_kids = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'kids'));
        $in['kids'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_kids);
        // party
        $categoria_party = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'party'));
        $in['party'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_party);

        $form = $this->createForm(new ProveedorType($in), $proveedor);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //$data = $form->getData();
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($proveedor, $proveedor->getPlainPassword());
            $proveedor->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();
        }

        return $this->render(
            'negocio.html.twig',array(
                //'commentarios'=>$comments,
                'form' => $form->createView()
            )
        );
    }
    /**
     * @Route("/{slug_site}/proveedores/{slug_category}/{page}",name="proveedores_category", defaults={"page" = 1},requirements={
     *     "page": "\d+",
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     *
     */
    public function proveedoresAction($slug_site,$slug_category,$page)
    {


        $categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_category));
        $main_categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_site));
        $proveedores_category_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByCategory($main_categoria,$categoria);

        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_category);
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $proveedores_category_query,
            $page,
            2,
            array('wrap-queries'=>true)
        );

        return $this->render(
            $slug_site.'/proveedores-list.html.twig',array(
                'proveedores'=>$pagination,
                'categorias_hijos'=>$children_categories
            )
        );
    }
    /**
     * @Route("/{slug_site}/proveedores/{slug_category}/{page}", requirements={
     *     "page": "\d+",
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function proveedoresPagiAction($slug_site,$slug_category,$page)
    {

        $categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_category));
        $main_categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_site));
        $proveedores_category_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByCategory($main_categoria,$categoria);

        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_category);
        $paginator  = $this->get('knp_paginator');


        $pagination = $paginator->paginate(
            $proveedores_category_query,
            $page,
            2
        );


        return $this->render(
            $slug_site.'/proveedores-list.html.twig',array(
                'proveedores'=>$pagination,
                'categorias_hijos'=>$children_categories
            )
        );
    }
    /**
     * @Route("/{slug_site}/proveedores/{slug_category}/{slug_proveedor}", name="proveedor_detail",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function proveedorShowAction($slug_site,$slug_category,$slug_proveedor,Request $request){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $proveedor = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(array('slug'=>$slug_proveedor));
        //$comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')->getAllComments($proveedor);


        if(is_object($user)){
            $comentarioProveedor = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')
                ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));
            if($comentarioProveedor==null){
                $comentarioProveedor = new ComentarioProveedor();
            }

            $form = $this->createForm(ComentarioProveedorType::class, $comentarioProveedor);

            $form->handleRequest($request);

            if ($form->isValid()) {
                //$data = $form->getData();
                $comentarioProveedor->setUser($user);
                $comentarioProveedor->setProveedor($proveedor);
                //$comentarioProveedor->setNota($data->getNota());
                //$comentarioProveedor->setComentario($data->getComentario());
                $em = $this->getDoctrine()->getManager();
                $em->persist($comentarioProveedor);
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'Your comment is save !');

                //return $this->redirect($this->generateUrl('proveedor_detail'));
                //return $this->redirectToRoute('task_success');
                //$this->redirect($request->getReferer());
            }
            return $this->render(
                $slug_site.'/proveedores-detail.html.twig',array(
                    'proveedor'=>$proveedor,
                    //'commentarios'=>$comments,
                    'form' => $form->createView()
                )
            );
        }
        else{
            return $this->render(
                'wedding/proveedores-detail.html.twig',array(
                    'proveedor'=>$proveedor,
                    //'commentarios'=>$comments,
                )
            );
        }


    }
}