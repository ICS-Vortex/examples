<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/profile")
 */
class ProfileController extends AbstractController
{
    /** @Route("/info", name="admin.profile.info", methods={"GET"}, options={"expose":true}) */
    public function info()
    {
        return $this->json($this->getUser());
    }
}