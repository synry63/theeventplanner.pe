<?php
/**
 * Created by PhpStorm.
 * User: patrici-star
 * Date: 9/15/2016
 * Time: 11:21 AM
 */
namespace AppBundle\Entity;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {

        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
        $this->userManager->updateUser($user);
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $pic = imageCreateFromJpeg($response->getProfilePicture());
        $username = $response->getUsername();
        $nombres = $response->getFirstName();
        $apellidos = $response->getLastName();
        //$user = $this->userManager->findUserBy(array('facebookId' => $username));
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        //when the user is registrating
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';

            $path = dirname(dirname(dirname(dirname (__FILE__))));
            $path.="/web/images/users/";
            $filename_out = "userprofilepic_".rand();
            $output_filename = $path.$filename_out;
            $jpeg_quality = 100;
            imagejpeg($pic, $output_filename, $jpeg_quality);

            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($username);
            $user->setEmail($username);
            $user->setPassword($username);

            $user->setNombres($nombres);
            $user->setApellidos($apellidos);
            $user->setEnabled(true);

            $fotoProfile = new FotoProfile();
            $fotoProfile->setUser($user);
            $fotoProfile->setProfileName($filename_out);

            $user->setProfile($fotoProfile);

            $this->userManager->updateUser($user);


            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $serviceName = $response->getResourceOwner()->getName();

        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        //update access token
        $user->$setter($response->getAccessToken());
        return $user;
    }
}