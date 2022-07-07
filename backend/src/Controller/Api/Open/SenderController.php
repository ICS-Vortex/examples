<?php


namespace App\Controller\Api\Open;

use App\Entity\SenderUpdate;
use App\Service\ApiAccessService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/open/sender")
 * Class SenderController
 * @package App\Controller\Api
 */
class SenderController extends AbstractController
{
    /**
     * @Route("/check-updates", name="api.sender.check_updates", methods={"GET"})
     * @param ApiAccessService $accessService
     * @return JsonResponse
     */
    public function checkUpdates(ApiAccessService $accessService) : JsonResponse
    {
        if ($accessService->isSerialNumberValid() === false) {
            return $this->json([
                'status' => 999,
                'message' => 'Invalid serial number'
            ], 403);
        }

        $latestVersion = $this->getDoctrine()->getRepository(SenderUpdate::class)->findBy([],['id' => 'DESC'], 1);
        if (empty($latestVersion)) {
            return $this->json([
                'status' => 1,
                'message' => 'Update information not found',
            ]);
        }
        $latest = reset($latestVersion);
        return $this->json([
            'status' => 0,
            'message' => 'Version info loaded',
            'version' => (int) $latest->getVersion(),
            'notes' => $latest->getNotes(),
        ]);
    }

    /**
     * @Route("/download/{version}", name="api.sender.download", methods={"GET"})
     * @param null $version
     * @param ApiAccessService $accessService
     * @return BinaryFileResponse|JsonResponse
     */
    public function download(ApiAccessService $accessService, $version = null)
    {
        if ($accessService->isSerialNumberValid() === false) {
            return $this->json([
                'status' => 999,
                'message' => 'Invalid serial number'
            ], 403);
        }

        if ($version !== null) {
            $file = $this->getDoctrine()->getRepository(SenderUpdate::class)->findOneBy([
                'version' => $version
            ]);
        } else {
            $file = $this->getDoctrine()->getRepository(SenderUpdate::class)
                ->findOneBy([],['id' => 'DESC']);
        }

        return $this->json([
            'status' => 0,
            'message' => 'Download link generated',
            'link' => $this->getParameter('secure') . '://'.$this->getParameter('mainHost')
                .$this->getParameter('app.path.sender_files').'/'.$file->getExe(),
            'file' => $file,
        ]);
    }

}
