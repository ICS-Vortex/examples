<?php

namespace App\Controller\Api\Open;

use App\Entity\Pilot;
use App\Service\ApiAccessService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DcsServerController
 * @package App\Controller\Api\Open
 * @Route("/api/open/dcs-server")
 */
class DcsServerController extends AbstractController
{
    /**
     * @param ApiAccessService $apiAccessService
     * @param string|null $ucid
     * @return JsonResponse
     * @Route("/access-rights/{ucid}", methods={"GET"}, name="api.open.dcs_servers.access_rights")
     */
    public function accessRights(ApiAccessService $apiAccessService, string $ucid = null): JsonResponse
    {
        if (!$apiAccessService->isServerIdentifierValid() || empty($ucid)) {
            return $this->json([], 403);
        }
        /** @var Pilot $user */
        $user = $this->getDoctrine()->getRepository(Pilot::class)->findOneBy(['ucid' => $ucid]);
        if (empty($user)) return $this->json([], 403);

        return $this->json([
            'roles' => $user->getRoles(),
            'isBanned' => $user->isBanned(),
            'banRecords' => $user->getBanRecords()->count(),
        ]);
    }
}