<?php

namespace App\Controller\Api\Open;

use App\Entity\Manual;
use App\Entity\Plane;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ManualsController
 * @package App\Controller\Main
 * @Route("/api/open/manuals")
 */
class ManualsController extends AbstractController
{
    /**
     * @Route("/categories", name="index.manuals.list", methods={"GET"})
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function categories(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = [];
        /** @var Plane $item */
        foreach ($em->getRepository(Plane::class)->findBy(['mod' => false], ['name' => 'ASC']) as $item) {
            if ($item->getImage() === null) {
                $image = '/images/planes/' . strtolower($item->getName()) . '.png';
            } else {
                $image = $this->getParameter('app.path.manuals_images') . '/' . $item->getImage();
            }
            $path = $this->getParameter('protocol') . '://' . $this->getParameter('mainHost');
            $item->setImage($path . $image);
            $categories[] = $item;
        }
        return $this->json(
            $serializer->normalize($categories, 'json', ['groups' => 'api_manuals'])
        );
    }

    /**
     * @param SerializerInterface $serializer
     * @param Plane|null $category
     * @return JsonResponse
     * @Route("/category/{category}")
     */
    public function category(SerializerInterface $serializer, Plane $category = null)
    {
        if (empty($category)) {
            return $this->json([], 404);
        }
        $manuals = $category->getManuals(false);
        if (empty($manuals)) {
            return $this->json([], 404);
        }
        return $this->json($serializer->normalize($manuals, 'json', ['groups' => 'api_manuals']));
    }

    /**
     * @Route("/all", name="main.manuals.all", methods={"GET"})
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function all(SerializerInterface $serializer)
    {
        return $this->json(
            $serializer->normalize(
                $this->getDoctrine()->getRepository(Manual::class)->getManuals(false),
                'json',
                ['groups' => 'api_manuals']
            )
        );
    }

    /**
     * @Route("/download/{manual}", methods={"GET"})
     * @param Manual|null $manual
     */
    public function download(Manual $manual = null)
    {
        if ($manual === null || !$manual->getPublic()) {
            return $this->json([], 404);
        }
        $file = $this->getParameter('kernel.project_dir') . '/public'
            . $this->getParameter('app.path.manuals_files') . '/' . $manual->getDocument();
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();
        $response = new BinaryFileResponse($file);

        if ($mimeTypeGuesser->isGuesserSupported()) {
            $response->headers->set('Content-Type', $mimeTypeGuesser->guessMimeType($file));
        } else {
            $response->headers->set('Content-Type', 'text/plain');
        }
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $manual->getDocument());
        return $response;
    }

}
