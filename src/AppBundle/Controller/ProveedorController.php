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
use AppBundle\Entity\PreguntaFrequente;
use AppBundle\Entity\Proveedor;
use AppBundle\Entity\RespuestaProveedor;
use AppBundle\Entity\UserProveedorGusta;
use AppBundle\Form\Type\ComentarioProveedorType;
use AppBundle\Form\Type\ComentarioRespuestaType;
use AppBundle\Form\Type\FotoType;
use AppBundle\Form\Type\GoogleMapType;
use AppBundle\Form\Type\LogoType;
use AppBundle\Form\Type\ProveedorChangePasswordType;
use AppBundle\Form\Type\ProveedorChangeLogoType;
use AppBundle\Form\Type\ProveedorProfileType;
use AppBundle\Form\Type\ProveedorType;
use AppBundle\Form\Type\RespuestaProveedorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Places\Autocomplete;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Ivory\GoogleMap\Places\AutocompleteType;
use Ivory\GoogleMap\Helper\Places\AutocompleteHelper;
use Ivory\GoogleMap\Overlays\InfoWindow;
use Ivory\GoogleMap\Events\MouseEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


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
        $moy = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedorRating($proveedor);
        $total = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedorCount($proveedor);
        $best_fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->getBestFotosProveedor($proveedor);
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')->getAllComments($proveedor,5);
        return $this->render(
            'negocio/zona-home.html.twig',array(
            //   'negocio/temp.html.twig',array(
                'proveedor'=>$proveedor,
                'moy'=>$moy,
                'favorito_total_usuarios'=>$total,
                'mejores_fotos'=>$best_fotos,
                'comentarios'=>$comments

            )
        );
    }
    /**
     * @Route("/negocio/zona/comentarios", name="negocio_zona_comentarios")
     */
    public function zonaComentariosAction(Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        $comments = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')->getAllComments($proveedor);
        return $this->render(
            'negocio/comentarios.html.twig',array(
                'comentarios'=>$comments
            )
        );

    }
    /**
     * @Route("/negocio/zona/comentario/{id}", name="negocio_zona_comentario"),requirements={
     *     "id": "\d+",
     * })
     */
    public function zonaComentarioAction($id,Request $request){
        $comment =$this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')->find($id);
        $respuesta = $comment->getRespuesta();
        if($respuesta==null){
            $respuesta = new RespuestaProveedor();
            $respuesta->setComentarioProveedor($comment);
        }
        $form = $this->createForm(new RespuestaProveedorType(),$respuesta );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 4) save the respuesta !
            $respuesta->setAdedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($respuesta);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Respuesta actualizada correctamente !');
        }
        return $this->render(
            'negocio/comentario.html.twig',
            array('form' => $form->createView(),
                  'comentario'=>$comment

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

            $request->getSession()->getFlashBag()->add('success', 'Contraseña actualizada !');

            return $this->redirectToRoute('negocio_zona_password');
        }

        return $this->render(
            'negocio/change-password.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/negocio/zona/mapa", name="negocio_zona_map")
     */
    public function zonaGoogleMapShowAction(Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(new GoogleMapType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ubicacion = json_decode($data['ubicacion']);
            $proveedor->setGoogleMapLat($ubicacion->lat);
            $proveedor->setGoogleMapLng($ubicacion->lng);
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Mapa actualizado !');

            return $this->redirectToRoute('negocio_zona_map');
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

            $request->getSession()->getFlashBag()->add('success', 'Datos actualizados !');

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
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        if(is_object($proveedor)){

            // send email to negocio
            $message = \Swift_Message::newInstance();
            $imgUrl = $message->embed(\Swift_Image::fromPath('http://theeventplanner.pe/images/register_logo.png'));
            $message->setSubject('Bienvenido a The Event Planner - Business')
                ->setFrom(array('sistema@theeventplanner.pe'=>'The Event Planner'))
                ->setTo($proveedor->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/negocio_register_user.html.twig',
                        array(
                            'usuario' => $proveedor->getUsername(),
                            'nombre_negocio' => $proveedor->getNombre(),
                            'telefono' => $proveedor->getTelefono(),
                            'email' => $proveedor->getEmail(),
                            'logo'=>$imgUrl
                        )
                    )
                );

            $message->setContentType("text/html");
            $this->get('mailer')->send($message);

            // send email to admin
            $message_admin = \Swift_Message::newInstance();
            $imgUrl_admin = $message_admin->embed(\Swift_Image::fromPath('http://theeventplanner.pe/images/register_logo.png'));
            $message_admin->setSubject('The Event Planner - Business - Nuevo Usuario')
                ->setFrom(array('sistema@theeventplanner.pe'=>'The Event Planner'))
                ->setTo('jsarabia@theeventplanner.pe')
                ->setBody(
                    $this->renderView(
                        'emails/negocio_register_admin.html.twig',
                        array(
                            'usuario' => $proveedor->getUsername(),
                            'nombre_negocio' => $proveedor->getNombre(),
                            'telefono' => $proveedor->getTelefono(),
                            'email' => $proveedor->getEmail(),
                            'logo'=>$imgUrl_admin
                        )
                    )
                );
            $message->setContentType("text/html");
            $this->get('mailer')->send($message);

            $message_admin->setContentType("text/html");
            $this->get('mailer')->send($message_admin);

            return $this->render(
                'negocio_confirmation.html.twig'
            );
        }
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

        /*
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
        */

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


            //create default preguntas
            $pf1 = new PreguntaFrequente();
            $pf1->setPregunta('Que capacidad tienen ? / Que cantidad pueden hacer ?');
            $pf1->setProveedor($proveedor);
            $proveedor->addPreguntas($pf1);

            $pf2 = new PreguntaFrequente();
            $pf2->setPregunta('Rango de precios');
            $pf2->setProveedor($proveedor);
            $proveedor->addPreguntas($pf2);

            $pf3 = new PreguntaFrequente();
            $pf3->setPregunta('Con que servicios cuentan ?');
            $pf3->setProveedor($proveedor);
            $proveedor->addPreguntas($pf3);

            $pf4 = new PreguntaFrequente();
            $pf4->setPregunta('Realizan mas de un evento al dia ?');
            $pf4->setProveedor($proveedor);
            $proveedor->addPreguntas($pf4);

            $pf5 = new PreguntaFrequente();
            $pf5->setPregunta('como se efectua el pago ?');
            $pf5->setProveedor($proveedor);
            $proveedor->addPreguntas($pf5);

            $pf6 = new PreguntaFrequente();
            $pf6->setPregunta('cuales son sus politicas de cancelacion ?');
            $pf6->setProveedor($proveedor);
            $proveedor->addPreguntas($pf6);

            $pf7 = new PreguntaFrequente();
            $pf7->setPregunta('con cuanta anticipación hay que ponerse en contacto con ustedes ?');
            $pf7->setProveedor($proveedor);
            $proveedor->addPreguntas($pf7);

            $pf8 = new PreguntaFrequente();
            $pf8->setPregunta('hay pedido minimo ? Cuanto ?');
            $pf8->setProveedor($proveedor);
            $proveedor->addPreguntas($pf8);

            $em->persist($proveedor);
            $em->flush();

            // authenticate your user right now
            $this->authenticateUser($proveedor);

            return $this->redirectToRoute('register_validacion_negocio');
        }

        return $this->render(
            'negocio.html.twig',array(
                //'commentarios'=>$comments,
                'form' => $form->createView(),
                //'map'=>$map
            )
        );
    }

    private function authenticateUser($user)
    {
        $providerKey = 'main_proveedor'; // your firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
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
        //$main_categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_site));
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

    private function initGoogleMap($proveedor,$slug_site){
        $map = $this->get('ivory_google_map.map');
        $map->setLanguage($this->get('request')->getLocale());
        $map->setCenter($proveedor->getGoogleMapLat(), $proveedor->getGoogleMapLng(), true);
        $map->setAsync(true);
        // Sets the zoom
        $map->setAutoZoom(false);
        $map->setMapOption('zoom', 16);
        //$map->setBound(-2.1, -3.9, 2.6, 1.4, true, true);
        $map->setStylesheetOption('width', '100%');
        $map->setStylesheetOption('height', '300px');
        $marker = new Marker();
        $marker->setIcon('http://theeventplanner.pe/images/markers/'.$slug_site.'_marker.png');
        // Sets your marker animation
        //$marker->setAnimation(Animation::DROP);

        //$marker->setAnimation('bounce');
        $marker->setPosition($proveedor->getGoogleMapLat(), $proveedor->getGoogleMapLng(), true);
        // Add your marker to the map
        $map->addMarker($marker);

// Configure your info window options

        $infoWindow = new InfoWindow();
        $infoWindow->setPrefixJavascriptVariable('info_window_');
        $infoWindow->setPosition(0, 0, true);
        $infoWindow->setPixelOffset(1.1, 2.1, 'px', 'pt');
        $text = $proveedor->getDireccion().'<br/>';
        $infoWindow->setContent('<p class="info-window">'
            .$text.'</p>');
        $infoWindow->setOpen(false);
        $infoWindow->setAutoOpen(true);
        $infoWindow->setOpenEvent(MouseEvent::CLICK);
        $infoWindow->setAutoClose(false);
        $infoWindow->setOption('disableAutoPan', true);
        $infoWindow->setOption('zIndex', 10);
        $infoWindow->setOptions(array(
            'disableAutoPan' => true,
            'zIndex'         => 10,
        ));

        $marker->setInfoWindow($infoWindow);

        return $map;
    }

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
            'comentarios'=>$comments,
        );

        if($proveedor->getGoogleMapLat()!=NULL && $proveedor->getGoogleMapLng()!=NULL){
            $map = $this->initGoogleMap($proveedor,$slug_site);
            $renderOut['map'] = $map;
        }
        if($moy==NULL) $moy = 0;

        if(is_object($user)){
            /*$comentarioProveedor = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')
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

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    //return $this->redirectToRoute('task_success');
                    //$this->redirect($request->getReferer());
                }
                $renderOut['form'] = $form->createView();
            }*/

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
     * @Route("/negocio/zona/preview/{slug_site}", name="negocio_zona_preview_seccion",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function proveedorPreviewBySeccionAction($slug_site,Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        $renderOut = array();
        $renderOut['proveedor'] = $proveedor;

        $seccions_site = array('wedding'=>false,'dinner'=>false,'kids'=>false,'party'=>false);
        foreach ($proveedor->getCategoriasListado() as $c){
            if($c->getParent()->getSlug() == 'wedding'){
                $seccions_site['wedding'] = true;
            }
            if($c->getParent()->getSlug() == 'kids'){
                $seccions_site['kids'] = true;
            }
            if($c->getParent()->getSlug() == 'party'){
                $seccions_site['party'] = true;
            }
            if($c->getParent()->getSlug() == 'dinner'){
                $seccions_site['dinner'] = true;
            }
        }
        $renderOut['seccions'] = $seccions_site;

        if($proveedor->getGoogleMapLat()!=NULL && $proveedor->getGoogleMapLng()!=NULL){
            $map = $this->initGoogleMap($proveedor,$slug_site);
            $renderOut['map'] = $map;
        }

        if($slug_site=='wedding'){
            return $this->render(
                'negocio/previews/preview_wedding.html.twig',
                $renderOut
            );
        }
        else if($slug_site=='dinner'){
            return $this->render(
                'negocio/previews/preview_dinner.html.twig',
                $renderOut
            );
        }
        else if($slug_site=='kids'){
            return $this->render(
                'negocio/previews/preview_kids.html.twig',
                $renderOut
            );
        }
        else if($slug_site=='party'){
            return $this->render(
                'negocio/previews/preview_party.html.twig',
                $renderOut
            );
        }
    }
    /**
     * @Route("/negocio/zona/preview", name="negocio_zona_preview")
     */
    public function proveedorPreviewAction(Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        $seccions_site = array('wedding'=>false,'dinner'=>false,'kids'=>false,'party'=>false);
        foreach ($proveedor->getCategoriasListado() as $c){
            if($c->getParent()->getSlug() == 'wedding'){
                $seccions_site['wedding'] = true;
            }
            if($c->getParent()->getSlug() == 'kids'){
                $seccions_site['kids'] = true;
            }
            if($c->getParent()->getSlug() == 'party'){
                $seccions_site['party'] = true;
            }
            if($c->getParent()->getSlug() == 'dinner'){
                $seccions_site['dinner'] = true;
            }
        }
        //get first
        $slug_site = '';
        foreach ($seccions_site as $key=>$value){
            if($value==true){
                $slug_site = $key;
                break;
            }
        }
        // end get first

        return $this->redirectToRoute('negocio_zona_preview_seccion',array('slug_site'=>$slug_site));


    }
    /**
     * @Route("/user-action/{slug_site}/proveedor/{slug_proveedor}/comentar", name="proveedor_me_comentar",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function comentarProveedorUserAction($slug_site,$slug_proveedor,Request $request){
        $user = $this->container->get('security.context')->getToken()->getUser();
        $proveedor = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(array('slug'=>$slug_proveedor));
        if(is_object($user)){
            //$comentarioProveedor = $this->getDoctrine()->getRepository('AppBundle:ComentarioProveedor')
               // ->findOneBy(array('proveedor'=>$proveedor,'user'=>$user));
                $comentarioProveedor = new ComentarioProveedor();

                $form = $this->createForm(new ComentarioProveedorType(),$comentarioProveedor,array(
                    'action' => $this->generateUrl('proveedor_me_comentar',array('slug_site' => $slug_site,'slug_proveedor'=>$slug_proveedor)),
                    'method' => 'POST',
                ));
                //$form = $this->createForm(new ComentarioProveedorType(), $comentarioProveedor);
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

                    $request->getSession()->getFlashBag()->add('success', 'Gracias por tu comentario !');
                    return $this->redirectToRoute('proveedor_detail',array('slug_site'=>$slug_site,'slug_proveedor'=>$slug_proveedor));
                    //return $this->redirectToRoute('task_success');
                    //$this->redirect($request->getReferer());
                }
                //$renderOut['form'] = $form->createView();
            return $this->render(
                $slug_site.'/comentario_add.html.twig',array(
                    'form'=>$form->createView()
                )
            );
        }
    }
    /**
     * @Route("/user-action/{slug_site}/proveedor/{slug_proveedor}/gusta", name="proveedor_me_gusta",requirements={
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