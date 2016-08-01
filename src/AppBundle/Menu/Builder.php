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
                'route' => 'admin_start',
                'label' => 'Inicio',
            )
        );
        $menu->addChild('negocios-lista',array(
                'route' => 'admin_negocios',
                'label' => 'Lista de negocios',
            )
        );
        $menu->addChild('tendencias-lista',array(
                'route' => 'admin_tendencias',
                'label' => 'Lista de tendencias',
            )
        );

        return $menu;
    }
    public function menuNegocio(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        $menu->addChild('negocio-home',array(
                'route' => 'negocio_zona',
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
        $menu->addChild('negocio-comentarios',array(
                'route' => 'negocio_zona_comentarios',
                'label' => 'Comentarios',
            )
        );
        $menu->addChild('negocio-mapa',array(
                'route' => 'negocio_zona_map',
                'label' => 'Mapa',
            )
        );
        $menu->addChild('negocio-preview',array(
                'route' => 'negocio_zona_preview',
                'label' => 'Preview',
            )
        );
        return $menu;

    }
    public function menuProfile(FactoryInterface $factory, array $options){
        $menu = $factory->createItem('root');
        $menu->addChild('about-me',array(
                'route' => 'fos_user_profile_show',
                'label' => 'Mis datos',
                'extras' => array(
                    'icon' => 'fa fa-user'
                )
            )
        );
        $menu->addChild('change-data',array(
                'route' => 'fos_user_profile_edit',
                'label' => 'Cambiar mis datos',
                'extras' => array(
                    'icon' => 'fa fa-edit'
                )

            )
        );
        $menu->addChild('change-foto',array(
                'route' => 'change_user_foto',
                'label' => 'Cambiar mi foto perfil',
                'extras' => array(
                    'icon' => 'fa fa-camera'
                )

            )
        );
        $menu->addChild('change-password',array(
                'route' => 'fos_user_change_password',
                'label' => 'Cambiar mi contraseña',
                'extras' => array(
                    'icon' => 'fa fa-lock'
                )
            )
        );

        $menu->addChild('show-comments',array(
                'route' => 'show_user_comments',
                'label' => 'Mis Comentarios',
                'extras' => array(
                    'icon' => 'fa fa-comments'
                )
            )
        );
        $menu->addChild('show-favoritos-fotos-proveedores',array(
                'route' => 'show_user_favoritos_fotos',
                'label' => 'Mis Fotos Proveedores',
                'extras' => array(
                    'icon' => 'fa fa-picture-o'
                )
            )
        );
        $menu->addChild('show-favoritos-fotos-inspiraciones',array(
                'route' => 'show_user_favoritos_fotos_inspiraciones',
                'label' => 'Mis Fotos Inspiraciones',
                'extras' => array(
                    'icon' => 'fa fa-picture-o'
                )
            )
        );
        $menu->addChild('show-favoritos-proveedores',array(
                'route' => 'show_user_favoritos_proveedores',
                'label' => 'Mis Proveedores Favoritos',
                'extras' => array(
                    'icon' => 'fa fa-users'
                )
            )
        );
        $menu->addChild('show-votos',array(
                'route' => 'show_user_votos',
                'label' => 'Mis Votos',
                'extras' => array(
                    'icon' => 'fa fa-heart'
                )
            )
        );

        return $menu;
    }
    public function mainMenuProfile(FactoryInterface $factory, array $options){

        $key = $options['site'];
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-default');

        //$uri = $this->container->get('router')->generate('wedding_start');
        $menu->addChild('home',array(
                'route' => 'site_start',
                'label' => 'Inicio',
                'routeParameters' => array('slug_site' => $key)

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => $key)
            )
        );

        $menu->addChild('center-image', array(
            'uri' => '#',
            'label' => '',
            'attributes' => array('id' => 'center-image'),
            'extras' => array(
                'img' => '/images/'.$options['image_title']
            )
        ));

        $menu->addChild('inspiraciones',array(
                'route' => 'inspiraciones_start',
                'label' => 'Inspiraciones',
                'routeParameters' => array('slug_site' => $key),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');



        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => $key)
            )
        );

        return $menu;
    }
    public function mainMenuKids(FactoryInterface $factory, array $options)
    {
        $key = 'kids';
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-default');

        //$uri = $this->container->get('router')->generate('wedding_start');
        $menu->addChild('home',array(
                'route' => 'site_start',
                'label' => 'Inicio',
                'routeParameters' => array('slug_site' => $key)

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => $key)
            )
        );
        //$menu->addChild('Proveedores');
        //$menu['Proveedores']->setUri('#');

        $menu->addChild('center-image', array(
            'uri' => '#',
            'label' => '',
            'attributes' => array('id' => 'center-image'),
            'extras' => array(
                'img' => '/images/'.$options['image_title']
            )
        ));

        $menu->addChild('inspiraciones',array(
                'route' => 'inspiraciones_start',
                'label' => 'Inspiraciones',
                'routeParameters' => array('slug_site' => $key),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');



        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => $key)
            )
        );



        return $menu;
    }
    public function mainMenuDinner(FactoryInterface $factory, array $options)
    {
        $key = 'dinner';
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-default');

        //$uri = $this->container->get('router')->generate('wedding_start');
        $menu->addChild('home',array(
                'route' => 'site_start',
                'label' => 'Inicio',
                'routeParameters' => array('slug_site' => $key)

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => $key)
            )
        );
        //$menu->addChild('Proveedores');
        //$menu['Proveedores']->setUri('#');

        $menu->addChild('center-image', array(
            'uri' => '#',
            'label' => '',
            'attributes' => array('id' => 'center-image'),
            'extras' => array(
                'img' => '/images/'.$options['image_title']
            )
        ));

        $menu->addChild('inspiraciones',array(
                'route' => 'inspiraciones_start',
                'label' => 'Inspiraciones',
                'routeParameters' => array('slug_site' => $key),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');



        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => $key)
            )
        );



        return $menu;
    }
    public function mainMenuParty(FactoryInterface $factory, array $options)
    {
        $key = 'party';
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-default');

        //$uri = $this->container->get('router')->generate('wedding_start');
        $menu->addChild('home',array(
                'route' => 'site_start',
                'label' => 'Inicio',
                'routeParameters' => array('slug_site' => $key)

            )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => $key)
            )
        );
        //$menu->addChild('Proveedores');
        //$menu['Proveedores']->setUri('#');

        $menu->addChild('center-image', array(
            'uri' => '#',
            'label' => '',
            'attributes' => array('id' => 'center-image'),
            'extras' => array(
                'img' => '/images/'.$options['image_title']
            )
        ));

        $menu->addChild('inspiraciones',array(
                'route' => 'inspiraciones_start',
                'label' => 'Inspiraciones',
                'routeParameters' => array('slug_site' => $key),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');



        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => $key)
            )
        );



        return $menu;
    }
    public function mainMenuWedding(FactoryInterface $factory, array $options)
    {
        $key = 'wedding';
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-default');

        //$uri = $this->container->get('router')->generate('wedding_start');
        $menu->addChild('home',array(
            'route' => 'site_start',
            'label' => 'Inicio',
            'routeParameters' => array('slug_site' => $key)

        )
        );
        $menu->addChild('proveedores',array(
                'route' => 'site_categorias',
                'label' => 'Proveedores',
                'routeParameters' => array('slug_site' => $key)
            )
        );
        //$menu->addChild('Proveedores');
        //$menu['Proveedores']->setUri('#');

        $menu->addChild('center-image', array(
            'uri' => '#',
            'label' => '',
            'attributes' => array('id' => 'center-image'),
            'extras' => array(
                'img' => '/images/'.$options['image_title']
            )
        ));

        $menu->addChild('inspiraciones',array(
        'route' => 'inspiraciones_start',
        'label' => 'Inspiraciones',
        'routeParameters' => array('slug_site' => $key),

            )
        );
        $menu['inspiraciones']->setLinkAttribute('class', 'external-link');



        $menu->addChild('contactenos',array(
                'route' => 'site_contactenos',
                'label' => 'Contáctenos',
                'routeParameters' => array('slug_site' => $key)
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