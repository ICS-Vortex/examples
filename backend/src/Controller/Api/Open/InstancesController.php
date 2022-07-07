<?php

namespace App\Controller\Api\Open;

use App\Constant\Parameter;
use App\Entity\Instance;
use App\Entity\Server;
use App\Form\SenderServerType;
use App\Service\ApiAccessService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/open/instances")
 */
class InstancesController extends AbstractController
{
    /**
     * @Route("/validate", methods={"GET", "POST"})
     * @param ApiAccessService $apiAccessService
     * @return JsonResponse
     */
    public function validate(ApiAccessService $apiAccessService): JsonResponse
    {
        if (!$apiAccessService->isSerialNumberValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Access denied',
            ), 403);
        }

        return $this->json([
            'status' => 0,
            'message' => 'Serial number authorized',
        ]);

    }

    /**
     * @Route("/get-servers", methods={"GET"})
     * @param ApiAccessService $apiAccessService
     * @param Request $request
     * @return JsonResponse
     */
    public function getServers(ApiAccessService $apiAccessService, Request $request) : JsonResponse
    {
        if (!$apiAccessService->isSerialNumberValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Access denied',
            ), 403);
        }
        $servers = [];
        $instance = $this->getDoctrine()->getRepository(Instance::class)->findOneBy([
            'serialNumber' => $request->headers->get(Parameter::DCS_SERIAL_HEADER, null),
        ]);
        if (empty($instance)) {
            return $this->json([], 404);
        }

        if ($instance->isEnabled() === false) {
            return $this->json([], 403);
        }
        $host = $this->getParameter('mainHost');
        $imagesFolder = $this->getParameter('app.path.servers_images');
        /** Server $server */
        foreach ($instance->getServers() as  $server) {
            $servers [] = [
                'id' => $server->getId(),
                'name' => $server->getName(),
                'email' => $server->getEmail(),
                'identifier' => $server->getIdentifier(),
                'discordServerId' => $server->getDiscordServerId(),
                'discordBotToken' => $server->getDiscordBotToken(),
                'sendDiscordNotifications' => $server->isSendDiscordNotifications(),
                'sendDiscordServerNotifications' => $server->isSendDiscordServerNotifications(),
                'sendDiscordFlightNotifications' => $server->isSendDiscordFlightNotifications(),
                'sendDiscordCombatNotifications' => $server->isSendDiscordCombatNotifications(),
                'discordWebHook' => $server->getDiscordWebHook(),
                'image' => $this->getParameter('secure') . '://'.$host.$imagesFolder.'/'.$server->getBackgroundImage(),
                'address' => $server->getAddress(),
                'port' => $server->getPort(),
                'teamSpeakAddress' => $server->getTeamSpeakAddress(),
                'srsAddress' => $server->getSrsAddress(),
                'srsFile' => $server->getSrsFile(),
                'reportsLocation' => $server->getReportsLocation(),
                'showMap' => $server->isShowMap(),
                'active' => $server->getActive(),
                'isOnline' => $server->getIsOnline(),
            ];
        }

        return $this->json($servers);
    }

    /**
     * @Route("/get-server/{server}", methods={"GET"})
     * @param ApiAccessService $apiAccessService
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param Server $server
     * @return JsonResponse
     */
    public function getServer(ApiAccessService $apiAccessService, Request $request, SerializerInterface $serializer, Server $server = null)
    {
        if (!$apiAccessService->isSerialNumberValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Access denied',
            ), 403);
        }

        $instance = $this->getDoctrine()->getRepository(Instance::class)->findOneBy([
            'serialNumber' => $request->headers->get(Parameter::DCS_SERIAL_HEADER),
        ]);

        if (empty($instance)) {
            return $this->json([], 404);
        }
        $serverBelongsToInstance = $this->getDoctrine()->getRepository(Instance::class)
            ->serverBelongsToInstance($instance, $server);
        if (!$serverBelongsToInstance) {
            return $this->json([], 403);
        }

        return $this->json($serializer->normalize($server, 'json', ['groups' => 'api_instances']));
    }

    /**
     * @Route("/update-server/{server}", name="api.instances.update_server", methods={"PUT"})
     * @param ApiAccessService $apiAccessService
     * @param Request $request
     * @param Server $server
     * @return JsonResponse
     */
    public function updateServer(ApiAccessService $apiAccessService, Request $request, Server $server) : JsonResponse
    {
        if (!$apiAccessService->isSerialNumberValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Access denied',
            ));
        }

        $serial = $request->headers->get(Parameter::DCS_SERIAL_HEADER);
        $instance = $this->getDoctrine()
            ->getRepository(Instance::class)->findOneBy(['serialNumber' => $serial]);
        if ($instance === null) {
            return $this->json([
                'status' => 1,
                'message' => 'Invalid serial number',
            ]);
        }
        if (!$instance->serverBelongsToInstance($server)) {
            return $this->json([
                'status' => 1,
                'message' => 'Operation not permitted',
            ], 403);
        }
        $data = json_decode($request->getContent(), true);
        if (empty($data)) {
            return $this->json([
                'status' => 999,
                'message' => 'Failed to process your request',
            ]);
        }

        $form = $this->createForm(SenderServerType::class, $server);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($server);
            $em->flush();

            return $this->json([
                'status' => 0,
                'message' => 'Server data updated',
            ]);
        }
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->json([
            'status' => 999,
            'message' => 'Failed to save server data! Please, check your form.',
            'errors' => $errors
        ]);
    }

    /**
     * @param ApiAccessService $apiAccessService
     * @return JsonResponse
     * @Route("/get-credentials-data", name="api.instances.get_credentials_data", methods={"GET"})
     */
    public function getCredentialsData(ApiAccessService $apiAccessService)
    {
        if (!$apiAccessService->isSerialNumberValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Access denied',
            ));
        }

        return $this->json([
            'status' => 0,
            'data' => [
                'amqp' => [
                    'host' => $this->getParameter('amqp.host'),
                    'port' => $this->getParameter('amqp.port'),
                    'username' => $this->getParameter('amqp.username'),
                    'password' => $this->getParameter('amqp.password'),
                    'queue' => $this->getParameter('amqp.queue'),
                ]
            ],
        ]);
    }
}
