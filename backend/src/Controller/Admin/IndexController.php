<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin")
 * Class IndexController
 * @package App\Controller\Admin
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", name="admin.index.index")
     */
    public function index(): Response
    {
        return $this->render('admin/base.html.twig');
    }

    /**
     * @Route("/user", name="admin.index.user", options={"expose": true})
     */
    public function user(SerializerInterface $serializer): Response
    {
        $user = $serializer->normalize($this->getUser(), 'json', ['groups' => 'api_admin']);
        return $this->json($user);
    }
}
