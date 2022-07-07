<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin")
 */
class LoginController extends AbstractController
{
    /**
     * @Route("/login.html", name="admin.login.login", methods={"GET"})
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils): Response
    {
        $error = $authUtils->getLastAuthenticationError(true);
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('admin/login/login.html.twig', array(
            'lastUsername' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/check", name="admin.login.check", methods={"POST"})
     */
    public function check(){}

    /**
     * @Route("/logout", name="admin.login.logout")
     */
    public function logout(){}
}
