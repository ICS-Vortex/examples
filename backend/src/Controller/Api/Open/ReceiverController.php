<?php

namespace App\Controller\Api\Open;

use App\Entity\Setting;
use App\Helper\Helper;
use App\Message\DcsJsonMessage;
use App\Repository\SettingRepository;
use App\Service\ApiAccessService;
use App\Service\ParserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

/**
 * @Route("/api/open")
 */
class ReceiverController extends AbstractController
{
    /**
     * @deprecated
     * @Route("/receiver/enqueue-deprecated", name="api.open.receiver.enqueue_deprecated", methods={"POST"})
     * @param ParserService $parserService
     * @param Request $request
     * @param ApiAccessService $apiAccessService
     * @param MessageBusInterface $bus
     * @return JsonResponse
     */
    public function enqueueDeprecated(
        ParserService $parserService,
        Request $request,
        ApiAccessService $apiAccessService,
        MessageBusInterface $bus): JsonResponse
    {
        if (!$apiAccessService->isSerialNumberValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Access denied',
            ), 200);
        }
        $validator = Validation::createValidator();

//        $data = jsson_decode($request->getContent(), true);
//        $violations = $validator->validate($data, $this->getDcsEventConstraint());

//        if ($violations->count() > 0) {
//            return $this->json([
//                'status' => 1,
//                'message' => 'Invalid JSON',
//            ]);
//        }

        /** @var Setting $enqueueJsonsOption */
        $enqueueJsonsOption = $this->getDoctrine()->getRepository(Setting::class)
            ->findOneBy(['keyword' => SettingRepository::SETTING_PARSER_ENQUEUE_JSONS]);
        $data = json_decode($request->getContent(), true);
        try {
            if (!empty($enqueueJsonsOption)) {
                if ($enqueueJsonsOption->isEnabled()) {
                    $bus->dispatch(new DcsJsonMessage($request->getContent()));
                    return $this->json([
                        'status' => 0,
                        'message' => 'DCS event ' . ($data['event']) . ' added to queue'
                    ]);
                } else {
                    return $this->json($parserService->parse($request->getContent()));
                }
            }

            $bus->dispatch(new DcsJsonMessage($request->getContent()));
            return $this->json([
                'status' => 0,
                'message' => 'DCS event ' . ($data['event'] ?? 'undefined event') . ' added to queue'
            ]);

        } catch (Exception $e) {
            return $this->json([
                'status' => 1,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/receiver/enqueue", name="api.open.receiver.enqueue", methods={"POST"})
     * @param ParserService $parserService
     * @param Request $request
     * @param ApiAccessService $apiAccessService
     * @param MessageBusInterface $bus
     * @return JsonResponse
     */
    public function enqueue(ParserService $parserService, Request $request, ApiAccessService $apiAccessService, MessageBusInterface $bus): JsonResponse
    {
        if (!$apiAccessService->isServerIdentifierValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Invalid server ID',
            ));
        }
        /** @var Setting $enqueueJsonsOption */
        $enqueueJsonsOption = $this->getDoctrine()->getRepository(Setting::class)
            ->findOneBy(['keyword' => SettingRepository::SETTING_PARSER_ENQUEUE_JSONS]);
        $data = Helper::jsonToArray($request->getContent());
        try {
            if (!empty($enqueueJsonsOption)) {
                if ($enqueueJsonsOption->isEnabled()) {
                    $bus->dispatch(new DcsJsonMessage($request->getContent()));
                    return $this->json([
                        'status' => 0,
                        'message' => 'DCS event ' . ($data['event']) . ' added to queue'
                    ]);
                }
            }

            return $this->parse($parserService, $apiAccessService, $request);
        } catch (Exception $e) {
            return $this->json([
                'status' => 1,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ]);
        }
    }


    /**
     * @Route("/receiver/parse", name="api.open.receiver.parse", methods={"POST"})
     * @param ParserService $parserService
     * @param ApiAccessService $apiAccessService
     * @param Request $request
     * @return JsonResponse
     */
    public function parse(ParserService $parserService, ApiAccessService $apiAccessService, Request $request): JsonResponse
    {
        if (!$apiAccessService->isServerIdentifierValid()) {
            return $this->json(array(
                'status' => 1,
                'message' => 'Access denied',
            ));
        }

        try {
            return $this->json($parserService->parse($request->getContent()));
        } catch (Exception $e) {
            // TODO add logging.
            return $this->json([
                'status' => 1,
                'message' => "Error: {$e->getMessage()}, in file: {$e->getFile()}, on line: {$e->getLine()}",
            ]);
        }
    }

    public function getDcsEventConstraint(): Assert\Collection
    {
        return new Assert\Collection([
            'fields' => [
                'event' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 1]),
                ],
                'time' => [
                    new Assert\Type('string'),
                    new Assert\NotBlank(),
                    new Assert\DateTime('Y-m-d H:i:s'),
                ],
                'server' => new Assert\Required([
                    new Assert\Collection([
                        'fields' => [
                            'identifier' => [
                                new Assert\Type('string'),
                                new Assert\Uuid(),
                            ]
                        ]
                    ])
                ]),
                'simulationTime' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ],
                'theatre' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                    new Assert\Length(['min' => 1]),
                ],
                'mission' => new Assert\Optional([
                    new Assert\Type('string'),
                    new Assert\Length(['min' => 1]),
                ]),
                'objects' => new Assert\Optional([
                    'constraints' => [
                        new Assert\Type('array'),
                        new Assert\All([
                             new Assert\Collection([
                                 'altitude' => [
                                    new Assert\NotBlank(),
                                    new Assert\Type('numeric')
                                 ],
                                 'country' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('numeric')
                                 ],
                                 'heading' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('numeric')
                                 ],
                                 'id' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('numeric')
                                 ],
                                 'isHuman' => [
                                     new Assert\Type('boolean')
                                 ],
                                 'isStatic' => [
                                     new Assert\Type('boolean')
                                 ],
                                 'latitude' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('numeric')
                                 ],
                                 'longitude' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('numeric')
                                 ],
                                 'side' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('string')
                                 ],
                                 'title' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('string')
                                 ],
                                 'type' => [
                                     new Assert\NotBlank(),
                                     new Assert\Type('string')
                                 ],
                             ])
                        ]),
                    ]
                ]),
                'weather' => new Assert\Optional([
                    new Assert\Collection([
                        'fields' => [
                            'name' => [
                                new Assert\Type('string'),
                                new Assert\Length(['min' => 1]),
                            ],
                            'enable_fog' => [
                                new Assert\Type('boolean'),
                            ],
                            'qnh' => [
                                new Assert\Type('numeric'),
                            ],
                            'dust_density' => [
                                new Assert\Type('numeric'),
                            ],
                            'enable_dust' => [
                                new Assert\Type('boolean'),
                            ],
                            'atmosphere_type' => [
                                new Assert\Type('numeric'),
                            ],
                            'groundTurbulence' => [
                                new Assert\Type('numeric'),
                            ],
                            'visibility' => new Assert\Collection([
                                'fields' => [
                                    'distance' => [
                                        new Assert\Type('numeric'),
                                    ]
                                ]
                            ]),
                        ],
                        'allowExtraFields' => true,
                    ])
                ]),
                'init' => new Assert\Optional([
                    new Assert\Collection([
                        'fields'=> [
                            'nick' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'side' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'id' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'ip' => new Assert\Optional([
                                new Assert\Ip(),
                            ]),
                            'cat' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'type' => new Assert\Optional([
                                new Assert\Type('string'),
                                new Assert\Length(['min' => 1]),
                            ]),
                        ],
                    ])
                ]),
                'targ' => new Assert\Optional([
                    new Assert\Collection([
                        'fields'=> [
                            'nick' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'side' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'id' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'ip' => new Assert\Optional([
                                new Assert\Ip(),
                            ]),
                            'cat' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'typ' => new Assert\Optional([
                                new Assert\Type('string'),
                            ]),
                            'score' => new Assert\Optional([
                                new Assert\Type('numeric'),
                            ]),
                        ],
                    ])
                ]),
                'field' => new Assert\Optional([
                    new Assert\Collection([
                        'fields'=> [
                            'name' => new Assert\Optional([
                                new Assert\Type('string'),
                                new Assert\Length(['min' => 1]),
                            ]),
                        ],
                    ])
                ]),
            ],
            'allowExtraFields' => true,
        ]);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isRequestValid(Request $request): bool
    {
        $validator = Validation::createValidator();

        $data = Helper::jsonToArray(Helper::base64ToJson($request->getContent()));
        $violations = $validator->validate($data, $this->getDcsEventConstraint());

        if ($violations->count() > 0) {
            return false;
        }

        return true;
    }
}
