<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\CotizacionType;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

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

        $session = $this->getRequest()->getSession();
        $session->set('site', $slug_site);



        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_site,'ASC');

        $categoria = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>$slug_site));

        $best_proveedores = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getBestProveedores($categoria);

        $best_fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->getBestFotos($categoria);

        $ultimas_noticias = $this->getDoctrine()->getRepository('AppBundle:Noticia')->getNoticiasWithCountComments($slug_site,4);

        $best_inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->getLastInspiraciones($slug_site,8);


        return $this->render(
            $slug_site.'/home.html.twig',array(
                'mejores_proveedores'=>$best_proveedores,
                'mejores_inspiraciones'=>$best_inspiraciones,
                'mejores_fotos'=>$best_fotos,
                'ultimas_noticias'=>$ultimas_noticias,
                'categorias'=>$children_categories,
                'home'=>'home'
            )
        );

    }
    /**
     * @Route("/{slug_site}/proveedores/", name="site_categorias",requirements={
     *     "slug": "wedding|dinner|kids|party"
     * })
     */
    public function categoriasAction($slug_site){
        $children_categories =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_site);
        $children_categories_menu =  $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren($slug_site,'ASC');

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem($slug_site, $this->get("router")->generate("site_start",array('slug_site'=>$slug_site)));
        $breadcrumbs->addItem("Proveedores", $this->get("router")->generate("site_categorias",array('slug_site'=>$slug_site)));

        return $this->render(
            $slug_site.'/categorias.html.twig',array(
                'categorias'=>$children_categories,
                'categorias_menu'=>$children_categories_menu
            )
        );

    }
    /**
     * @Route("/{slug_site}/quienes-somos", name="site_nosotros",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function nosotrosAction($slug_site,Request $request)
    {
        return $this->render(
            $slug_site.'/nosotros.html.twig'
        );
    }
    /**
     * @Route("/{slug_site}/aspectos-legales", name="site_legal",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function legalAction($slug_site,Request $request)
    {
        return $this->render(
            $slug_site.'/legal.html.twig'
        );
    }
    /**
     * @Route("/{slug_site}/contactenos", name="site_contactenos",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function contactoAction($slug_site,Request $request)
    {
        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);

        if ($form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();


            // send email to admin
            $message = \Swift_Message::newInstance();
            $imgUrl = $message->embed(\Swift_Image::fromPath('http://theeventplanner.pe/images/register_logo.png'));
            $message->setSubject('The Event Planner - Formulario Contacto')
                ->setFrom(array('sistema@theeventplanner.pe'=>'The Event Planner'))
                ->setTo('jsarabia@theeventplanner.pe')
                ->setBody(
                    $this->renderView(
                        'emails/contacto_admin.html.twig',
                        array(
                            'nombre' => $data['name'],
                            'email' => $data['email'],
                            'asunto' => $data['subject'],
                            'telefono' => $data['tel'],
                            'mensaje' => $data['message'],
                            'logo'=>$imgUrl
                        )
                    )
                );

            // send email to user as auto responder
            $message_user = \Swift_Message::newInstance();
            $imgUrl_user = $message_user->embed(\Swift_Image::fromPath('http://theeventplanner.pe/images/register_logo.png'));
            $message_user->setSubject('Formulario de Contacto')
                ->setFrom(array('sistema@theeventplanner.pe'=>'The Event Planner'))
                ->setTo($data['email'])
                ->setBody(
                    $this->renderView(
                        'emails/contacto_user.html.twig',
                        array(
                            'nombre' => $data['name'],
                            'email' => $data['email'],
                            'asunto' => $data['subject'],
                            'telefono' => $data['tel'],
                            'mensaje' => $data['message'],
                            'logo'=>$imgUrl_user
                        )
                    )
                );
            $message_user->setContentType("text/html");
            $this->get('mailer')->send($message_user);

            $message->setContentType("text/html");
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add('success', '¡Tu correo ha sido enviado! ¡Gracias!');

            $routeName = $request->get('_route');

            return $this->redirect($this->generateUrl($routeName,array('slug_site' => $slug_site)));
        }

        return $this->render(
            $slug_site.'/contactenos.html.twig',
            array('form' => $form->createView())
        );
    }
    /**
     * @Route("/user-action/{slug_site}/{slug_proveedor}/contizacion", name="cotizacion_negocio",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function contizacionAction($slug_site,$slug_proveedor,Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $proveedor = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->findOneBy(array('slug'=>$slug_proveedor));

            // count cotizar
            if (empty($proveedor->getCountCotizar())) {
                $proveedor->setCountCotizar(1);
            } else {
                $proveedor->setCountCotizar($proveedor->getCountCotizar() + 1);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();
            //end count cotizar

            $form = $this->createForm(new CotizacionType(),NULL,array(
                'action' => $this->generateUrl('cotizacion_negocio',array('slug_site' => $slug_site,'slug_proveedor'=>$slug_proveedor)),
                'method' => 'POST',
            ));
            $form->handleRequest($request);

            if ($form->isSubmitted()) {

               if($form->isValid()){

                   $url = $this->get('router')->generate('proveedor_detail',array(
                       'slug_site' => $slug_site,
                       'slug_proveedor' => $slug_proveedor,
                    ));

                   $data = $form->getData();

                   // send email to negocio
                   $message = \Swift_Message::newInstance();
                   $imgUrl = $message->embed(\Swift_Image::fromPath('http://theeventplanner.pe/images/register_logo.png'));
                   $message->setSubject('The Event Planner - Formulario Cotizacion')
                       ->setFrom(array('sistema@theeventplanner.pe'=>'The Event Planner'))
                       ->setTo($proveedor->getEmail())
                       ->setBody(
                           $this->renderView(
                               'emails/cotizacion_negocio.html.twig',
                               array(
                                   'nombre' => $data['name'],
                                   'email' => $data['email'],
                                   'asunto' => $data['subject'],
                                   'proveedor'=>$proveedor,
                                   'telefono' => $data['tel'],
                                   'mensaje' => $data['message'],
                                   'logo'=>$imgUrl
                               )
                           )
                       );

                   // send email to user as auto responder
                   $message_user = \Swift_Message::newInstance();
                   $imgUrl_user = $message_user->embed(\Swift_Image::fromPath('http://theeventplanner.pe/images/register_logo.png'));
                   $message_user->setSubject('Formulario Cotizacion')
                       ->setFrom(array('sistema@theeventplanner.pe'=>'The Event Planner'))
                       ->setTo($data['email'])
                       ->setBody(
                           $this->renderView(
                               'emails/cotizacion_user.html.twig',
                               array(
                                   'nombre' => $data['name'],
                                   'email' => $data['email'],
                                   'asunto' => $data['subject'],
                                   'proveedor'=>$proveedor,
                                   'telefono' => $data['tel'],
                                   'mensaje' => $data['message'],
                                   'logo'=>$imgUrl_user
                               )
                           )
                       );
                   $message_user->setContentType("text/html");
                   $this->get('mailer')->send($message_user);
                   $message->setContentType("text/html");
                   $this->get('mailer')->send($message);

                   $this->get('swiftmailer.command.spool_send')->run(new ArgvInput(array()), new ConsoleOutput());

                   $custom_response = new \stdClass();
                   $custom_response->success = true;
                   $custom_response->message = 'Tu solicitud de cotizacion fue enviada correctamente';
                   $custom_response->targetUrl = $url;
                   echo json_encode($custom_response);
                   exit;
               }
               else{
                   $custom_response = new \stdClass();
                   $custom_response->success = false;
                   $custom_response->message = 'El formulario contiene errores, por favor llene todo los campos requeridos';
                   echo json_encode($custom_response);
                   exit;
               }

            }
            return $this->render(
                $slug_site.'/cotizacion.html.twig',
                array('form' => $form->createView())
            );
        }


    }
}
