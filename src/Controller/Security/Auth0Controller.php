<?php

namespace App\Controller\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Auth0Controller extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Route("/connect/auth0", name="connect_auth0_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // will redirect!
        return $clientRegistry
            ->getClient('auth0_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([], []);
    }

    /**
     * @Route("/connect/auth0/check", name="connect_auth0_check")
     */
    public function connectCheckAction()
    {
    }
}
