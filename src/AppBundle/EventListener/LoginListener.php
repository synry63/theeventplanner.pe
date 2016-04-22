<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/14/16
 * Time: 7:49 AM
 */
namespace AppBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener implements EventSubscriberInterface
{
    private $container;


    public function __construct($container)
    {
        $this->container = $container;

    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onLogin',
            SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
        );
    }

    public function onLogin($event)
    {
        // FYI
        // if ($event instanceof UserEvent) {
        //    $user = $event->getUser();
        // }
        // if ($event instanceof InteractiveLoginEvent) {
        //    $user = $event->getAuthenticationToken()->getUser();
        // }


    }
}
