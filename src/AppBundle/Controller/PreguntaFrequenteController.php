<?php
namespace AppBundle\Controller;

use AppBundle\Entity\InspiracionUserGusta;
use AppBundle\Entity\TendenciaUserGusta;
use AppBundle\Entity\VotoUserGusta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PreguntaFrequenteController extends Controller
{
    /**
     * @Route("/negocio/zona/faq", name="negocio_zona_faq")
     */
    public function zonaComentariosAction(Request $request){
        $proveedor = $this->get('security.token_storage')->getToken()->getUser();
        $preguntas = $this->getDoctrine()->getRepository('AppBundle:PreguntaFrequente')->findBy(
            array('proveedor'=>$proveedor)
        );
        return $this->render(
            'negocio/preguntas_frequentes.html.twig',array(
                'preguntas'=>$preguntas
            )
        );

    }
}

?>