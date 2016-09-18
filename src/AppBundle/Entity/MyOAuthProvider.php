<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 9/15/2016
 * Time: 11:21 AM
 */
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

class MyOAuthProvider implements OAuthAwareUserProviderInterface
{
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $token = $response->getAccessToken();
        $facebookId =  $response->getUsername(); // Facebook ID, e.g. 537091253102004
        $username = $response->getRealName();
        $email = $response->getEmail();

        // search user in database
        $result = $this->em->getRepository('AppUserBundle:User')->findOneBy(
            array(
                'facebookId' => $facebookId
            )
        );

        if(!$result) {
            $user = new User();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setFacebookId($facebookId);

            // ..
            // save to database instructions
            // ..
        }

        return $this->loadUserByUsername($username);
    }

    // other methods
}