<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/6/16
 * Time: 8:16 AM
 */
namespace AppBundle\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Matcher\Matcher;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public  function testMenu(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'wedding_start',
                'routeParameters' => array('slug_site' => 'wedding')
        ));

        $menu->addChild('About Me', array('route' => 'wedding_contactenos'));

        return $menu;
    }
    public function menuProfile(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        $menu->addChild('about-me',array(
                'route' => 'fos_user_profile_show',
                'label' => 'Mis datos',
            )
        );
        $menu->addChild('change-data',array(
                'route' => 'fos_user_profile_edit',
                'label' => 'Cambiar mis datos',
            )
        );
        $menu->addChild('change-password',array(
                'route' => 'fos_user_change_password',
                'label' => 'Cambiar mi contraseña',
            )
        );

        $menu->addChild('show-comments',array(
                'route' => 'show_user_comments',
                'label' => 'Mis Comentarios',
            )
        );
        $menu->addChild('show-favoritos',array(
                'route' => 'show_user_favoritos',
                'label' => 'Mis Favoritos',
            )
        );


        return $menu;
    }
    public function mainMenuWedding(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $em = $this->container->get('doctrine')->getManager();

        //$uri = $this->container->get('router')->generate('wedding_start');
        $menu->addChild('home',array(
            'route' => 'site_start',
            'label' => 'Inicio',
            'routeParameters' => array('slug_site' => 'wedding')

        )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => 'wedding')
            )
        );
        //$menu->addChild('Proveedores');
        //$menu['Proveedores']->setUri('#');


        $menu->addChild('Inspiraciones');
        $menu['Inspiraciones']->setUri('#');

        //$uri = $this->container->get('router')->generate('wedding_contactenos');
        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contactenos',
                'routeParameters' => array('slug_site' => 'wedding')
            )
        );
        $menu['Inspiraciones']->addChild(
            'Fotos',array('route' => 'inspiraciones_type','routeParameters' => array('slug_type' => 'fotos')
        ));
        $menu['Inspiraciones']->addChild(
            'Productos de Proveedor',array('route' => 'inspiraciones_type','routeParameters' => array('slug_type' => 'productos')
        ));
        $menu['Inspiraciones']->addChild(
            'Noticias',array('route' => 'inspiraciones_type','routeParameters' => array('slug_type' => 'noticias')
        ));


        /*$children_categories =  $em->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('wedding');//$weddingParentCategoria->getChildren();

        foreach ($children_categories as $a_child) {
            //$uri = $this->container->get('router')->generate('proveedores_category',array('slug_category'=>$a_child->getSlug()));
            $menu['Proveedores']->addChild($a_child->getNombre(),
                array(
                 'route' => 'proveedores_category','routeParameters' => array('slug_category' => $a_child->getSlug()),
                 'extras' => array('img' =>$a_child->getImgSmall()),
                )
            );

        }*/



        return $menu;
    }
}