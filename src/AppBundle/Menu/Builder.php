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

    public function testMenu(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'wedding_start',
                'routeParameters' => array('slug_site' => 'wedding')
        ));

        $menu->addChild('About Me', array('route' => 'wedding_contactenos'));

        return $menu;
    }
    public function menuAdmin(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        $menu->addChild('negocio-home',array(
                'route' => 'negocio_zona',
                'label' => 'Inicio',
            )
        );
        $menu->addChild('negocios-lista',array(
                'route' => 'admin_negocios',
                'label' => 'Lista de negocios',
            )
        );
        return $menu;
    }
    public function menuNegocio(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        $menu->addChild('negocio-home',array(
                'route' => 'admin_start',
                'label' => 'Inicio',
            )
        );
        $menu->addChild('negocio-password',array(
                'route' => 'negocio_zona_password',
                'label' => 'Cambiar contraseña',
            )
        );
        $menu->addChild('negocio-imagenes',array(
                'route' => 'negocio_zona_imagenes',
                'label' => 'Gestión de imagenes',
            )
        );
        $menu->addChild('negocio-logo',array(
                'route' => 'negocio_zona_logo',
                'label' => 'Cambiar logo',
            )
        );
        $menu->addChild('negocio-datos',array(
                'route' => 'negocio_zona_perfil',
                'label' => 'Cambiar datos',
            )
        );
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
        $menu->addChild('show-favoritos-fotos',array(
                'route' => 'show_user_favoritos_fotos',
                'label' => 'Mis Fotos Favoritas',
            )
        );
        $menu->addChild('show-favoritos-proveedores',array(
                'route' => 'show_user_favoritos_proveedores',
                'label' => 'Mis Proveedores Favoritos',
            )
        );

        return $menu;
    }
    public function mainMenu(FactoryInterface $factory, array $options){

        $slug_site = $options['slug_site'];

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $menu->addChild('home',array(
                'route' => 'site_start',
                'label' => 'Inicio',
                'routeParameters' => array('slug_site' => $slug_site)

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => $slug_site)
            )
        );


        $menu->addChild('inspiraciones',array(
                'route' => 'inspiraciones_start',
                'label' => 'Inspiraciones',
                'routeParameters' => array('slug_site' => $slug_site),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');

        //$uri = $this->container->get('router')->generate('wedding_contactenos');
        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => $slug_site)
            )
        );

        return $menu;
    }
    public function mainMenuKids(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $menu->addChild('home',array(
                'route' => 'site_start',
                'label' => 'Inicio',
                'routeParameters' => array('slug_site' => 'kids')

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => 'kids')
            )
        );


        $menu->addChild('inspiraciones',array(
                'route' => 'inspiraciones_start',
                'label' => 'Inspiraciones',
                'routeParameters' => array('slug_site' => 'kids'),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');

        //$uri = $this->container->get('router')->generate('wedding_contactenos');
        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => 'kids')
            )
        );

        return $menu;
    }
    public function mainMenuParty(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $menu->addChild('home',array(
                'route' => 'site_start',
                'label' => 'Inicio',
                'routeParameters' => array('slug_site' => 'party')

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => 'party')
            )
        );


        $menu->addChild('inspiraciones',array(
                'route' => 'inspiraciones_start',
                'label' => 'Inspiraciones',
                'routeParameters' => array('slug_site' => 'party'),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');

        //$uri = $this->container->get('router')->generate('wedding_contactenos');
        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => 'party')
            )
        );
    }
    public function mainMenuWedding(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

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


        $menu->addChild('inspiraciones',array(
        'route' => 'inspiraciones_start',
        'label' => 'Inspiraciones',
        'routeParameters' => array('slug_site' => 'wedding'),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');

        //$uri = $this->container->get('router')->generate('wedding_contactenos');
        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => 'wedding')
            )
        );
        /*$menu['Inspiraciones']->addChild(
            'Fotos',array('route' => 'inspiraciones_type','routeParameters' => array('slug_type' => 'fotos')
        ));
        $menu['Inspiraciones']->addChild(
            'Productos de Proveedor',array('route' => 'inspiraciones_type','routeParameters' => array('slug_type' => 'productos')
        ));
        $menu['Inspiraciones']->addChild(
            'Noticias',array('route' => 'inspiraciones_type','routeParameters' => array('slug_type' => 'noticias')
        ));*/


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