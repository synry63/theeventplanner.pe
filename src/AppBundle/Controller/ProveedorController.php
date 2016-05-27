<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 6:10 PM
 */
namespace AppBundle\Controller;


use AppBundle\Entity\CategoriaListado;
use AppBundle\Entity\ComentarioProveedor;
use AppBundle\Entity\Foto;
use AppBundle\Entity\Logo;
use AppBundle\Entity\Proveedor;
use AppBundle\Entity\UserProveedorGusta;
use AppBundle\Form\Type\ComentarioProveedorType;
use AppBundle\Form\Type\FotoType;
use AppBundle\Form\Type\GoogleMapType;
use AppBundle\Form\Type\LogoType;
use AppBundle\Form\Type\ProveedorChangePasswordType;
use AppBundle\Form\Type\ProveedorChangeLogoType;
use AppBundle\Form\Type\ProveedorProfileType;
use AppBundle\Form\Type\ProveedorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Places\Autocomplete;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Ivory\GoogleMap\Places\AutocompleteType;
use Ivory\GoogleMap\Helper\Places\AutocompleteHelper;


class ProveedorController extends Controller
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
     * @Route("/negocio/zona", name="negocio_zona")
     */
    public function zonaShowAction(Request $request){

        $proveedor = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(
            'negocio/zona-home.html.twig',array(
                'proveedor'=>$proveedor

            )
        );
    }

    /**
     * @Route("/negocio/zona/cambiar-contrasena", name="negocio_zona_password")
     */
    public function zonaPasswordShowAction(Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(new ProveedorChangePasswordType(), $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($proveedor, $proveedor->getPlainPassword());
            $proveedor->setPassword($password);

            // 4) save the Proveedor !
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your password had change !');

            return $this->redirectToRoute('negocio_zona_password');
        }

        return $this->render(
            'negocio/change-password.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/negocio/zona/mapa   ", name="negocio_zona_map")
     */
    public function zonaGoogleMapShowAction(Request $request){

        $form = $this->createForm(new GoogleMapType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            var_dump($data);
        }

        return $this->render(
            'negocio/map.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/negocio/zona/cambiar-perfile", name="negocio_zona_perfil")
     */
    public function zonaPerfilShowAction(Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();

        $in = array();
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


        $form = $this->createForm(new ProveedorProfileType($in), $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 4) save the Proveedor !
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your data had change !');

            return $this->redirectToRoute('negocio_zona_perfil');
        }

        return $this->render(
            'negocio/profile.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/negocio/login", name="login_negocio")
     */
    public function loginAction(Request $request){

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render(
            'negocio/login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/negocio/registrar/validacion", name="register_validacion_negocio")
     */
    public function registerValidAction(Request $request){
        return $this->render(
            'temp.html.twig'
        );
    }
    /**
     * @Route("/negocio/registrar", name="register_negocio_start")
     */
    public function registerAction(Request $request)
    {

        $proveedor = new Proveedor();
        //$categorias = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findAll();

        //$proveedor->test($categoria);
        $in = array();
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

        $map = $this->get('ivory_google_map.map');
        $map->setLanguage($this->get('request')->getLocale());
        $map->setCenter(-12.0552581, -77.080205, true);
        $map->setMapOption('zoom', 3);
        $map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);
        $marker = new Marker();
        // Sets your marker animation
        $marker->setAnimation(Animation::BOUNCE);
        $marker->setAnimation('bounce');
        $marker->setPosition(-12.0552581, -77.080205, true);
        // Add your marker to the map
        $map->addMarker($marker);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //$data = $form->getData();
            //$logo = new Logo();
            //$logo->setLogoFile($temp_file);
            $temp_file = $proveedor->getTempFile();
            $logo = new Logo();
            $logo->setLogoFile($temp_file);
            $logo->setProveedor($proveedor);
            $proveedor->setLogo($logo);

            //$logo = $proveedor->getLogo();
            //$logo->setProveedor($proveedor);



            /*$temp_file = $proveedor->getLogo();
            $logo = new Logo();
            $logo->setLogoFile($temp_file);
            $logo->setLogoName('test');
            $proveedor->setLogo($logo);*/
            //var_dump('here');
            //exit;
            // 3) Encode the password (you could also do this via Doctrine listener)
            $proveedor->setSlug($this->slugify($proveedor->getNombre()));
            $password = $this->get('security.password_encoder')
                ->encodePassword($proveedor, $proveedor->getPlainPassword());
            $proveedor->setPassword($password);

            // 4) save the Proveedor !
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);

            $em->flush();

            return $this->redirectToRoute('register_validacion_negocio');
        }

        return $this->render(
            'negocio.html.twig',array(
                //'commentarios'=>$comments,
                'form' => $form->createView(),
                'map'=>$map
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
        $proveedores_category_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByCategory($categoria);

        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_category);
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $proveedores_category_query,
            $page,
            6
            //array('wrap-queries'=>true)
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
    /*public function proveedoresPagiAction($slug_site,$slug_category,$page)
    {

        $categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_category));
        $main_categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_site));
        $proveedores_category_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByCategory($categoria);

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
    }*/

    /**
     * @Route("/{slug_site}/proveedor/{slug_proveedor}", name="proveedor_detail",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function proveedorShowAction($slug_site,$slug_proveedor,Request $request){

        $user = $this->container->get('security.context')->getToken()->getUser();
        $proveedor = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(array('slug'=>$slug_proveedor));
        $moy = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedorRating($proveedor);
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')->getAllComments($proveedor);


        $renderOut = array(
            'proveedor'=>$proveedor,
            'moy'=>$moy,
            'comentarios'=>$comments
        );

        if(is_object($user)){
            $comentarioProveedor = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')
                ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));
            if($comentarioProveedor==null){
                $comentarioProveedor = new ComentarioProveedor();
                $form = $this->createForm(new ComentarioProveedorType(), $comentarioProveedor);
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
                    return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    //return $this->redirectToRoute('task_success');
                    //$this->redirect($request->getReferer());
                }
                $renderOut['form'] = $form->createView();
            }

            $userGustaProveedor = $this->getDoctrine()->getRepository('AppBundle:UserProveedorGusta')
                ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));

            $renderOut['favorito'] = $userGustaProveedor;

            return $this->render(
                $slug_site.'/proveedores-detail.html.twig',$renderOut
            );
        }
        else{
            return $this->render(
                $slug_site.'/proveedores-detail.html.twig',$renderOut
            );
        }
    }
    /**
     * @Route("/{slug_site}/proveedor/{slug_proveedor}/gusta", name="proveedor_me_gusta",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function updateGustaProveedorUserAction($slug_site,$slug_proveedor,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(is_object($user)){
            $proveedor = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(array('slug'=>$slug_proveedor));
            $userGustaProveedor = $this->getDoctrine()->getRepository('AppBundle:UserProveedorGusta')
                ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));

            $em = $this->getDoctrine()->getManager();
            if($userGustaProveedor==NULL){
                $userGustaProveedor = new UserProveedorGusta();
                $userGustaProveedor->setUser($user);
                $userGustaProveedor->setProveedor($proveedor);
                $em->persist($userGustaProveedor);
                $em->flush();
            }
            else{
                $em->remove($userGustaProveedor);
                $em->flush();
            }
            return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
        }

    }

}