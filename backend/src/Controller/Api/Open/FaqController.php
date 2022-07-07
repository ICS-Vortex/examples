<?php

namespace App\Controller\Api\Open;

use App\Entity\Faq;
use App\Entity\Instance;
use App\Entity\Server;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/faq")
 */
class FaqController extends AbstractController
{
    /**
     * @Route("/list")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function list(Request $request, SerializerInterface $serializer) : JsonResponse
    {
        $serialIsValid = $this->getDoctrine()->getRepository(Instance::class)->serialIsValid(
            $request->headers->get('X-DCS-SERIAL', null)
        );
        if ($serialIsValid === true) {
            $options = ['forSender' => true];
        } else {
            return $this->json([], 404);
        }

        $data = $this->getDoctrine()->getRepository(Faq::class)->findBy($options);
        $questions = $serializer->normalize($data, 'json', ['groups' => 'api_faq']);

        return $this->json([
            'status' => 0,
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/server/{server}")
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return JsonResponse
     */
    public function server(SerializerInterface $serializer, Server $server = null)
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        $data = $this->getDoctrine()->getRepository(Faq::class)->findBy(['server' => $server]);
        $faq = $serializer->normalize($data, 'json', ['groups' => 'api_faq']);

        return $this->json($faq);
    }
}
