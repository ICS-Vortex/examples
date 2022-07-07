<?php


namespace App\Controller\System;


use App\Entity\Server;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServerController
 * @package App\Controller\System
 * @Route("/system/server")
 */
class ServerController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("/clear", name="system.server.clear")
     * @return RedirectResponse
     */
    public function clear(Request $request) : RedirectResponse
    {
        $id = (int) $request->query->get('id');
        $server = $this->getDoctrine()->getRepository(Server::class)->find($id);
        if ($server !== null && $this->getUser()->hasAccessToServer($server)) {
            $process = $this->getDoctrine()->getRepository(Server::class)->clearStatistics($server);
            if ($process) {
                $this->addFlash('success', $server->getName() . ' statistics data deleted');
            } else {
                $this->addFlash('danger', 'Statistics not deleted on server '.$server->getName().'. Check LOG file!!!');
            }
        }

        return $this->redirectToRoute('easycorp_bundle_easyadmin_abstractdashboard_index', array(
            'action' => 'list',
            'entity' => $request->query->get('entity', 'Server'),
        ));
    }

}
