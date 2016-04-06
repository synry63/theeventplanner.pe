<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
            'for_all/selection.html.twig',array(
                'wedding'=>$this->generateUrl('wedding_start'),
                'party'=>$this->generateUrl('party_start'),
                'kids'=>$this->generateUrl('kids_start'),
                'dinner'=>$this->generateUrl('dinner_start'),
            )
        );
    }
    /**
     * @Route("/dinner/contactenos", name="dinner_contactenos_start")
     * @Route("/wedding/contactenos", name="wedding_contactenos_start")
     */
    public function contactoAction(Request $request)
    {

        return $this->render(
            'for_all/home.html.twig',array(
                'wedding'=>$this->generateUrl('wedding_start'),
                'party'=>$this->generateUrl('party_start'),
                'kids'=>$this->generateUrl('kids_start'),
                'dinner'=>$this->generateUrl('dinner_start'),
            )
        );
    }
    public function menu_contentAction($max){



        return $this->render(
            'for_all/proveedores-menu.html.twig',
            array('proveedores' => 'lista')
        );
    }
}
