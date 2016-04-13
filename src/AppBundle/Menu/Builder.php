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

        $menu->addChild('Home', array('route' => 'wedding_start'));

        $menu->addChild('About Me', array('route' => 'wedding_contactenos'));

        return $menu;
    }
    public function mainMenuWedding(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $em = $this->container->get('doctrine')->getManager();

        //$uri = $this->container->get('router')->generate('wedding_start');
        $menu->addChild('home',array(
            'route' => 'wedding_start',
            'label' => 'Inicio',
        )
        );
        $menu->addChild('Proveedores');
        $menu['Proveedores']->setUri('#');
        $menu->addChild('Inspiraciones');
        $menu['Inspiraciones']->setUri('#');

        //$uri = $this->container->get('router')->generate('wedding_contactenos');
        $menu->addChild('contactenos',array(
                'route' => 'wedding_contactenos',
                'label' => 'Contactenos',
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

        //$CategoriaListadoRepo = $em->getRepository('AppBundle:CategoriaListado');
        //$weddingParentCategoria = $CategoriaListadoRepo->findOneBy(array('slug' => 'wedding'));
        $children_categories =  $em->getRepository('AppBundle:CategoriaListado')->getCategoriasChildren('wedding');//$weddingParentCategoria->getChildren();

        foreach ($children_categories as $a_child) {
            //$uri = $this->container->get('router')->generate('proveedores_category',array('slug_category'=>$a_child->getSlug()));
            $menu['Proveedores']->addChild($a_child->getNombre(),
                array(
                 'route' => 'proveedores_category','routeParameters' => array('slug_category' => $a_child->getSlug()),
                 'extras' => array('img' =>$a_child->getImgSmall()),
                )
            );

        }

        //$menu->addChild($test[0]->getNombre(), array('route' => 'wedding_start'));

        /*$proveedores = $proveedorRepo->findAll();



        $menu->addChild('Proveedores');
        $menu['Proveedores']->setUri('#');*/

        // access services from the container!
        /*$em = $this->container->get('doctrine')->getManager();
        // findMostRecent and Blog are just imaginary examples
        $blog = $em->getRepository('AppBundle:Blog')->findMostRecent();

        $menu->addChild('Latest Blog Post', array(
            'route' => 'blog_show',
            'routeParameters' => array('id' => $blog->getId())
        ));

        // create another menu item
        $menu->addChild('About Me', array('route' => 'about'));
        // you can also add sub level's to your menu's as follows
        $menu['About Me']->addChild('Edit profile', array('route' => 'edit_profile'));*/

        // ... add more children

        return $menu;
    }
}