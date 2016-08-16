<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;
use AppBundle\Form\Type\CotizacionType;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;


class BlogController extends Controller
{
    /**
     * @Route("/{slug_site}/noticias", name="noticias_start",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function indexAction($slug_site,Request $request)
    {
        return $this->render(
            $slug_site.'/noticias.html.twig'
        );
    }
}
