<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/13/16
 * Time: 9:05 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Inspiracion;
use AppBundle\Entity\Tendencia;
use AppBundle\Form\Type\InspiracionType;
use AppBundle\Form\Type\TendenciaType;
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
    public function adminTendenciasAllAction(Request $request){
        $tendencias = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->findBy(
            array(),
            array('updatedAt' => 'DESC')
        );
        return $this->render(
            'admin/tendencias.html.twig',
            array(
                'tendencias'=>$tendencias
            )
        );
    }
    /**
     * @Route("/admin/tendencias/{slug_site}", name="admin_tendencias_seccion",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function adminTendenciasAction(Request $request,$slug_site){
        $tendencias = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->findBy(
            array('type'=>$slug_site),
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
        $tendencia = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->find($id);

        if($tendencia!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($tendencia);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Your tendencia deleted !');
            return $this->redirectToRoute('admin_tendencias');
        }


    }
    /**
     * @Route("/admin/tendencia/add", name="admin_tendencia_add")
     */
    public function adminTendenciaAddAction(Request $request){
        $tendencia = new Tendencia();
        $form_tendencia = $this->createForm(new TendenciaType(), $tendencia);
        $form_tendencia->handleRequest($request);
        if ($form_tendencia->isSubmitted() && $form_tendencia->isValid()) {
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
                'form_tendencia' => $form_tendencia->createView(),
            )
        );
    }
    /**
     * @Route("/admin/tendencia/{id}", name="admin_tendencia_edit")
     */
    public function adminTendenciaAction(Request $request,$id){
        $tendencia = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->find($id);
        $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(
            array('tendencia'=>$tendencia),
            array('sort' => 'ASC')
        );
        $form_tendencia = $this->createForm(new TendenciaType(), $tendencia);
        $form_tendencia->handleRequest($request);
        if ($form_tendencia->isSubmitted() && $form_tendencia->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $slug = $this->slugify($tendencia->getNombre());
            $tendencia->setSlug($slug);
            $tendencia->setUpdatedAt(new \DateTime('now'));
            $em->persist($tendencia);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your tendencia saved !');
            return $this->redirectToRoute('admin_tendencia_edit',array('id'=>$id));
        }

        $inspiracion = new Inspiracion();
        $form_inspiracion = $this->createForm(new InspiracionType(), $inspiracion);
        $form_inspiracion->handleRequest($request);
        if ($form_inspiracion->isSubmitted() && $form_inspiracion->isValid()) {
            $inspiracion->setTendencia($tendencia);
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($inspiracion);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Your inspiracion added !');
            return $this->redirectToRoute('admin_tendencia_edit',array('id'=>$id));
        }


        return $this->render(
            'admin/tendencia_edit.html.twig',
            array(
                'tendencia'=>$tendencia,
                'inspiraciones'=>$inspiraciones,
                'form_tendencia' => $form_tendencia->createView(),
                'form_inspiracion' => $form_inspiracion->createView()

            )
        );
    }
    /**
     * @Route("/admin/tendencia/{id}/inspiraciones", name="admin_tendencia_inspiraciones")
     */
    public function adminTendenciaInspiracionesAction(Request $request,$id){
        $tendencia = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->find($id);
        $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(
            array('tendencia'=>$tendencia),
            array('sort' => 'ASC')
        );

        return $this->render(
            'admin/tendencia_inspiraciones.html.twig',
            array(
                'inspiraciones' => $inspiraciones
            )
        );
    }
    /**
     * @Route("/admin/tendencia/inspiracion/add", name="admin_tendencia_inpiracion_add")
     */
    public function adminTendenciaInspiracionAddAction(Request $request){
        $inspiracion = new Inspiracion();
        $form = $this->createForm(new InspiracionType(), $inspiracion);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($inspiracion);
            $em->flush();
            $id = $inspiracion->getTendencia()->getId();
            $request->getSession()->getFlashBag()->add('success', 'Your inspiracion added !');
            return $this->redirectToRoute('admin_tendencia_inpiraciones',array('id'=>$id));
        }
        return $this->render(
            'admin/tendencia_add.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
    /**
     * @Route("/admin/tendencia/inspiracion/delete/{id}", name="admin_tendencia_inspiracion_delete")
     */
    public function adminTendenciaInspiracionDeleteAction(Request $request,$id){
        $inspiraion = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->find($id);
        if($inspiraion!=null){
            $id = $inspiraion->getTendencia()->getId();
            $em = $this->getDoctrine()->getManager();
            $em->remove($inspiraion);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Your inspiraion deleted !');
            return $this->redirectToRoute('admin_tendencia_inspiraciones',array('id'=>$id));
        }
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
            6
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
            $p->setIsAccepted(false);
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
            $p->setIsAccepted(true);
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

        $proveedor =  $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($id);
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
        return $this->redirectToRoute('admin_negocio_preview_seccion',array('slug_site'=>$slug_site,'id'=>$id));


    }

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
     * @Route("admin/negocio/preview/{slug_site}/{id}", name="admin_negocio_preview_seccion",requirements={
     *     "slug_site": "wedding|dinner|kids|party",
     *      "id": "\d+"
     * })
     */
    public function proveedorPreviewBySeccionAction($slug_site,$id,Request $request){

        $proveedor =  $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($id);
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
                'admin/previews_admin/preview_wedding.html.twig',
                $renderOut
            );
        }
        else if($slug_site=='dinner'){
            return $this->render(
                'admin/previews_admin/preview_dinner.html.twig',
                $renderOut
            );
        }
        else if($slug_site=='kids'){
            return $this->render(
                'admin/previews_admin/preview_kids.html.twig',
                $renderOut
            );
        }
        else if($slug_site=='party'){
            return $this->render(
                'admin/previews_admin/preview_party.html.twig',
                $renderOut
            );
        }
    }

}