<?php
// Application/UserBundle/EventListener/OverrideAttachRefreshTokenOnSuccessListener.php

namespace App\Listener;

use DateTime;
use Gesdinet\JWTRefreshTokenBundle\EventListener\AttachRefreshTokenOnSuccessListener;
use Gesdinet\JWTRefreshTokenBundle\Request\RequestRefreshToken;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class OverrideAttachRefreshTokenOnSuccessListener extends AttachRefreshTokenOnSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function attachRefreshToken(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
        $request = $this->requestStack->getCurrentRequest();

        if (!$user instanceof UserInterface) {
            return;
        }

        $refreshTokenString = RequestRefreshToken::getRefreshToken($request, 'refreshToken');

        if ($refreshTokenString) {
            $data['refresh_token'] = $refreshTokenString;
        } else {
            $datetime = new DateTime();
            $datetime->modify('+' . $this->ttl . ' seconds');

            $refreshToken = $this->refreshTokenManager->create();
            $refreshToken->setUsername($user->getId()); // Change this to $user->getEmail() for your purpose
            $refreshToken->setRefreshToken();
            $refreshToken->setValid($datetime);

            $valid = false;
            while (false === $valid) {
                $valid = true;
                $errors = $this->validator->validate($refreshToken);
                if ($errors->count() > 0) {
                    foreach ($errors as $error) {
                        if ('refreshToken' === $error->getPropertyPath()) {
                            $valid = false;
                            $refreshToken->setRefreshToken();
                        }
                    }
                }
            }

            $this->refreshTokenManager->save($refreshToken);
            $data['refresh_token'] = $refreshToken->getRefreshToken();
        }

        $event->setData($data);
    }
}
