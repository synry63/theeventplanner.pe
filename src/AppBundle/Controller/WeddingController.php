<?php
namespace AppBundle\Controller;

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
        return $this->render(
            'for_all/home.html.twig',array(
                'wedding'=>$this->generateUrl('wedding_start'),
                'party'=>$this->generateUrl('party_start'),
                'kids'=>$this->generateUrl('kids_start'),
                'dinner'=>$this->generateUrl('dinner_start'),
            )
        );
    }
}