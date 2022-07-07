<?php

namespace App\Controller\Admin;

use App\Entity\CouponFile;
use App\Entity\Log;
use App\Entity\Tournament;
use App\Entity\TournamentCoupon;
use App\Form\Admin\CouponsFileType;
use App\Message\CouponsFileMessage;
use Exception;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Handler\UploadHandler;

/**
 * @Route("/admin/coupons")
 */
class CouponsController extends AbstractController
{
    /**
     * @Route("/upload", name="admin.coupons.upload", methods={"POST"}, options={"expose": true})
     * @param Request $request
     * @param UploadHandler $uploadHandler
     * @param MessageBusInterface $bus
     * @return JsonResponse
     */
    public function upload(Request $request, UploadHandler $uploadHandler, MessageBusInterface $bus): JsonResponse
    {
        $couponsFile = new CouponFile();
        $form = $this->createForm(CouponsFileType::class, $couponsFile);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadHandler->upload($couponsFile, 'sourceFile');
                $this->getDoctrine()->getManager()->persist($couponsFile);
                $this->getDoctrine()->getManager()->flush();
                $bus->dispatch(new CouponsFileMessage($couponsFile));
                return $this->json([
                    'status' => 0,
                    'message' => 'Coupons file uploaded successfully'
                ]);
            }catch (Exception $e) {
                $this->getDoctrine()->getRepository(Log::class)
                    ->log($e->getTraceAsString(), Logger::EMERGENCY, 'controller');
                return $this->json([
                    'status' => 1,
                    'message' => 'Upload failed due to server error',
                    'error' => $e->getMessage()
                ]);
            }
        }
        return $this->json([
            'status' => 1,
            'message' => 'Request failed. Invalid data'
        ]);
    }

    /**
     * @Route("/{file}/invoke-upload", name="api.coupons.invoke_upload", methods={"POST"}, options={"expose": true})
     */
    public function invokeUpload(MessageBusInterface $bus, CouponFile $file = null): JsonResponse
    {
        if (empty($file)) {
            return $this->json([
                'status' => 1,
                'message' => 'File not found',
            ]);
        }
        if ($file->getUploaded()) {
            return $this->json([
                'status' => 1,
                'message' => 'File already processed',
            ]);
        }
        $bus->dispatch(new CouponsFileMessage($file));

        return $this->json([
            'status' => 0,
            'message' => 'Your file will be processed soon'
        ]);
    }

    /**
     * @Route("/files", name="api.coupons.files", methods={"GET"}, options={"expose": true})
     */
    public function files(SerializerInterface $serializer): JsonResponse
    {
        $files = $this->getDoctrine()->getRepository(CouponFile::class)->findAll();
        return $this->json($serializer->normalize($files, 'json', ['groups' => ['admin_coupons']]));
    }

    /**
     * @Route("/get-coupons-file-form", name="api.coupons.coupons_file_form", methods={"GET"}, options={"expose": true})
     */
    public function getCouponsFileForm(): JsonResponse
    {
        $form = $this->createForm(CouponsFileType::class, new CouponFile());
        $view = $this->renderView('admin/coupons/couponsFileForm.html.twig', [
            'form' => $form->createView()
        ]);
        return $this->json(['html' => $view]);
    }

    /**
     * @Route("/tournaments", name="api.coupons.tournaments", methods={"GET"}, options={"expose": true})
     */
    public function tournaments(SerializerInterface $serializer): JsonResponse
    {
        $tournament = $this->getDoctrine()->getRepository(Tournament::class)->findBy(['provideCoupons' => true]);
        return $this->json($serializer->normalize($tournament, 'json', ['groups' => 'api_tournaments']));
    }

    /**
     * @Route("/users-with-coupons", name="api.coupons.users_with_coupons", methods={"GET"}, options={"expose": true})
     */
    public function usersWithCoupons(SerializerInterface $serializer): JsonResponse
    {
        $coupons = $this->getDoctrine()->getRepository(TournamentCoupon::class)->getUsersWithCoupons();
        return $this->json($serializer->normalize($coupons, 'json',['groups' => ['tournament_coupons']]));
    }

    /**
     * @Route("/requests", name="api.coupons.requests", methods={"GET"}, options={"expose": true})
     */
    public function requests()
    {
        return $this->json([]);
    }
}
