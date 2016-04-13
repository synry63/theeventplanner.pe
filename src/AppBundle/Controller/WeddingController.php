<?php
namespace AppBundle\Controller;

use AppBundle\Entity\ComentarioProveedor;
use AppBundle\Form\Type\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 *
 * @Route("/wedding")
 *
 */

class WeddingController extends Controller
{
    /**
     * @Route("/", name="wedding_start")
     */
    public function indexAction(Request $request)
    {

        $best_proveedores = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getBestProveedores('wedding');

        $best_inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->getBestInspiraciones();

        return $this->render(
            'wedding/home.html.twig',array(
                'mejores_proveedores'=>$best_proveedores,
                'mejores_inspiraciones'=>$best_inspiraciones
            )
        );

        /*
         * OTHER SOLUCION
         * $em = $this->container->get('doctrine')->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('cp, avg(cp.nota) as mymoy', 'p')
            ->from('AppBundle\Entity\ComentarioProveedor', 'cp')
            ->join('cp.proveedor','p');
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('p');
        $query = $qb->getQuery();
        $test = $query->getResult();
        return $this->render(
            'wedding/home.html.twig',array('test'=>$test)
        );
        */
        /**
         * OTHER SOLUCION
         */
        /*$ComentarioProveedorRepository = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor');
        $qb = $ComentarioProveedorRepository->createQueryBuilder('cp')
             ->select('cp,avg(cp.nota) as mymoy');
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('cp.proveedor');
        $query = $qb->getQuery();
        $test = $query->getResult();
        return $this->render(
            'wedding/home.html.twig',array('test'=>$test)
        );*/

    }
    /**
     * @Route("/proveedores/{slug_category}/{page}",name="proveedores_category", defaults={"page" = 1}, requirements={
     *     "page": "\d+"
     *
     * })
     */
    public function proveedoresAction($slug_category,$page)
    {

        $categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_category));
        $proveedores_category_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByCategory($categoria);
        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_category);
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $proveedores_category_query,
            $page,
            2
        );

        return $this->render(
            'wedding/proveedores-list.html.twig',array(
                'proveedores'=>$pagination,
                'categorias_hijos'=>$children_categories
            )
        );
    }
    /**
     * @Route("/proveedores/{slug_category}/{page}", requirements={
     *     "page": "\d+"
     * })
     */
    public function proveedoresPagiAction($slug_category,$page)
    {

        $categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_category));
        $proveedores_category_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByCategory($categoria);
        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_category);
        $paginator  = $this->get('knp_paginator');


        $pagination = $paginator->paginate(
            $proveedores_category_query,
            $page,
            2
        );


        return $this->render(
            'wedding/proveedores-list.html.twig',array(
                'proveedores'=>$pagination,
                'categorias_hijos'=>$children_categories
            )
        );
    }
    /**
     * @Route("/proveedores/{slug_category}/{slug_proveedor}", name="proveedor_detail")
     */
    public function proveedorShowAction($slug_category,$slug_proveedor){

        $proveedor = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(array('slug'=>$slug_proveedor));

        return $this->render(
            'wedding/proveedores-detail.html.twig',array(
                'proveedor'=>$proveedor
            )
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
    /**
     * @Route("/contactenos", name="wedding_contactenos")
     */
    public function contactoAction(Request $request)
    {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('	pmary@ryma-soluciones.com')
                ->setTo($data['email']);

            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('success', 'Your email has been sent! Thanks!');

            return $this->redirect($this->generateUrl('wedding_contactenos'));
        }

        return $this->render(
            'wedding/contactenos.html.twig',
            array('form' => $form->createView())
        );
    }
}