<?php


namespace App\Controller\Main;

use App\Constant\Parameter;
use App\Entity\Pilot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/index.html", name="admin_profile_index", methods={"GET"})
     */
    public function index() {
        return $this->render('main/profile/index.html.twig', [

        ]);
    }

    /**
     * @Route("/upload-avatar", name="main_profile_upload_avatar",options={"expose": true}, methods={"POST"})
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request, TranslatorInterface $translator) {
        $em = $this->getDoctrine()->getManager();
        /** @var File $file */
        $file = $request->files->get('file');
        if (empty($file)) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.empty_fileset'),
            ]);
        }
        $dir = $this->getParameter('kernel.project_dir');

        $hash = md5(uniqid(rand(), true));
        $extension = $file->guessExtension();
        $avatarsFolder= $dir. Parameter::FOLDER_PUBLIC.Parameter::FOLDER_UPLOAD_AVATARS;
        if (!is_dir($avatarsFolder)) {
            return $this->json([
                'status' => 1,
                'message' => $translator->trans('message.dir_not_found'),
            ]);
        }
        $fileName = $hash.'.'.$extension;
        $file->move($avatarsFolder, $fileName);
        /** @var Pilot $user */
        $user = $this->getUser();
        $user->setAvatar($fileName);
        $em->merge($user);
        $em->flush();
        return $this->json([
            'status' => 0,
            'message' => $translator->trans('message.file_uploaded'),
            'image' => Parameter::FOLDER_UPLOAD_AVATARS.'/'. $fileName,
        ]);
    }
}