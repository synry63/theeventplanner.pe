<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/13/16
 * Time: 9:05 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Inspiracion;
use AppBundle\Entity\Musica;
use AppBundle\Entity\Noticia;
use AppBundle\Entity\Tendencia;
use AppBundle\Entity\Video;
use AppBundle\Entity\Voto;
use AppBundle\Form\Type\InspiracionType;
use AppBundle\Form\Type\MusicaType;
use AppBundle\Form\Type\NoticiaType;
use AppBundle\Form\Type\ProveedorAdminProfileType;
use AppBundle\Form\Type\TendenciaType;
use AppBundle\Form\Type\VideoType;
use AppBundle\Form\Type\VotoType;
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
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;



class AdminController extends Controller
{



    private function menuSelected($key){
        $default = array('home'=>false,'negocios'=>false,'tendencias'=>false,'votos'=>false,'videos'=>false,'musicas'=>false,'noticias'=>false);
        $default[$key] = true;
        return $default;
    }
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
            'admin/home.html.twig',
            array('seccion'=>$this->menuSelected('home'))
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
                'tendencias'=>$tendencias,
                'seccion' => $this->menuSelected('tendencias')
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
                'tendencias'=>$tendencias,
                'seccion' => $this->menuSelected('tendencias')
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
            $request->getSession()->getFlashBag()->add('success', 'Su tendencia fue borrada !');
            return $this->redirectToRoute('admin_tendencias');
        }


    }
    /**
     * @Route("/admin/voto/delete/{id}", name="admin_voto_delete")
     */
    public function adminVotoDeleteAction(Request $request,$id){
        $voto = $this->getDoctrine()->getRepository('AppBundle:Voto')->find($id);

        if($voto!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($voto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su voto fue borrada !');
            return $this->redirectToRoute('admin_votos');
        }
    }

    /**
     * @Route("/admin/voto/add", name="admin_voto_add")
     */
    public function adminVotoAddAction(Request $request){
        $voto = new Voto();
        $form = $this->createForm(new VotoType(), $voto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $slug = $this->slugify($voto->getNombre());
            $voto->setSlug($slug);
            //$logo->setProveedor($proveedor);
            $em->persist($voto);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Su voto fue guardada !');
            return $this->redirectToRoute('admin_votos');
        }

        return $this->render(
            'admin/votos_add.html.twig',
            array(
                'form' => $form->createView(),
                'seccion' => $this->menuSelected('votos')
            )
        );
    }
    /**
     * @Route("/admin/musica/edit/{id}", name="admin_musica_edit")
     */
    public function adminMusicaEditAction(Request $request,$id){
        $musica = $this->getDoctrine()->getRepository('AppBundle:Musica')->find($id);
        $form_musica = $this->createForm(new MusicaType(), $musica);
        $form_musica->handleRequest($request);
        if ($form_musica->isSubmitted() && $form_musica->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($musica);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su musica fue guardada !');
            return $this->redirectToRoute('admin_musica_edit',array('id'=>$id));
        }
        return $this->render(
            'admin/musica_edit.html.twig',
            array(
                'form' => $form_musica->createView(),
                'seccion' => $this->menuSelected('musica')
            )
        );
    }
    /**
     * @Route("/admin/video/edit/{id}", name="admin_video_edit")
     */
    public function adminVideoEditAction(Request $request,$id){
        $video = $this->getDoctrine()->getRepository('AppBundle:Video')->find($id);
        $form_video = $this->createForm(new VideoType(), $video);
        $form_video->handleRequest($request);
        if ($form_video->isSubmitted() && $form_video->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su video fue guardada !');
            return $this->redirectToRoute('admin_video_edit',array('id'=>$id));
        }
        return $this->render(
            'admin/video_edit.html.twig',
            array(
                'form' => $form_video->createView(),
                'seccion' => $this->menuSelected('videos')
            )
        );
    }
    /**
     * @Route("/admin/video/delete/{id}", name="admin_video_delete")
     */
    public function adminVideoDeleteAction(Request $request,$id){
        $video = $this->getDoctrine()->getRepository('AppBundle:Video')->find($id);
        if($video!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($video);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su video fue borrada !');
            return $this->redirectToRoute('admin_videos');
        }
    }

    /**
     * @Route("/admin/video/add", name="admin_video_add")
     */
    public function adminVideoAddAction(Request $request){
        $video = new Video();
        $form_video = $this->createForm(new VideoType(), $video);
        $form_video->handleRequest($request);
        if ($form_video->isSubmitted() && $form_video->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Su video fue guardado !');
            return $this->redirectToRoute('admin_videos');
        }
        return $this->render(
            'admin/video_add.html.twig',
            array(
                'form' => $form_video->createView(),
                'seccion' => $this->menuSelected('videos')
            )
        );
    }
    /**
     * @Route("/admin/musica/add", name="admin_musica_add")
     */
    public function adminMusicaAddAction(Request $request){
        $musica = new Musica();
        $form_musica = $this->createForm(new MusicaType(), $musica);
        $form_musica->handleRequest($request);
        if ($form_musica->isSubmitted() && $form_musica->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($musica);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su musica fue guardado !');
            return $this->redirectToRoute('admin_musicas');
        }
        return $this->render(
            'admin/musica_add.html.twig',
            array(
                'form' => $form_musica->createView(),
                'seccion' => $this->menuSelected('videos')
            )
        );
    }
    /**
     * @Route("/admin/musica/delete/{id}", name="admin_musica_delete")
     */
    public function adminMusicaDeleteAction(Request $request,$id){
        $musica = $this->getDoctrine()->getRepository('AppBundle:Musica')->find($id);
        if($musica!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($musica);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su musica fue borrada !');
            return $this->redirectToRoute('admin_musicas');
        }
    }

    /**
     * @Route("/admin/videos/{slug_site}", name="admin_videos_seccion",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function adminVideosSeccionAction(Request $request,$slug_site){
        $videos = $this->getDoctrine()->getRepository('AppBundle:Video')->findBy(
            array('type'=>$slug_site),
            array('sort' => 'ASC')
        );
        return $this->render(
            'admin/videos.html.twig',
            array(
                'videos'=>$videos,
                'seccion' => $this->menuSelected('videos')
            )
        );
    }
    /**
     * @Route("/admin/videos", name="admin_videos")
     */
    public function adminVideosAction(Request $request){
        $videos = $this->getDoctrine()->getRepository('AppBundle:Video')->findBy(
            array(),
            array('sort' => 'ASC')
        );
        return $this->render(
            'admin/videos.html.twig',
            array(
                'videos'=>$videos,
                'seccion' => $this->menuSelected('videos')
            )
        );

    }
    /**
     * @Route("/admin/musicas", name="admin_musicas")
     */
    public function adminMusicasAction(Request $request){
        $musicas = $this->getDoctrine()->getRepository('AppBundle:Musica')->findBy(
            array(),
            array('sort' => 'ASC')
        );
        return $this->render(
            'admin/musicas.html.twig',
            array(
                'musicas'=>$musicas,
                'seccion' => $this->menuSelected('musicas')
            )
        );

    }
    /**
     * @Route("/admin/musicas/{slug_site}", name="admin_musicas_seccion",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function adminMusicasSeccionAction(Request $request,$slug_site){
        $musicas = $this->getDoctrine()->getRepository('AppBundle:Musica')->findBy(
            array('type'=>$slug_site),
            array('sort' => 'ASC')
        );
        return $this->render(
            'admin/musicas.html.twig',
            array(
                'musicas'=>$musicas,
                'seccion' => $this->menuSelected('musicas')
            )
        );
    }

    /**
     * @Route("/admin/noticias", name="admin_noticias")
     */
    public function adminNoticiasAction(Request $request){
        $noticias = $this->getDoctrine()->getRepository('AppBundle:Noticia')->findBy(
            array(),
            array('adedAt' => 'DESC')
        );
        return $this->render(
            'admin/noticias.html.twig',
            array(
                'noticias'=>$noticias,
                'seccion' => $this->menuSelected('noticias')
            )
        );
    }
    /**
     * @Route("/admin/noticias/{slug_site}", name="admin_noticias_seccion",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function adminNoticiasSeccionAction(Request $request,$slug_site){
        $noticias = $this->getDoctrine()->getRepository('AppBundle:Noticia')->findBy(
            array('type'=>$slug_site),
            array('adedAt' => 'DESC')
        );
        return $this->render(
            'admin/noticias.html.twig',
            array(
                'noticias'=>$noticias,
                'seccion' => $this->menuSelected('noticias')
            )
        );
    }
    /**
     * @Route("/admin/noticia/add", name="admin_noticia_add")
     */
    public function adminNoticiaAddAction(Request $request){
        $noticia = new Noticia();
        $form_noticia = $this->createForm(new NoticiaType(), $noticia);
        $form_noticia->handleRequest($request);
        if ($form_noticia->isSubmitted() && $form_noticia->isValid()) {
            $slug = $this->slugify($noticia->getNombre());
            $noticia->setSlug($slug);

            foreach ($noticia->getParafos() as $p) {
                $p->setNoticia($noticia);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($noticia);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su noticia fue guardado !');
            return $this->redirectToRoute('admin_noticias');
        }
        return $this->render(
            'admin/noticia_add.html.twig',
            array(
                'form' => $form_noticia->createView(),
                'seccion' => $this->menuSelected('noticias')
            )
        );
    }
    /**
     * @Route("/admin/noticia/{id}", name="admin_noticia_edit")
     */
    public function adminNoticiaEditAction(Request $request,$id){

        $noticia = $this->getDoctrine()->getRepository('AppBundle:Noticia')->find($id);

        $originalParafos = new ArrayCollection();
        foreach ($noticia->getParafos() as $p) {
            $originalParafos->add($p);
        }

        $form_noticia = $this->createForm(new NoticiaType(), $noticia);
        $form_noticia->handleRequest($request);
        if ($form_noticia->isSubmitted() && $form_noticia->isValid()) {
            $slug = $this->slugify($noticia->getNombre());
            $noticia->setSlug($slug);
            $em = $this->getDoctrine()->getManager();
            foreach ($originalParafos as $p) {
                if (false === $noticia->getParafos()->contains($p)) {
                    $em->remove($p);
                    //$s->setTendencia(null);
                }
            }
            foreach ($noticia->getParafos() as $p) {
                $p->setNoticia($noticia);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($noticia);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su noticia fue guardado !');
            return $this->redirectToRoute('admin_noticia_edit',array('id'=>$id));
        }
        return $this->render(
            'admin/noticia_edit.html.twig',
            array(
                'form' => $form_noticia->createView(),
                'noticia'=>$noticia,
                'seccion' => $this->menuSelected('noticias')
            )
        );
    }
    /**
     * @Route("/admin/noticia/delete/{id}", name="admin_noticia_delete")
     */
    public function adminNoticiaDeleteAction(Request $request,$id){
        $noticia = $this->getDoctrine()->getRepository('AppBundle:Noticia')->find($id);
        if($noticia!=null){
            $em = $this->getDoctrine()->getManager();
            $em->remove($noticia);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su noticia fue borrada !');
            return $this->redirectToRoute('admin_noticias');
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

            $request->getSession()->getFlashBag()->add('success', 'Su tendencia fue guardada !');
            return $this->redirectToRoute('admin_tendencias');
        }

        return $this->render(
            'admin/tendencia_add.html.twig',
            array(
                'form_tendencia' => $form_tendencia->createView(),
                'seccion' => $this->menuSelected('tendencias')
            )
        );
    }
    /**
     * @Route("/admin/voto/{id}", name="admin_voto_edit")
     */
    public function adminVotoEditAction(Request $request,$id){
        $voto = $this->getDoctrine()->getRepository('AppBundle:Voto')->find($id);

        $form_voto = $this->createForm(new VotoType(), $voto);
        $form_voto->handleRequest($request);
        if ($form_voto->isSubmitted() && $form_voto->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $slug = $this->slugify($voto->getNombre());
            $voto->setSlug($slug);
            $voto->setUpdatedAt(new \DateTime('now'));
            $em->persist($voto);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su voto fue guardada !');
            return $this->redirectToRoute('admin_voto_edit',array('id'=>$id));
        }
        return $this->render(
            'admin/votos_edit.html.twig',
            array(
                'form' => $form_voto->createView(),
                'seccion' => $this->menuSelected('votos')
            )
        );
    }
    /**
     * @Route("/admin/inspiracion/{id}", name="admin_inspiracion_edit")
     */
    public function adminInspiracionEditAction(Request $request,$id){
        $inspiracion = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->find($id);
        $form_inspiracion = $this->createForm(new InspiracionType(), $inspiracion);
        $form_inspiracion->handleRequest($request);
        if ($form_inspiracion->isSubmitted() && $form_inspiracion->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$logo->setProveedor($proveedor);
            $em->persist($inspiracion);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Su inspiracion fue actualizada !');
            return $this->redirectToRoute('admin_inspiracion_edit',array('id'=>$id));
        }
        return $this->render(
            'admin/tendencia_inspiracion_edit.html.twig',
            array(
                'form' => $form_inspiracion->createView(),
                'inspiracion'=>$inspiracion,
                'seccion' => $this->menuSelected('tendencias')

            )
        );
    }
    /**
     * @Route("/admin/tendencia/{id}", name="admin_tendencia_edit")
     */
    public function adminTendenciaEditAction(Request $request,$id){
        $tendencia = $this->getDoctrine()->getRepository('AppBundle:Tendencia')->find($id);
        $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(
            array('tendencia'=>$tendencia),
            array('sort' => 'ASC')
        );
        $originalSource = new ArrayCollection();
        foreach ($tendencia->getSources() as $s) {
            $originalSource->add($s);
        }
        $form_tendencia = $this->createForm(new TendenciaType(), $tendencia);
        $form_tendencia->handleRequest($request);
        if ($form_tendencia->isSubmitted() && $form_tendencia->isValid()) {
            //$data = $form_tendencia->getData();
            $em = $this->getDoctrine()->getManager();
            foreach ($originalSource as $src) {
                if (false === $tendencia->getSources()->contains($src)) {
                    $em->remove($src);
                    //$s->setTendencia(null);
                }
            }
            foreach ($tendencia->getSources() as $src) {
                $src->setTendencia($tendencia);
            }

            $slug = $this->slugify($tendencia->getNombre());
            $tendencia->setSlug($slug);
            $tendencia->setUpdatedAt(new \DateTime('now'));
            $em->persist($tendencia);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Su tendencia fue guardada !');
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
            $request->getSession()->getFlashBag()->add('success', 'Su inspiracion fue guardada !');
            return $this->redirectToRoute('admin_tendencia_edit',array('id'=>$id));
        }


        return $this->render(
            'admin/tendencia_edit.html.twig',
            array(
                'tendencia'=>$tendencia,
                'inspiraciones'=>$inspiraciones,
                'form_tendencia' => $form_tendencia->createView(),
                'form_inspiracion' => $form_inspiracion->createView(),
                'seccion' => $this->menuSelected('tendencias')

            )
        );
    }
    /**
     * @Route("/admin/videos/sort/list/{slug_site}", name="admin_videos_sort_list",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function adminVideosSortListAction(Request $request,$slug_site){
        //array('type'=>$slug_site),
        $videos = $this->getDoctrine()->getRepository('AppBundle:Video')->findBy(
            array('type'=>$slug_site),
            array('sort' => 'ASC')
        );

        return $this->render(
            'admin/videos_sort.html.twig',
            array(
                'videos' => $videos,
                'seccion' => $this->menuSelected('videos')
            )
        );
    }
    /**
     * @Route("/admin/musicas/sort/list/{slug_site}", name="admin_musicas_sort_list",requirements={
     *     "slug_site": "wedding|dinner|kids|party"
     * })
     */
    public function adminMusicasSortListAction(Request $request,$slug_site){
        $musicas = $this->getDoctrine()->getRepository('AppBundle:Musica')->findBy(
            array('type'=>$slug_site),
            array('sort' => 'ASC')
        );

        return $this->render(
            'admin/musicas_sort.html.twig',
            array(
                'musicas' => $musicas,
                'seccion' => $this->menuSelected('musicas')
            )
        );
    }
    /**
     * @Route("/admin/votos/sort/list", name="admin_votos_sort_list")
     */
    public function adminVotosSortListAction(Request $request){
        $votos = $this->getDoctrine()->getRepository('AppBundle:Voto')->findBy(
            array(),
            array('sort' => 'ASC')
        );

        return $this->render(
            'admin/votos_sort.html.twig',
            array(
                'votos' => $votos,
                'seccion' => $this->menuSelected('votos')
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
                'inspiraciones' => $inspiraciones,
                'seccion' => $this->menuSelected('tendencias')
            )
        );
    }
    /**
     * @Route("/admin/musicas/sort", name="admin_videos_sort")
     */
    public function adminMusicasSortAction(Request $request){
        if($request->isXmlHttpRequest()) {
            $sort_string = $request->request->get('sort');
            $arr = explode('&',$sort_string);
            $arr_ids = array();
            foreach ($arr as $value){
                $temp = explode('=',$value)[1];
                $arr_ids[] = $temp;
            }
            $sort = 0;
            $em = $this->getDoctrine()->getManager();
            foreach ($arr_ids as $musica_id){
                $musica = $this->getDoctrine()->getRepository('AppBundle:Musica')->find($musica_id);
                $musica->setSort($sort);
                $em->persist($musica);
                $sort++;
            }
            $em->flush();


            return new JsonResponse(array('sort' => $sort));
        }
    }
    /**
     * @Route("/admin/videos/sort", name="admin_videos_sort")
     */
    public function adminVideosSortAction(Request $request){
        if($request->isXmlHttpRequest()) {
            $sort_string = $request->request->get('sort');
            $arr = explode('&',$sort_string);
            $arr_ids = array();
            foreach ($arr as $value){
                $temp = explode('=',$value)[1];
                $arr_ids[] = $temp;
            }
            $sort = 0;
            $em = $this->getDoctrine()->getManager();
            foreach ($arr_ids as $video_id){
                $video = $this->getDoctrine()->getRepository('AppBundle:Video')->find($video_id);
                $video->setSort($sort);
                $em->persist($video);
                $sort++;
            }
            $em->flush();


            return new JsonResponse(array('sort' => $sort));
        }
    }
    /**
     * @Route("/admin/votos/sort", name="admin_votos_sort")
     */
    public function adminVotosSortAction(Request $request){
        if($request->isXmlHttpRequest()) {
            $sort_string = $request->request->get('sort');
            $arr = explode('&',$sort_string);
            $arr_ids = array();
            foreach ($arr as $value){
                $temp = explode('=',$value)[1];
                $arr_ids[] = $temp;
            }
            $sort = 0;
            $em = $this->getDoctrine()->getManager();
            foreach ($arr_ids as $voto_id){
                $inspiracion = $this->getDoctrine()->getRepository('AppBundle:Voto')->find($voto_id);
                $inspiracion->setSort($sort);
                $em->persist($inspiracion);
                $sort++;
            }
            $em->flush();
            /*$sort = (int)$request->request->get('sort');
            $id = (int)$request->request->get('id_inspiracion');
            $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(
                array(),
                array('sort' => 'ASC')
            );*/

            //$inspiracion = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->find($id);
            //$inspiracion->setSort($sort);




            return new JsonResponse(array('sort' => $sort));
        }
    }
    /**
     * @Route("/admin/inspiracion/sort", name="admin_tendencia_inpiracion_sort")
     */
    public function adminTendenciaInspiracionSortAction(Request $request){

        if($request->isXmlHttpRequest()) {
            $sort_string = $request->request->get('sort');
            $arr = explode('&',$sort_string);
            $arr_ids = array();
            foreach ($arr as $value){
                $temp = explode('=',$value)[1];
                $arr_ids[] = $temp;
            }
            $sort = 0;
            $em = $this->getDoctrine()->getManager();
            foreach ($arr_ids as $insp_id){
                $inspiracion = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->find($insp_id);
                $inspiracion->setSort($sort);
                $em->persist($inspiracion);
                $sort++;
            }
            $em->flush();
            /*$sort = (int)$request->request->get('sort');
            $id = (int)$request->request->get('id_inspiracion');
            $inspiraciones = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->findBy(
                array(),
                array('sort' => 'ASC')
            );*/

            //$inspiracion = $this->getDoctrine()->getRepository('AppBundle:Inspiracion')->find($id);
            //$inspiracion->setSort($sort);




            return new JsonResponse(array('sort' => $sort));
        }

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
            $request->getSession()->getFlashBag()->add('success', 'Su inspiracion fue guardada !');
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
            $request->getSession()->getFlashBag()->add('success', 'Su inspiracion fue borrada !');
            return $this->redirectToRoute('admin_tendencia_edit',array('id'=>$id));
        }
    }
    /**
     * @Route("/admin/negocios/acceptados/{page}", name="admin_negocios_acceptados", defaults={"page" = 1})
     */
    public function adminNegociosAcceptadosShowAction(Request $request,$page){
        $proveedores_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByState(true);

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
                'seccion'=>$this->menuSelected('negocios')
            )
        );
    }
    /**
     * @Route("/admin/negocios/en-espera/{page}", name="admin_negocios_espera", defaults={"page" = 1})
     */
    public function adminNegociosEnEsperaShowAction(Request $request,$page){
        $proveedores_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresByState(false);

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
                'seccion'=>$this->menuSelected('negocios')
            )
        );
    }
    /**
     * @Route("/admin/negocios/{page}", name="admin_negocios", defaults={"page" = 1})
     */
    public function adminNegociosShowAction(Request $request,$page){


        $proveedores_query = $this->getDoctrine()->getRepository('AppBundle:Proveedor')->getProveedoresOrderBy('alfa');

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
                'seccion'=>$this->menuSelected('negocios')
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

            $request->getSession()->getFlashBag()->add('success', 'DESAPROVADO');

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

            $request->getSession()->getFlashBag()->add('success', 'Aprovado');

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
        $fotos = $this->getDoctrine()->getRepository('AppBundle:Foto')->getProveedorFotos($proveedor,'sort');
        $renderOut = array();
        $renderOut['proveedor'] = $proveedor;
        $renderOut['fotos'] = $fotos;

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
    /**
     * @Route("/admin/votos", name="admin_votos")
     */
    public function adminVotosShowAction(Request $request){
        $votos = $this->getDoctrine()->getRepository('AppBundle:Voto')->findBy(array(),array('sort'=>'ASC'));

        return $this->render(
            'admin/votos.html.twig',
            array(
                'votos'=>$votos,
                'seccion' => $this->menuSelected('votos')
            )
        );
    }
    /**
     * @Route("admin/negocio/delete/{id}", name="admin_negocio_delete",requirements={
     *      "id": "\d+"
     * })
     */
    public function adminProveedorDeleteAction($id,Request $request){
        $proveedor =  $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($id);

        if($proveedor!=NULL){
            $em = $this->getDoctrine()->getManager();
            $em->remove($proveedor);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Negocio borrado correctamente !');

            return $this->redirectToRoute('admin_negocios');
        }
    }
    /**
     * @Route("admin/negocio/profile/{id}", name="admin_negocio_profile",requirements={
     *      "id": "\d+"
     * })
     */
    public function adminProveedorProfileAction($id,Request $request){
        $proveedor =  $this->getDoctrine()->getRepository('AppBundle:Proveedor')->find($id);

        $in = array();
        // weddings
        $categoria_wdding = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'wedding'));
        $in['wedding'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_wdding);
        // dinners
        $categoria_dinner = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'dinner'));
        $in['dinner'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_dinner);
        // kids
        $categoria_kids = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'kids'));
        $in['kids'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_kids);
        // party
        $categoria_party = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->findOneBy(array('slug'=>'party'));
        $in['party'] = $this->getDoctrine()->getRepository('AppBundle:CategoriaListado')->getCategoriasChildrenManaged($categoria_party);


        $form = $this->createForm(new ProveedorAdminProfileType($in), $proveedor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 4) save the Proveedor !
            $em = $this->getDoctrine()->getManager();
            $em->persist($proveedor);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Rublo de actividad actualiza !');

            return $this->redirectToRoute('admin_negocio_profile',array('id'=>$id));
        }

        $seccions_site = "";
        foreach ($proveedor->getCategoriasListado() as $c){
            if($c->getParent()->getSlug() == 'wedding'){
                $seccions_site = 'wedding';
                break;
            }
            else if($c->getParent()->getSlug() == 'kids'){
                $seccions_site = 'kids';
                break;
            }
            else if($c->getParent()->getSlug() == 'party'){
                $seccions_site = 'party';
                break;
            }
            else if($c->getParent()->getSlug() == 'dinner'){
                $seccions_site = 'dinner';
                break;
            }
        }

        return $this->render(
            'admin/negocio_profile.html.twig',
            array('form' => $form->createView(),
                'seccion'=>$seccions_site
            )
        );

    }
}