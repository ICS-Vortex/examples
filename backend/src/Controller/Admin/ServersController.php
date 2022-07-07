<?php

namespace App\Controller\Admin;

use App\Entity\Server;
use App\Form\ServerType;
use App\Repository\BaseUserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/admin/servers")
 * Class ServersController
 * @package App\Controller\Admin
 */
class ServersController extends AbstractController
{
    /**
     * @Route("/list", name="admin.servers.list", methods={"GET"}, options={"expose": true})
     * @param SerializerInterface $serializer
     * @return Response
     * @throws ExceptionInterface
     */
    public function list(SerializerInterface $serializer){
        if ($this->getUser()->getRole() === BaseUserRepository::ROLE_ROOT) {
            $servers = $this->getDoctrine()->getRepository(Server::class)->findAll();
        } else {
            $servers = $this->getUser()->getServers();
        }

        return $this->json($serializer->normalize($servers, 'json', ['groups' => ['api_instances']]));
    }

    /**
     * @Route("/get-form/{server}", name="admin.servers.get_form", methods={"GET"}, options={"expose": true})
     * @param SerializerInterface $serializer
     * @param Server|null $server
     * @return Response
     * @throws ExceptionInterface
     */
    public function getServerForm(SerializerInterface $serializer, Server $server = null)
    {
        if (empty($server)) {
            return $this->json([], 404);
        }
        if (!$this->getUser()->hasAccessToServer($server)) {
            return $this->json([
               'message' => 'Forbidden',
            ], 403);
        }

        $form = $this->createForm(ServerType::class, $server);
        $view = $this->renderView('admin/forms/Server/edit.html.twig', [
            'form' => $form->createView()
        ]);

        return $this->json([
            'form' => $view,
            'server' => $serializer->normalize($server, 'json', ['groups' => ['api_instances']]),
        ]);
    }

    /**
     * @Route("/{server}/show.html", name="admin.servers.show", methods={"GET"})
     * @param Server $server
     * @return Response
     */
    public function show(Server $server)
    {
        if (!$this->getUser()->hasAccessToServer($server)) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        return $this->render('admin/servers/show.html.twig', array(
            'server' => $server,
        ));
    }

    /**
     * @Route("/edit/{server}", name="admin.servers.edit", methods={"POST"}, options={"expose":true})
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param Server|null $server
     * @return RedirectResponse|Response
     */
    public function edit(SerializerInterface $serializer, Request $request, Server $server = null)
    {
        if (empty($server)) {
            return $this->json([], 404);
        }

        if (!$this->getUser()->hasAccessToServer($server)) {
            return $this->json([
                'message' => 'Forbidden',
            ], 403);
        }
        $data = json_decode($request->getContent(), true);
        dump($data);exit;

//        $form = $this->createForm(ServerType::class, $server);
//        $em = $this->getDoctrine()->getManager();
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em->persist($server);
//            $em->flush();
//
//            return $this->redirectToRoute('admin.servers.show', array('server' => $server->getId()));
//        }

        return $this->json($serializer->normalize($server, 'json', ['groups' => ['api_instances']]));
    }

    /**
     * @Route("/{server}/clear-stats", name="admin.servers.clear", methods={"GET","POST"})
     * @param Server $server
     * @param TranslatorInterface $translator
     * @return RedirectResponse
     */
    public function clear(Server $server, TranslatorInterface $translator){
        if (!$this->getUser()->hasAccessToServer($server)) {
            throw new AccessDeniedException('Unable to access this page!');
        }
        $em = $this->getDoctrine()->getManager();
        try{
            $em->getRepository(Server::class)->clearStatistics($server);
            $this->addFlash('info', $translator->trans('message.statistics.cleared'));
            return $this->redirectToRoute('admin.index.index');
        }catch (Exception $e){
            $this->addFlash('danger', $translator->trans('message.statistics.not_cleared'));
            return $this->redirectToRoute('admin.index.index');
        }
    }
}
