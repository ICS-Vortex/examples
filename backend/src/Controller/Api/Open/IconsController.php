<?php


namespace App\Controller\Api\Open;


use App\Entity\SocialLink;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/open/icons")
 */
class IconsController extends AbstractController
{
    /**
     * @Route("/list", name="api.open.icons.list")
     */
    public function list()
    {
        $em = $this->getDoctrine();
        $list = $em->getRepository(SocialLink::class)->findAll();
        return $this->json($list);
    }
}