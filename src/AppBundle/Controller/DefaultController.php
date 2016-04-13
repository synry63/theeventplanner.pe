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
            'selection.html.twig'
        );
    }

    public function menu_contentAction($max = 3){



        return $this->render(
            'wedding/proveedores-menu.html.twig',
            array('proveedores' => 'lista')
        );
    }
}
