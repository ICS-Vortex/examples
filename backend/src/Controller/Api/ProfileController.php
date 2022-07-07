<?php

namespace App\Controller\Api;

use App\Entity\Pilot;
use App\Entity\SystemLog;
use App\Form\Api\Open\UcidProfileType;
use App\Form\Api\SocialNetworkType;
use App\Repository\SystemLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/api/{_locale}/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Rest\Get("/", name="api.profile.info")
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    public function getProfile(SerializerInterface $serializer): JsonResponse
    {
        return $this->json([
            'status' => 0,
            'user' => $serializer->normalize($this->getUser(), 'json', ['groups' => 'api_profile']),
        ]);
    }

    /**
     * @Rest\Put("/connect-social-network", name="api.profile.connect_social_network")
     * @param Request $request
     * @return JsonResponse
     */
    public function connectSocialAccount(Request $request): JsonResponse
    {
        /** @var Pilot $user */
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(SocialNetworkType::class, $user);
        $form->submit($data);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->json([
                'status' => 1,
                'message' => 'message.invalid_input_data',
                'errors' => $form->getErrors(true),
            ]);
        }

        $em = $this->getDoctrine()->getManager();

        $account = $em->getRepository(Pilot::class)->findOneBy(['facebookId' => $user->getFaceBookId()]);
        if (!empty($account)) {
            return $this->json([
                'status' => 1,
                'message' => 'message.account_already_connected',
            ]);
        }

        $em->persist($user);
        $em->flush();

        return $this->json([
            'status' => 0,
            'message' => 'message.profile_updated',
        ]);
    }

    /**
     * @Route("/edit", name="api.profile.edit", methods={"PUT"})
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function editProfile(Request $request, EntityManagerInterface $manager, TranslatorInterface $translator): JsonResponse
    {
        /** @var Pilot $user */
        $user = $this->getUser();
        $form = $this->createForm(UcidProfileType::class, $user);
        $data = json_decode($request->getContent(), true);

        $form->submit($data);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $manager->persist($user);
                    $manager->flush();
                    return $this->json(['status' => 0, 'message' => $translator->trans('message.profile_saved')]);
                } catch (Exception $e) {
                    $this->getDoctrine()->getRepository(SystemLog::class)
                        ->log($e->getMessage() . ' | ' . $e->getTraceAsString(), Logger::ALERT, SystemLogRepository::INITIATOR_PROFILE);
                    return $this->json(['status' => 0, 'message' => $translator->trans('message.internal_error_occurred')]);
                }
            }
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.invalid_data'),
                'errors' => $form->getErrors()
            ], Response::HTTP_BAD_REQUEST);
        }
        return $this->json([
            'status' => 1,
            'message' => $translator->trans('message.bad_request')
        ], Response::HTTP_BAD_REQUEST);
    }
}
