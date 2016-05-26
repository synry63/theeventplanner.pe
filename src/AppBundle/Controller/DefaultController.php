<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;

/**
 *
 * @Route("/")
 *
 */

class DefaultController extends Controller
{
    /**
     * @Route("/", name="selection_start")
     */
    public function indexAction(Request $request)
    {

        return $this->render(
            'selection.html.twig'
        );
    }
    /**
     * @Route("/{slug_site}/", name="site_start",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function siteAction($slug_site,Request $request)
    {


        $categoria_wedding = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_site));

        $best_proveedores = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getBestProveedores($categoria_wedding);

        $best_fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->getBestFotos($categoria_wedding);

        $best_inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->getBestInspiraciones();


        return $this->render(
            $slug_site.'/home.html.twig',array(
                'mejores_proveedores'=>$best_proveedores,
                'mejores_inspiraciones'=>$best_inspiraciones,
                'mejores_fotos'=>$best_fotos,
            )
        );

    }
    /**
     * @Route("/{slug_site}/categorias/", name="site_categorias",requirements={
     *     "slug": "wedding|dinner|kids|party"
     * })
     */
    public function categoriasAction($slug_site){
        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_site);
        return $this->render(
            $slug_site.'/categorias.html.twig',array(
                'categorias'=>$children_categories
            )
        );

    }

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
     * @Route("/profile/fotos-favoritas", name="show_user_favoritos_fotos")
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
     * @Route("/{slug_site}/contactenos", name="site_contactenos",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function contactoAction($slug_site,Request $request)
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

            $routeName = $request->get('_route');

            return $this->redirect($this->generateUrl($routeName,array('slug_site' => $slug_site)));
        }

        return $this->render(
            $slug_site.'/contactenos.html.twig',
            array('form' => $form->createView())
        );
    }

}
