<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/12/16
 * Time: 9:13 AM
 */
namespace AppBundle\Controller;


use AppBundle\Entity\CategoriaListado;
use AppBundle\Entity\Foto;
use AppBundle\Entity\Logo;
use AppBundle\Entity\Proveedor;
use AppBundle\Form\Type\FotoType;
use AppBundle\Form\Type\LogoType;
use AppBundle\Form\Type\ProveedorChangePasswordType;
use AppBundle\Form\Type\ProveedorChangeLogoType;
use AppBundle\Form\Type\ProveedorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class LogoController extends Controller
{
    /**
     * @Route("/negocio/zona/cambiar-logo", name="negocio_zona_logo")
     */
    public function zonaLogoShowAction(Request $request){

        $proveedor = $this->get('security.token_storage')->getToken()->getUser();

        $logo = $proveedor->getLogo();

        $form = $this->createForm(new ProveedorChangeLogoType(), $logo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($logo);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Your logo had change !');
            return $this->redirectToRoute('negocio_zona_logo');
        }

        return $this->render(
            'negocio/change-logo.html.twig',
            array(
                'logo' => $logo,
                'form' => $form->createView())
        );
    }
}
