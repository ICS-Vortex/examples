<?php

namespace App\Controller\Api;

use App\Entity\Pilot;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/api/{_locale}/upload")
 */
class UploadController extends AbstractController
{
    /**
     * @Route("/image", name="api.upload.image")
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return JsonResponse
     */
    public function uploadImage(Request $request, SluggerInterface $slugger): JsonResponse
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('files');
        $allowedImages = ['jpeg', 'jpg', 'gif', 'png'];
        if (!in_array($file->guessExtension(), $allowedImages)) {
            return $this->json([
                'message' => 'message.invalid_image_format',
                'extension' => $file->guessExtension()
            ], 500);
        }
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
//        $safeFilename = $slugger->slug($originalFilename);
        $filename = uniqid() . '.' . $file->guessExtension();
        $dir = $this->getParameter('kernel.project_dir') . '/public' . $this->getParameter('app.path.avatar_images');
        try {
            if (empty($request->headers->get('X-IMAGE-TYPE'))) {
                return $this->json([
                    'status' => 1,
                    'message' => 'Bad request',
                ], ResponseCode::HTTP_BAD_REQUEST);
            }
            $file->move($dir, $filename);
            /** @var Pilot $pilot */
            $pilot = $this->getUser();
            switch ($request->headers->get('X-IMAGE-TYPE')) {
                case 'photo' :
                    $pilot->setPhoto($filename);
                    break;
                case 'avatar' :
                    $pilot->setAvatar($filename);
                    break;
                case 'squadLogo':
                    $pilot->setSquadLogo($filename);
                    break;
                default:
                    return $this->json([
                        'status' => 1,
                        'message.invalid_picture_type'
                    ], ResponseCode::HTTP_BAD_REQUEST);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($pilot);
            $em->flush();
            return $this->json([
                'status' => 0,
                'message.picture_uploaded'
            ]);
        } catch (Exception $e) {
            return $this->json([
                'status' => 1,
                'message' => $e->getMessage(),
            ], ResponseCode::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
