<?php

namespace AppBundle\GrantExtensions;

use FOS\OAuthServerBundle\Storage\GrantExtensionInterface;
use OAuth2\Model\IOAuth2Client;
use FOS\UserBundle\Doctrine\UserManager;

class FacebookGrantExtension implements GrantExtensionInterface
{

    /**
     * @var UserManager
     */
    protected $userManager = null;

    /**
     * @var \Facebook\Facebook
     */
    protected $facebookSdk = null;

    public function __construct(UserManager $userManager, \Facebook\Facebook $facebookSdk)
    {
        $this->userManager = $userManager;
        $this->facebookSdk = $facebookSdk;
    }

    /**
     * @see OAuth2\IOAuth2GrantExtension::checkGrantExtension
     */
    public function checkGrantExtension(IOAuth2Client $client, array $inputData, array $authHeaders)
    {
        if (!isset($inputData['facebook_access_token'])) {
            return false;
        }

        $this->facebookSdk->setDefaultAccessToken($inputData['facebook_access_token']);
        try {
            // Try to get the user with the facebook token from Open Graph
            $fbData = $this->facebookSdk->get('/me?fields=email,id,first_name,last_name,name,name_format');


            if (!$fbData instanceof \Facebook\FacebookResponse) {
                return false;
            }

            // Check if a user match in database with the facebook id
            $user = $this->userManager->findUserBy([
                'facebookId' => $fbData->getDecodedBody()['id']
            ]);

            // If none found, try to match email
            if (null === $user && isset($fbData->getDecodedBody()['email'])) {
                $user = $this->userManager->findUserBy([
                    'email' => $fbData->getDecodedBody()['email']
                ]);
            }

            // If no user found, register a new user and grant token
            if (null === $user) {
                // TODO: Create new user
                return false;
            } else { // Else, return the access_token for the user
                // Associate user with facebookId
                $user->setFacebookId($fbData->getDecodedBody()['id']);
                $this->userManager->updateUser($user);

                return array(
                    'data' => $user
                );
            }
        } catch (\FacebookApiExceptionion $e) {
            return false;
        }
    }

}
