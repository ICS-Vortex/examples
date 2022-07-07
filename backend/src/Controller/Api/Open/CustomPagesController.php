<?php


namespace App\Controller\Api\Open;

use App\Entity\CustomPage;
use App\Entity\Tournament;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/custom_pages")
 */
class CustomPagesController extends AbstractController
{
    /**
     * @param SerializerInterface $serializer
     * @param null $page
     * @return JsonResponse
     * @throws ExceptionInterface
     * @Rest\Get("/{page}", name="api.open.custom_pages.get_page")
     */
    public function getPage(SerializerInterface $serializer, $page = null): JsonResponse
    {
        if (empty($page)) {
            return $this->json([
                'status' => 0,
                'message' => 'message.page_not_found',
            ], 404);
        }

        $customPage = $this->getDoctrine()->getRepository(CustomPage::class)->findOneBy([
            'url' => $page,
            'public' => true,
            'tournament' => null,
        ]);
        if (!empty($customPage)) {
            return $this->json([
                'status' => 0,
                'page' => $serializer->normalize($customPage, 'json', ['groups' => ['api_open_custom_pages']])
            ]);
        }

        return $this->json([
            'status' => 0,
            'message' => 'message.page_not_found',
        ], 404);
        //api_open_custom_pages
    }

    /**
     * @param SerializerInterface $serializer
     * @param Tournament $tournament
     * @param null $page
     * @return JsonResponse
     * @throws ExceptionInterface
     * @Rest\Get("/{tournament}/{page}", name="api.open.custom_pages.get_tournament")
     */
    public function getTournamentPage(SerializerInterface $serializer, Tournament $tournament, $page = null): JsonResponse
    {
        if (empty($tournament)) {
            return $this->json([
                'status' => 0,
                'message' => 'message.page_not_found',
            ], 404);
        }

        $pages = $tournament->getCustomPages();
        /** @var CustomPage $customPage */
        foreach ($pages as $customPage) {
            if ($customPage->getUrl() === $page && $customPage->isPublic()) {
                return $this->json([
                    'status' => 0,
                    'page' => $serializer->normalize($customPage, 'json', ['groups' => ['api_open_custom_pages']])
                ]);
            }
        }

        return $this->json([
            'status' => 0,
            'message' => 'message.page_not_found',
        ], 404);
        //api_open_custom_pages
    }
}
