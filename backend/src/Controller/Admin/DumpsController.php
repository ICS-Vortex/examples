<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/dumps")
 */
class DumpsController extends AbstractController
{
    /**
     * @Route("/index.html", name="admin_dumps_index", methods={"GET"})
     * @return Response
     */
    public function index() {
        $finder = new Finder();
        $path = $this->getParameter('kernel.project_dir').'/public/uploads/dumps';
        $finder->files()->in($path);
        $files = [];
        foreach ($finder as $file) {
            $files[] = [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'createTime' => date('d.m.Y H:i:s', $file->getCTime()),
            ];
        }

        return $this->render('admin/dumps/index.html.twig', [
            'dumps' => $files,
        ]);
    }

    /**
     * @Route("/download/{filename}", name="admin_dumps_download", methods={"GET"})
     * @param $filename
     * @return BinaryFileResponse
     */
    public function download($filename) {
        $dump = new File($this->getParameter('kernel.project_dir').'/public/uploads/dumps/'.$filename);
        return $this->file($dump);
    }
}
