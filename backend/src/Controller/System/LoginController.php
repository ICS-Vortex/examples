<?php

namespace App\Controller\System;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/system")
 * @param AuthenticationUtils $authUtils
 * @return Response
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/login.html", name="system.login.index", methods={"GET"})
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError(true);
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('system/login/login.html.twig', array(
            'lastUsername' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/login-check", name="system.login.check")
     */
    public function check(){}

    /**
     * @Route("/logout", name="system.login.logout")
     */
    public function logout(){}
}