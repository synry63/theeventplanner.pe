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
    /**
     * @Route("/negocio/zona/image-processing", name="processing_negocio_logo")
     */
    public function negocioFotoProfileProcessingShowAction(Request $request){
        $imagePath = $this->get('kernel')->getRootDir() . '/../web/images/proveedores/logo/temps/' . $this->getRequest()->getBasePath();


        $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
        $temp = explode(".", $_FILES["img"]["name"]);
        $extension = end($temp);


        //Check write Access to Directory

        if(!is_writable($imagePath)){
            $response = Array(
                "status" => 'error',
                "message" => 'Can`t upload File; no write Access'
            );
            print json_encode($response);
            return;
        }

        if ( in_array($extension, $allowedExts))
        {
            if ($_FILES["img"]["error"] > 0)
            {
                $response = array(
                    "status" => 'error',
                    "message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
                );
            }
            else
            {

                $filename = $_FILES["img"]["tmp_name"];
                list($width, $height) = getimagesize( $filename );
                $rand = rand();
                move_uploaded_file($filename,  $imagePath . $rand);
                $my_path = '/images/proveedores/logo/temps/';
                $response = array(
                    "status" => 'success',
                    "url" => $my_path.$rand,
                    "width" => $width,
                    "height" => $height
                );

            }
        }
        else
        {
            $response = array(
                "status" => 'error',
                "message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
            );
        }

        print json_encode($response);
        exit;
    }
    /**
     * @Route("/negocio/zona/image-crop", name="crop_negocio_logo")
     */
    public function userFotoProfileCropShowAction(Request $request){
        $imgUrl = $_POST['imgUrl'];
        $pieces = explode("/", $imgUrl);
        $filename = $pieces[count($pieces)-1];
// original sizes
        $imgInitW = $_POST['imgInitW'];
        $imgInitH = $_POST['imgInitH'];
// resized sizes
        $imgW = $_POST['imgW'];
        $imgH = $_POST['imgH'];
// offsets
        $imgY1 = $_POST['imgY1'];
        $imgX1 = $_POST['imgX1'];
// crop box
        $cropW = $_POST['cropW'];
        $cropH = $_POST['cropH'];
// rotation angle
        $angle = $_POST['rotation'];

        $jpeg_quality = 100;
        $path = $this->get('kernel')->getRootDir() . '/../web/images/proveedores/logo/' . $this->getRequest()->getBasePath();
        $filename_out = "neogiciologo_".rand();
        $output_filename = $path.$filename_out;

// uncomment line below to save the cropped image in the same location as the original image.
//$output_filename = dirname($imgUrl). "/croppedImg_".rand();

        $what = getimagesize($path.'temps/'.$filename);
        switch(strtolower($what['mime']))
        {
            case 'image/png':
                //$img_r = imagecreatefrompng($path.'temps/'.$filename);
                $source_image = imagecreatefrompng($path.'temps/'.$filename);
                $type = '.png';
                break;
            case 'image/jpeg':
                //$img_r = imagecreatefromjpeg($path.'temps/'.$filename);
                $source_image = imagecreatefromjpeg($path.'temps/'.$filename);
                error_log("jpg");
                $type = '.jpeg';
                break;
            case 'image/gif':
                //$img_r = imagecreatefromgif($path.'temps/'.$filename);
                $source_image = imagecreatefromgif($path.'temps/'.$filename);
                $type = '.gif';
                break;
            default: die('image type not supported');
        }


//Check write Access to Directory

        if(!is_writable(dirname($output_filename))){
            $response = Array(
                "status" => 'error',
                "message" => 'Can`t write cropped File'
            );
        }else{

            // resize the original image to size of editor
            $resizedImage = imagecreatetruecolor($imgW, $imgH);
            imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
            // rotate the rezized image
            $rotated_image = imagerotate($resizedImage, -$angle, 0);
            // find new width & height of rotated image
            $rotated_width = imagesx($rotated_image);
            $rotated_height = imagesy($rotated_image);
            // diff between rotated & original sizes
            $dx = $rotated_width - $imgW;
            $dy = $rotated_height - $imgH;
            // crop rotated image to fit into original rezized rectangle
            $cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
            imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
            imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
            // crop image into selected area
            $final_image = imagecreatetruecolor($cropW, $cropH);
            imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
            imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
            // finally output png image
            //imagepng($final_image, $output_filename.$type, $png_quality);
            imagejpeg($final_image, $output_filename.$type, $jpeg_quality);
            $my_path = '/images/proveedores/logo/';
            $response = Array(
                "status" => 'success',
                "message" => 'cambio realizado, en unos segundo la pagina se va actualizar para visualizar el cambio',
                "url" => $my_path.$filename_out.$type,
                "targetUrl" => $this->get('router')->generate('negocio_zona_logo')
            );

            // PERSISTANCE
            $proveedor = $this->get('security.token_storage')->getToken()->getUser();
            $logo = $proveedor->getLogo();
            $logo->setLogoName($filename_out.$type);
            $em = $this->getDoctrine()->getManager();
            $em->persist($logo);
            $em->flush();

        }
        print json_encode($response);
        exit;
    }
}
