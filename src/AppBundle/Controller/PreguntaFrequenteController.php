<?php
namespace AppBundle\Controller;

use AppBundle\Entity\InspiracionUserGusta;
use AppBundle\Entity\PreguntaFrequente;
use AppBundle\Entity\TendenciaUserGusta;
use AppBundle\Entity\VotoUserGusta;
use AppBundle\Form\Type\PreguntaFrequenteType;
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
        $pregunta = new PreguntaFrequente();
        $form = $this->createForm(new PreguntaFrequenteType(), $pregunta);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $pregunta->setProveedor($proveedor);
            $em = $this->getDoctrine()->getManager();
            $em->persist($pregunta);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Se agrego su pregunta-respuesta !');
            return $this->redirectToRoute('negocio_zona_faq');
        }
        return $this->render(
            'negocio/preguntas_frequentes.html.twig',array(
                'preguntas'=>$preguntas,
                'form'=>$form->createView()
            )
        );
    }
    /**
     * @Route("/negocio/zona/faq/delete/{id}", name="negocio_zona_faq_delete")
     */
    public function zonaComentarioDeleteAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $pregunta = $this->getDoctrine()->getRepository('AppBundle:PreguntaFrequente')->find($id);
        if($pregunta!=null){
            $em->remove($pregunta);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'FAQ suprimida !');
            return $this->redirectToRoute('negocio_zona_faq');
        }
    }
    /**
     * @Route("/negocio/zona/faq/edit/{id}", name="negocio_zona_faq_edit")
     */
    public function zonaComentarioEditAction(Request $request,$id){
        $pregunta = $this->getDoctrine()->getRepository('AppBundle:PreguntaFrequente')->find($id);
        $form = $this->createForm(new PreguntaFrequenteType(), $pregunta);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pregunta);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Se actualizo su pregunta-respuesta !');
            return $this->redirectToRoute('negocio_zona_faq_edit',array('id'=>$id));
        }
        return $this->render(
            'negocio/preguntas_frequentes_edit.html.twig',
            array(
                'form'=>$form->createView()
            )
        );
    }
}

?>